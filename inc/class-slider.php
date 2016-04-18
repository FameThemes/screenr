<?php
class Screenr_Slider {
    public $has_video =  false;
    public $number_item = 0;
    public $items = array();
    public $data;
    function __construct( $data ) {
        if ( empty( $data ) ){
            return false;
        }

        $this->data =  $data;
        $this->number_item = count( $this->data );
    }

    /**
     * Get file ext
     *
     * @param $file
     * @return string
     */
    function get_file_ext( $file ){
        $array = explode('.', $file );
        $extension = end($array);
        return strtolower( $extension );
    }

    /**
     * Get media data
     *
     * @param $media
     * @return array|bool
     */
    function get_media( $media ){
        $media = wp_parse_args( $media, array(
            'id' => '',
            'url' =>'',
        ) );

        $video_exts = array( 'mp4', 'webm', 'ogg');
        $r = array();
        if ( $media['id'] ) {
            $url =  wp_get_attachment_url( $media['id'] );
            $meta_data = wp_get_attachment_metadata( $media['id'] );
            // check is video
            if ( isset( $meta_data['mime_type'] ) && strpos( $meta_data['mime_type'], 'video' ) !== false ) {
                $r[ 'type' ] = 'video/'.$meta_data['fileformat'];
                $r[ 'url' ]  = $url;
                $this->has_video = true;
            } else {
                $r[ 'type' ] = 'image';
                $r[ 'url' ]  = $url;
            }

        }

        if ( empty( $r ) && $media['url'] != '' ){
            $ext = $this->get_file_ext( $media['url'] );
            if ( $ext && in_array( $ext , $video_exts ) ) {
                $r[ 'type' ] = 'video/'.$ext;
            } else {
                $r[ 'type' ] = 'image';
                $r[ 'url' ]  = $media['url'];
            }
        }

        return ( ! empty( $r ) ) ? $r : false;

    }

    function render(  ){
        $slider_data =  array();
        //wp_get_attachment_url( get_post_thumbnail_id() );
        foreach ( $this->data as $k => $item ){
            $item = wp_parse_args( $item, array(
                'title'         => '',
                'desc'          => '',
                'media'         => '',
                'align'         => '',
                'v_align'         => '',
                'button_1'      => '',
                'button_1_link' => '',
                'button_2'      => '',
                'button_2_link' => '',
            ) );
            $item['media'] = $this->get_media( $item['media'] );
            if ( ! $item['align'] ) {
                $item['align']  = 'center';
            }
            if ( ! $item['v_align'] ) {
                $item['v_align']  = 'center';
            }
            $slider_data[ $k ] = $this->render_item( $item );
        }

        return join( "\n", $slider_data );
    }

    function render_media( $media ){
        if ( ! $media ) {
            return '';
        }

        if ( $media['type'] == 'image' ){
            return '<img src="'.esc_url( $media['url'] ).'" alt="" />';
        } else {
            if ( $this->number_item == 1  ){
                return ' <video autoplay loop muted><source src="'.esc_url( $media['url'] ).'" type="'.esc_attr( $media['type'] ).'"></video>';
            } else {
                return ' <video muted><source src="'.esc_url( $media['url'] ).'" type="'.esc_attr( $media['type'] ).'"></video>';
            }
        }
    }

    function render_item( $item ){

        $html = '<div class="swiper-slide slide-align-'.esc_attr( $item['align'] ).' slide-v_align-'.esc_attr( $item['v_align'] ).'">';
            $html .= $this->render_media( $item['media'] );
            $html .= '<div class="swiper-slide-intro">';
                $html .= '<div class="swiper-intro-inner">';
                    if ( $item['title'] ) {
                        $html .= '<h2 class="swiper-slide-heading">'.wp_kses_post( $item['title'] ).'</h2>';
                    }
                    if ( $item['desc'] ) {
                        $html .= '<div class="swiper-slide-desc">'.wp_kses_post( $item['desc'] ).'</div>';
                    }

                    if ( $item['btn_1'] || $item['btn_2'] ) {
                        $html .= '<div class="swiper-slide-actions">';

                        if ( $item['btn_2'] ){
                            $html .= '<a class="btn btn-secondary-outline btn-lg" href="'.esc_url( $item['btn_2_link'] ).'">'.esc_html( $item['btn_1'] ).'</a> ';
                        }

                        if ( $item['btn_1'] ){
                            $html .= ' <a class="btn btn-primary btn-lg" href="'.esc_url( $item['btn_1_link'] ).'">'.esc_html( $item['btn_1'] ).'</a> ';
                        }

                        $html .= '</div>';
                    }

                $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="overlay"></div>';
        $html .= '</div>';

        return $html;
    }

}
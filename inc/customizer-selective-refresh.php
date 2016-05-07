<?php
/**
 * Add customizer selective refresh
 *
 * @since 1.2.1
 *
 * @param $wp_customize
 */
function screenr_customizer_partials( $wp_customize )
{

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }


    $selective_refresh_keys = array(
        // section slider
        array(
            'id' => 'slider',
            'settings' => array(
                //'features_id',
                'slider_items',
                'slider_overlay_color',
                'slider_fullscreen',
                'slider_pdtop',
                'slider_pdbotom',
            ),
        ),

        // section features
        array(
            'id' => 'features',
            'settings' => array(
                //'features_id',
                'features_title',
                'features_subtitle',
                'features_desc',
                'features_items',
                'features_layout',
            ),
        ),

        // section about
        array(
            'id' => 'about',
            'settings' => array(
                //'features_id',
                'about_title',
                'about_subtitle',
                'about_desc',
                'about_page_id',
                'about_page_content_type',
            ),
        ),

        // section videolightbox
        array(
            'id' => 'videolightbox',
            'settings' => array(
                'videolightbox_title',
                'videolightbox_video',
               // 'videolightbox_parallax_img',
            ),
        ),
        // section services
        array(
            'id' => 'services',
            'settings' => array(
                'services_title',
                'services_subtitle',
                'services_desc',
                'services_items',
                'services_layout',
            ),
        ),

        // section news
        array(
            'id' => 'news',
            'settings' => array(
                'news_title',
                'news_subtitle',
                'news_desc',
                'news_num_post',
                'news_layout',
            ),
        ),
        // section news
        array(
            'id' => 'contact',
            'settings' => array(
                'contact_title',
                'contact_subtitle',
                'contact_desc',
                'contact_content',
                'contact_items',
                'contact_layout',
            ),
        ),
        // section clients
        array(
            'id' => 'clients',
            'settings' => array(
                'clients_title',
                'clients_subtitle',
                'clients_desc',
                'clients_items',
                'clients_layout',
            ),
        ),


    );

    $selective_refresh_keys = apply_filters( 'screenr_customizer_partials_selective_refresh_keys', $selective_refresh_keys );

    foreach ( $selective_refresh_keys as $section ) {
        foreach ( $section['settings'] as $key ) {
            if ( $wp_customize->get_setting( $key ) ) {
                $wp_customize->get_setting( $key )->transport = 'postMessage';
            }
        }

        $tpl = 'section-parts/section-'.$section['id'].'.php';
        $wp_customize->selective_refresh->add_partial( 'section_'.$section['id'] , array(
            'selector' => 'section.section-'.$section['id'],
            'settings' => $section['settings'],
            'render_callback' => function () use ( $tpl ) {
                $GLOBALS['screenr_is_selective_refresh'] = true;
                $file = locate_template( $tpl );
                if ( $file ) {
                    include $file;
                }
            },
        ));
    }

}

add_action( 'customize_register', 'screenr_customizer_partials', 50 );
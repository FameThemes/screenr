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

        // section counter
        array(
            'id' => 'counter',
            'settings' => array(
                'counter_items',
                'counter_title',
                'counter_subtitle',
                'counter_desc',
                'counter_layout',
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

                'news_loadmore',
                'news_more_text',
                'news_more_link',
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

    $selective_refresh_keys = apply_filters( 'screenr_customizer_selective_refresh_sections', $selective_refresh_keys );

    foreach ( $selective_refresh_keys as $section ) {
        if ( $section['id'] ) {
            foreach ($section['settings'] as $key) {
                if ($wp_customize->get_setting($key)) {
                    $wp_customize->get_setting($key)->transport = 'postMessage';
                }
            }

            $func_name = isset( $section['callback'] ) ? $section['callback']: 'screenr_selective_refresh_render_section_content';
            $selector = isset( $section['selector']  ) ? $section['selector'] : '.section-' . $section['id'] ;

            $wp_customize->selective_refresh->add_partial('section-' . $section['id'], array(
                'selector' => $selector,
                'settings' => $section['settings'],
                'render_callback' => $func_name,
            ));
        }
    }

    
    $custom_css = array(
        // header
        'header_bg_color',
        'menu_color',
        'menu_hover_color',
        'menu_hover_bg_color',
        'header_t_bg_color',
        'menu_t_color',
        'menu_t_hover_color',
        'menu_t_hover_border_color',
        'logo_text_color',
        'menu_toggle_button_color',
        // footer
        'footer_widgets_bg',
        'footer_widgets_color',
        'footer_copyright_bg',
        'footer_copyright_color',
    );

    foreach ( $custom_css as $key ) {
        if ( $wp_customize->get_setting( $key ) ) {
            $wp_customize->get_setting( $key )->transport = 'postMessage';
        }
    }

    $wp_customize->selective_refresh->add_partial( 'custom_style' , array(
        'selector' => '#screenr-style-inline-css',
        'settings' => $custom_css,
        'render_callback' => 'screenr_custom_style',
    ));



}
add_action( 'customize_register', 'screenr_customizer_partials', 95 );

/**
 * Selective render content
 *
 * @param $partial
 * @param array $container_context
 */
function screenr_selective_refresh_render_section_content( $partial, $container_context = array() ) {
    $tpl = 'section-parts/'.$partial->id.'.php';
    $GLOBALS['screenr_is_selective_refresh'] = true;
    $file = apply_filters( 'screenr_selective_refresh_render_section_content_file', false,  $tpl, $partial, $container_context );
    if ( ! $file ) {
        $file = locate_template($tpl);
    }
    if ( $file ) {
        include $file;
    }
    do_action( 'screenr_selective_refresh_render_section_content', $file, $tpl, $partial, $container_context );
}
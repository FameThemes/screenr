<?php
/**
 * Front page
 *
 * @package Screenr
 */

get_header(); ?>

	<div id="content" class="site-content">
		<main id="main" class="site-main" role="main">
            <?php

            do_action( 'screenr_frontpage_before_section_parts' );

			if ( ! has_action( 'screenr_frontpage_section_parts' ) ) {

				$sections = apply_filters (
					'screenr_frontpage_sections_order',
					array(
                    	'slider',
						'features',
						'about',
						'videolightbox',
						'gallery',
						'services',
						'clients',
						'counter',
						'news',
						'contact'
                	)
				);

				foreach ( $sections as $section ){
                    // If  section not disable
                    if ( ! get_theme_mod( $section.'_disable' ) ) {
                        /**
                         * Hook before section
                         */
                        do_action('screenr_before_section_' . $section);
                        do_action('screenr_before_section_part', $section);

                        /**
                         * Load section template part
                         */
                        get_template_part('section-parts/section', $section);

                        /**
                         * Hook after section
                         */
                        do_action('screenr_after_section_part', $section);
                        do_action('screenr_after_section_' . $section);
                    }
				}

			} else {
				do_action( 'screenr_frontpage_section_parts' );
			}

            do_action( 'screenr_frontpage_after_section_parts' );

			?>
		</main><!-- #main -->
	</div><!-- #content -->

<?php get_footer(); ?>

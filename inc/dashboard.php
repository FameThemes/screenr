<?php

/**
 * Get theme actions required
 *
 * @return array|mixed|void
 */
function screenr_get_actions_required( ) {

    $actions = array();
    $front_page = get_option( 'page_on_front' );
    $actions['page_on_front'] = 'dismiss';
    $actions['page_template'] = 'dismiss';
    $actions['recommend_plugins'] = 'dismiss';
    if ( 'page' != get_option( 'show_on_front' ) ) {
        $front_page = 0;
    }
    if ( $front_page <= 0  ) {
        $actions['page_on_front'] = 'active';
        $actions['page_template'] = 'active';
    } else {
        $actions['page_template'] = 'dismiss';
        /*
        if ( get_post_meta( $front_page, '_wp_page_template', true ) == 'template-frontpage.php' ) {
            $actions['page_template'] = 'dismiss';
        } else {
            $actions['page_template'] = 'active';
        }
        */
    }

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }

    if ( ! empty( $recommend_plugins ) ) {

        foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
            $plugin_info = wp_parse_args( $plugin_info, array(
                'name' => '',
                'active_filename' => '',
            ) );
            if ( $plugin_info['active_filename'] ) {
                $active_file_name = $plugin_info['active_filename'] ;
            } else {
                $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            }
            if ( ! is_plugin_active( $active_file_name ) ) {
                $actions['recommend_plugins'] = 'active';
            }
        }

    }

    $actions = apply_filters( 'screenr_get_actions_required', $actions );
    $hide_by_click = get_option( 'screenr_actions_dismiss' );
    if ( ! is_array( $hide_by_click ) ) {
        $hide_by_click = array();
    }

    $n_active  = $n_dismiss = 0;
    $number_notice = 0;
    foreach ( $actions as $k => $v ) {
        if ( ! isset( $hide_by_click[ $k ] ) ) {
            $hide_by_click[ $k ] = false;
        }

        if ( $v == 'active' ) {
            $n_active ++ ;
            $number_notice ++ ;
            if ( $hide_by_click[ $k ] ) {
                if ( $hide_by_click[ $k ] == 'hide' ) {
                    $number_notice -- ;
                }
            }
        } else if ( $v == 'dismiss' ) {
            $n_dismiss ++ ;
        }

    }

    $return = array(
        'actions' => $actions,
        'number_actions' => count( $actions ),
        'number_active' => $n_active,
        'number_dismiss' => $n_dismiss,
        'hide_by_click'  => $hide_by_click,
        'number_notice'  => $number_notice,
    );
    if ( $return['number_notice'] < 0 ) {
        $return['number_notice'] = 0;
    }

    return $return;
}

/**
 * Reset action required when activate theme
 */
function screenr_reset_actions_required () {
    delete_option('screenr_actions_dismiss');
}
add_action('switch_theme', 'screenr_reset_actions_required');

/**
 * Add theme dashboard page
 */

add_action('admin_menu', 'screenr_theme_info');
function screenr_theme_info() {
    $actions = screenr_get_actions_required();
    $number_count = $actions['number_notice'];

    if ( $number_count > 0 ){
        $update_label = sprintf( _n( '%1$s action required', '%1$s actions required', $number_count, 'screenr' ), $number_count );
        $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $update_label )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
        $menu_title = sprintf( esc_html__('Screenr Theme %s', 'screenr'), $count );
    } else {
        $menu_title = esc_html__('Screenr Theme', 'screenr');
    }

    add_theme_page( esc_html__( 'Screenr Dashboard', 'screenr' ), $menu_title, 'edit_theme_options', 'ft_screenr', 'screenr_theme_info_page');
}


/**
 * Add admin notice when active theme, just show one timetruongsa@200811
 *
 * @return bool|null
 */
function screenr_admin_notice() {
    if ( ! function_exists( 'screenr_get_actions_required' ) ) {
        return false;
    }
    $actions = screenr_get_actions_required();
    $number_action = $actions['number_notice'];
    if ( $number_action > 0 ) {
        $theme_data = wp_get_theme();
        ?>
        <div class="updated notice notice-success notice-alt is-dismissible">
            <p><?php printf( esc_html__( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$s', 'screenr' ),  $theme_data->Name, '<a href="'.esc_url( add_query_arg( array( 'page' => 'ft_screenr' ), admin_url( 'themes.php' ) ) ).'">'.esc_html__( 'Welcome Page', 'screenr' ).'</a>'  ); ?></p>
        </div>

        <?php
    }
}

function screenr_admin_import_notice(){
    ?>
    <div class="updated notice notice-success notice-alt is-dismissible">
        <p><?php printf( esc_html__( 'Save time by import our demo data, your website will be set up and ready to customize in minutes. %s', 'screenr' ), '<a class="button button-secondary" href="'.esc_url( add_query_arg( array( 'page' => 'ft_screenr&tab=demo-data-importer' ), admin_url( 'themes.php' ) ) ).'">'.esc_html__( 'Import Demo Data', 'screenr' ).'</a>'  ); ?></p>
    </div>
    <?php
}

/**
 * Activation notice
 */
function screenr_one_activation_admin_notice(){
    global $pagenow;
    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
        add_action( 'admin_notices', 'screenr_admin_notice' );
        add_action( 'admin_notices', 'screenr_admin_import_notice' );
    }
}

function screenr_render_recommend_plugins( $recommend_plugins = array() ){
    foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
        $plugin_info = wp_parse_args( $plugin_info, array(
            'name' => '',
            'active_filename' => '',
        ) );
        $plugin_name = $plugin_info['name'];
        $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
        $button_class = 'install-now button';
        if ( $plugin_info['active_filename'] ) {
            $active_file_name = $plugin_info['active_filename'] ;
        } else {
            $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
        }

        if ( ! is_plugin_active( $active_file_name ) ) {
            $button_txt = esc_html__( 'Install Now', 'screenr' );
            if ( ! $status ) {
                $install_url = wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'install-plugin',
                            'plugin' => $plugin_slug
                        ),
                        network_admin_url( 'update.php' )
                    ),
                    'install-plugin_'.$plugin_slug
                );

            } else {
                $install_url = add_query_arg(array(
                    'action' => 'activate',
                    'plugin' => rawurlencode( $active_file_name ),
                    'plugin_status' => 'all',
                    'paged' => '1',
                    '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                ), network_admin_url('plugins.php'));
                $button_class = 'activate-now button-primary';
                $button_txt = esc_html__( 'Active Now', 'screenr' );
            }

            $detail_link = add_query_arg(
                array(
                    'tab' => 'plugin-information',
                    'plugin' => $plugin_slug,
                    'TB_iframe' => 'true',
                    'width' => '772',
                    'height' => '349',

                ),
                network_admin_url( 'plugin-install.php' )
            );

            echo '<div class="rcp">';
            echo '<h4 class="rcp-name">';
            echo esc_html( $plugin_name );
            echo '</h4>';
            echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'"><a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.$button_txt.'</a></p>';
            echo '<a class="plugin-detail thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Details', 'screenr' ).'</a>';
            echo '</div>';
        }

    }
}

function screenr_admin_dismiss_actions(){
    // delete_option( 'screenr_actions_dismiss' );
    if ( isset( $_GET['screenr_action_notice'] ) ) {
        $actions_dismiss =  get_option( 'screenr_actions_dismiss' );
        if ( ! is_array( $actions_dismiss ) ) {
            $actions_dismiss = array();
        }
        $action_key = stripslashes( $_GET['screenr_action_notice'] );
        if ( isset( $actions_dismiss[ $action_key ] ) &&  $actions_dismiss[ $action_key ] == 'hide' ){
            $actions_dismiss[ $action_key ] = 'show';
        } else {
            $actions_dismiss[ $action_key ] = 'hide';
        }
        update_option( 'screenr_actions_dismiss', $actions_dismiss );
        $url = $_SERVER['REQUEST_URI'];
        $url = remove_query_arg( 'screenr_action_notice', $url );
        wp_redirect( $url );
        die();
    }
}

add_action( 'admin_init', 'screenr_admin_dismiss_actions' );


add_action( 'load-themes.php',  'screenr_one_activation_admin_notice'  );
function screenr_theme_info_page() {
    $theme_data = wp_get_theme('screenr');
    // Check for current viewing tab
    $tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else {
        $tab = null;
    }
    $actions_r = screenr_get_actions_required();
    $number_action = $actions_r['number_notice'];
    $actions = $actions_r['actions'];

    $current_action_link =  add_query_arg( array( 'page' => 'ft_screenr', 'tab' => 'actions_required' ), admin_url( 'themes.php' ) );

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }

    ?>
    <div class="wrap about-wrap theme_info_wrapper">
        <h1><?php printf( esc_html__('Welcome to Screenr - Version %1s', 'screenr'), $theme_data->Version ); ?></h1>
        <div class="about-text">
            <?php esc_html_e( 'Big - Bold and stylish, Screenr is a multiuse parallax fullscreen theme well suited for business, portfolio, digital agency, freelancers and everyone else who appreciate good design.', 'screenr' ); ?>
        </div>
        <div class="demo-text">
            <?php printf( esc_html__( 'Be sure to check out the %1s or %2s to see some of the possibilities.', 'screenr' ), '<a target="_blank" href="http://demos.famethemes.com/screenr">Screenr Demo</a>', '<a target="_blank" href="http://demos.famethemes.com/screenr-plus">Screenr Plus Demo</a>'); ?>
        </div>

        <a target="_blank" href="<?php echo esc_url('http://www.famethemes.com/'); ?>" class="famethemes-badge wp-badge"><span>FameThemes</span></a>

        <h2 class="nav-tab-wrapper">

            <a href="?page=ft_screenr" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Screenr', 'screenr' ) ?></a>
            <a href="?page=ft_screenr&tab=actions_required" class="nav-tab<?php echo $tab == 'actions_required' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Actions Required', 'screenr' ); echo ( $number_action > 0 ) ? "<span class='theme-action-count'>{$number_action}</span>" : ''; ?></a>
            <?php do_action( 'screenr_admin_more_tabs', $tab ); ?>
            <a href="?page=ft_screenr&tab=demo-data-importer" class="nav-tab<?php echo $tab == 'demo-data-importer' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'One Click Demo Import', 'screenr' ); ?></span></a>
            <a href="?page=ft_screenr&tab=contribute" class="nav-tab<?php echo $tab == 'contribute' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Contribute', 'screenr' ); ?><span class="dashicons dashicons-thumbs-up"></span></a>
        </h2>

        <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info info-tab-content">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">

                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Customizer', 'screenr' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'screenr'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo esc_url( admin_url('customize.php') ); ?>" class="button button-primary"><?php esc_html_e('Start Customize', 'screenr'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Documentation', 'screenr' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('Need any help to setup and configure %s? Please have a look at our documentations instructions.', 'screenr'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo esc_url( 'http://docs.famethemes.com/category/113-screenr' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Screenr Documentation', 'screenr'); ?></a>
                            </p>
                            <?php do_action( 'screenr_dashboard_theme_links' ); ?>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Having Trouble, Need Support?', 'screenr' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('Support for %s WordPress theme is conducted through FameThemes support ticket system.', 'screenr'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo esc_url('https://www.famethemes.com/contact/' ); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html__('Create a support ticket', 'screenr'), $theme_data->Name); ?></a>
                            </p>
                        </div>
                    </div>

                    <div class="theme_info_right">
                        <img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="Theme Screenshot" />
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ( $tab == 'actions_required' ) { ?>
            <div class="action-required-tab info-tab-content">
                <?php if ( $actions_r['number_active'] > 0 ) { ?>
                    <?php $actions = wp_parse_args( $actions, array( 'page_on_front' => '', 'page_template' ) ) ?>

                    <?php if ( $actions['recommend_plugins'] == 'active' ) {  ?>
                        <div id="plugin-filter" class="recommend-plugins action-required">
                            <a  title="" class="dismiss" href="<?php echo add_query_arg( array( 'screenr_action_notice' => 'recommend_plugins' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['recommend_plugins'] == 'hide' ) { ?>
                                <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Recommend Plugins', 'screenr' ); ?></h3>
                            <?php
                            screenr_render_recommend_plugins( $recommend_plugins );
                            ?>
                        </div>
                    <?php } ?>

                    <?php if ( $actions['page_on_front'] == 'active' ) {  ?>
                        <div class="theme_link  action-required">
                            <a title="" class="dismiss" href="<?php echo add_query_arg( array( 'screenr_action_notice' => 'page_on_front' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['page_on_front'] == 'hide' ) { ?>
                                    <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                    <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Switch "Front page displays" to "A static page"', 'screenr' ); ?></h3>
                            <div class="about">
                                <p><?php esc_html_e( 'In order to have the one page look for your website, please go to Customize -&gt; Static Front Page and switch "Front page displays" to "A static page".', 'screenr' ); ?></p>
                            </div>
                            <p>
                                <a  href="<?php echo esc_url( admin_url('options-reading.php') ); ?>" class="button"><?php esc_html_e('Setup front page displays', 'screenr'); ?></a>
                            </p>
                        </div>
                    <?php } ?>

                    <?php if ( $actions['page_template'] == 'active' ) {  ?>
                        <div class="theme_link  action-required">
                            <a  title="" class="dismiss" href="<?php echo add_query_arg( array( 'screenr_action_notice' => 'page_template' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['page_template'] == 'hide' ) { ?>
                                    <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                    <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Set your homepage page template to "Frontpage".', 'screenr' ); ?></h3>

                            <div class="about">
                                <p><?php esc_html_e( 'In order to change homepage section contents, you will need to set template "Frontpage" for your homepage.', 'screenr' ); ?></p>
                            </div>
                            <p>
                                <?php
                                $front_page = get_option( 'page_on_front' );
                                if ( $front_page <= 0  ) {
                                    ?>
                                    <a  href="<?php echo esc_url( admin_url('options-reading.php') ); ?>" class="button"><?php esc_html_e('Setup front page displays', 'screenr'); ?></a>
                                    <?php
                                }

                                if ( $front_page > 0 && get_post_meta( $front_page, '_wp_page_template', true ) != 'template-frontpage.php' ) {
                                    ?>
                                    <a href="<?php echo esc_url( get_edit_post_link( $front_page ) ); ?>" class="button"><?php esc_html_e('Change homepage page template', 'screenr'); ?></a>
                                    <?php
                                }
                                ?>
                            </p>
                        </div>
                    <?php } ?>

                    <?php do_action( 'screenr_more_required_details', $actions ); ?>
                <?php  } else { ?>
                    <h3><?php  printf( esc_html__( 'Keep update with %s', 'screenr' ) , $theme_data->Name ); ?></h3>
                    <p><?php esc_html_e( 'Hooray! There are no required actions for you right now.', 'screenr' ); ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ( $tab == 'demo-data-importer' ) { ?>
            <div class="demo-import-tab-content info-tab-content">
                <?php if ( has_action( 'screenr_demo_import_content_tab' ) ) {
                    do_action( 'screenr_demo_import_content_tab' );
                } else { ?>
                    <div id="plugin-filter" class="demo-import-boxed">
                        <?php
                        $plugin_name = 'famethemes-demo-importer';
                        $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_name );
                        $button_class = 'install-now button';
                        $button_txt = esc_html__( 'Install Now', 'screenr' );
                        if ( ! $status ) {
                            $install_url = wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'action' => 'install-plugin',
                                        'plugin' => $plugin_name
                                    ),
                                    network_admin_url( 'update.php' )
                                ),
                                'install-plugin_'.$plugin_name
                            );

                        } else {
                            $install_url = add_query_arg(array(
                                'action' => 'activate',
                                'plugin' => rawurlencode( $plugin_name . '/' . $plugin_name . '.php' ),
                                'plugin_status' => 'all',
                                'paged' => '1',
                                '_wpnonce' => wp_create_nonce('activate-plugin_' . $plugin_name . '/' . $plugin_name . '.php'),
                            ), network_admin_url('plugins.php'));
                            $button_class = 'activate-now button-primary';
                            $button_txt = esc_html__( 'Active Now', 'screenr' );
                        }

                        $detail_link = add_query_arg(
                            array(
                                'tab' => 'plugin-information',
                                'plugin' => $plugin_name,
                                'TB_iframe' => 'true',
                                'width' => '772',
                                'height' => '349',

                            ),
                            network_admin_url( 'plugin-install.php' )
                        );

                        echo '<p>';
                        printf( esc_html__(
                            'Hey, you will need to install and activate the %1$s plugin first.', 'screenr' ),
                            '<a class="thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'FameThemes Demo Importer', 'screenr' ).'</a>'
                        );
                        echo '</p>';

                        echo '<p class="plugin-card-'.esc_attr( $plugin_name ).'"><a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_name ).'" class="'.esc_attr( $button_class ).'">'.$button_txt.'</a></p>';

                        ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>


        <?php if ( $tab == 'contribute' ) { ?>
            <div class="contribute-tab-content feature-section three-col">
                <h2><?php esc_html_e( 'How can I contribute?', 'screenr' ); ?></h2>
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Found a bug? Want to contribute with a fix or create a new feature?', 'screenr' ); ?></strong></p>
                        <p><?php esc_html_e('GitHub is the place to go!', 'screenr'); ?></p>
                        <p>
                            <a href="https://github.com/FameThemes/screenr" target="_blank" class="button button-primary"><?php esc_html_e('Screenr on GitHub', 'screenr'); ?> <span class="dashicons dashicons-external"></span></a>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you a polyglot? Want to translate Screenr into your own language?', 'screenr' ); ?></strong></p>
                        <p><?php esc_html_e('Get involved at WordPress.org.', 'screenr'); ?></p>
                        <p>
                            <a href="https://translate.wordpress.org/projects/wp-themes/screenr" target="_blank" class="button button-primary"><?php esc_html_e('Translate Screenr', 'screenr'); ?> <span class="dashicons dashicons-external"></span></a>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you enjoying Screenr theme?', 'screenr' ); ?></strong></p>
                        <p><?php _e('Rate our theme on <a target="_blank" href="https://wordpress.org/support/theme/screenr/reviews/?filter=5#postform">WordPress.org</a>. We\'d really appreciate it!', 'screenr'); ?></p>
                        <p><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></p>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php do_action( 'screenr_more_tabs_details', $tab ); ?>

    </div> <!-- END .theme_info -->
    <script type="text/javascript">
        jQuery(  document).ready( function( $ ){
            $( 'body').addClass( 'about-php' );
        } );
    </script>
    <?php
}

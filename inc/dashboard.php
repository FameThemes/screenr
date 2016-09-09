<?php
/**
 * Add theme dashboard page
 */

add_action('admin_menu', 'screenr_theme_info');
function screenr_theme_info() {
    //$theme_data = wp_get_theme('screenr');

    $actions = screenr_get_actions_required();
    $n = array_count_values( $actions );
    $number_count =  0;
    if ( $n && isset( $n['active'] ) ) {
        $number_count = $n['active'];
    }

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
    $n = array_count_values( $actions );
    $number_action =  0;
    if ( $n && isset( $n['active'] ) ) {
        $number_action = $n['active'];
    }
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

function screenr_one_activation_admin_notice(){
    global $pagenow;
    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
        add_action( 'admin_notices', 'screenr_admin_notice' );
        add_action( 'admin_notices', 'screenr_admin_import_notice' );
    }
}

/* activation notice */
add_action( 'load-themes.php',  'screenr_one_activation_admin_notice'  );

function screenr_theme_info_page() {

    $theme_data = wp_get_theme('screenr');

    if ( isset( $_GET['screenr_action_dismiss'] ) ) {
        $actions_dismiss =  get_option( 'screenr_actions_dismiss' );
        if ( ! is_array( $actions_dismiss ) ) {
            $actions_dismiss = array();
        }
        $actions_dismiss[ stripslashes( $_GET['screenr_action_dismiss'] ) ] = 'dismiss';
        update_option( 'screenr_actions_dismiss', $actions_dismiss );
    }

    // Check for current viewing tab
    $tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else {
        $tab = null;
    }

    $actions = screenr_get_actions_required();
    $n = array_count_values( $actions );
    $number_action =  0;
    if ( $n && isset( $n['active'] ) ) {
        $number_action = $n['active'];
    }
    $current_action_link =  add_query_arg( array( 'page' => 'ft_screenr', 'tab' => 'actions_required' ), admin_url( 'themes.php' ) );
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
                                <a href="<?php echo esc_url( 'http://docs.famethemes.com/category/76-screenr' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Screenr Documentation', 'screenr'); ?></a>
                            </p>
                            <?php do_action( 'screenr_dashboard_theme_links' ); ?>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Having Trouble, Need Support?', 'screenr' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('Support for %s WordPress theme is conducted through FameThemes support ticket system.', 'screenr'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo esc_url('https://www.famethemes.com/contact/' ); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html('Create a support ticket', 'screenr'), $theme_data->Name); ?></a>
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
                <?php if ( $number_action > 0 ) { ?>
                    <?php $actions = wp_parse_args( $actions, array( 'page_on_front' => '', 'page_template' ) ) ?>
                    <?php if ( $actions['page_on_front'] == 'active' ) {  ?>
                        <div class="theme_link  action-required">
                            <a title="<?php  esc_attr_e( 'Dismiss', 'screenr' ); ?>" class="dismiss" href="<?php echo add_query_arg( array( 'screenr_action_dismiss' => 'page_on_front' ), $current_action_link ); ?>"><span class="dashicons dashicons-dismiss"></span></a>
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
                            <a  title="<?php  esc_attr_e( 'Dismiss', 'screenr' ); ?>" class="dismiss" href="<?php echo add_query_arg( array( 'screenr_action_dismiss' => 'page_template' ), $current_action_link ); ?>"><span class="dashicons dashicons-dismiss"></span></a>
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
                    <div class="demo-import-boxed">
                        <p><?php  printf( __( '<b>Hey,</b> you will need to install and activate the FameThemes Demo Importer plugin first, %s now from Github.', 'screenr' ) , '<a href="https://github.com/FameThemes/famethemes-demo-importer/archive/master.zip">'. esc_html__( 'download it', 'screenr' ) .'</a>' ); ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ( $tab == 'contribute' ) { ?>
            <div class="contribute-tab-content feature-section three-col">
                <h2>How can I contribute?</h2>
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
    <?php
}

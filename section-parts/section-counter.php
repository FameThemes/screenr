<?php
$title      = get_theme_mod( 'news_title', __( '', 'screenr' ) );
$subtitle   = get_theme_mod( 'news_subtitle', __( 'Some Fun Facts about our agency?', 'screenr' ) );
$desc       = get_theme_mod( 'news_desc' );
?>

<section id="" class="screenr-section section-counter section-padding section-padding-larger section-meta">
    <div class="container">
        <div class="section-title-area">
            <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
            <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
            <?php if ( $desc ) { ?><div class="section-desc"><?php echo do_shortcode( apply_filters( 'the_content', $desc ) ); ?></div><?php } ?>
        </div>
        <div class="counter-contents">
            <div class="section-content">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="counter-item counter-item-bg1">
                            <span class="counter-title">Projects</span>
                            <div class="counter__number">
                                <span class="n counter">456</span>
                            </div>
                            <i class="fa fa-briefcase fa-3x"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="counter-item counter-item-bg2">
                            <span class="counter-title">Feedback</span>
                            <div class="counter__number">
                                <span class="n counter">99%</span>
                            </div>
                            <i class="fa fa-gift fa-3x"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="counter-item counter-item-bg3">
                            <span class="counter-title">Pizzas Ordered</span>
                            <div class="counter__number">
                                <span class="n counter">254</span>
                            </div>
                            <i class="fa fa-cart-arrow-down fa-3x"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="counter-item counter-item-bg4">
                            <span class="counter-title">Average Cost</span>
                            <div class="counter__number">
                                <span class="n counter">$32</span>
                            </div>
                            <i class="fa fa-credit-card fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

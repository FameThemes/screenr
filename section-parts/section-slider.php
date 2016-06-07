<?php
$slider = new Screenr_Slider( get_theme_mod( 'slider_items' ) );
$autoplay = get_theme_mod( 'slider_autoplay' , 7000 );
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod( 'slider_id', 'hero' ) ); ?>" class="section-slider screenr-section swiper-slider <?php echo screenr_is_fullscreen() ? 'full-screen' : ''; ?> fixed" >
<?php } ?>
    <div class="swiper-container" data-autoplay="<?php echo intval( $autoplay ); ?>">
        <div class="swiper-wrapper">
            <?php
             echo $slider->render();
            ?>
        </div>
        <?php if ( $slider->number_item > 1 ) { ?>
        <div class="swiper-button-prev"><i class="fa fa-angle-left" ></i> <div class="slide-count"> <span class="slide-current" >1</span> <span class="sep"></span> <span class="slide-total">3</span> </div></div>
        <div class="swiper-button-next"><i class="fa fa-angle-right" ></i> <div class="slide-count"> <span class="slide-current" >1</span> <span class="sep"></span> <span class="slide-total">3</span> </div> </div>
        <?php } ?>
        <div class="btn-next-section"></div>
    </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>

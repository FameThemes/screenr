<?php

$slider = new Screenr_Slider( get_theme_mod( 'screenr_slider_items' ) );

?>
<div id="<?php echo esc_attr( get_theme_mod( 'screenr_slider_id', 'hero-slider' ) ); ?>" class="swiper-slider full-screen fixed" >
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
             echo $slider->render();
            ?>
        </div>
        <!-- Add Pagination -->
        <!-- <div class="swiper-pagination swiper-pagination-white"></div> -->
        <!-- Add Navigation -->
        <div class="swiper-button-prev"><i class="fa fa-angle-left" ></i> <div class="slide-count"> <span class="slide-current" >1</span> <span class="sep"></span> <span class="slide-total">3</span> </div></div>
        <div class="swiper-button-next"><i class="fa fa-angle-right" ></i> <div class="slide-count"> <span class="slide-current" >1</span> <span class="sep"></span> <span class="slide-total">3</span> </div> </div>
    </div>
</div>

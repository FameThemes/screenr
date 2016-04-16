<?php

$slider = new Screenr_Slider( get_theme_mod( 'screenr_slider_items' ) );

?>
<div class="swiper-slider full-screen fixed" >
    <div class="swiper-container">

        <div class="swiper-wrapper">
            <?php
             echo $slider->render();
            ?>
        </div>
        <!-- Add Pagination -->
        <!-- <div class="swiper-pagination swiper-pagination-white"></div> -->
        <!-- Add Navigation -->
        <div class="swiper-button-prev swiper-button-white"></div>
        <div class="swiper-button-next swiper-button-white"></div>
    </div>
</div>

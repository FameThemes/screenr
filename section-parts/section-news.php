<section id="news" class="section-news section-padding">
    <div class="container">
        <div class="section-title-area">
            <div class="section-subtitle">Section Subtitle</div>
            <h2 class="section-title">Latest News</h2>
            <div class="section-desc"></div>
        </div>
        <div class="section-news-content">
            <div class="row">
                <div class="content-grid">
                    <?php $latest_posts = new WP_Query( array( 'post_per_page' => 3 ) ); ?>
                    <?php if ( $latest_posts->have_posts() ) : ?>
                        <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-4' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                                        <div class="entry-thumb">
                                            <?php the_post_thumbnail( 'screenr-blog-grid-small' ); ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            	<header class="entry-header">
                            		<?php the_title( '<h2 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            	</header><!-- .entry-header -->
                            	<div class="entry-excerpt">
                            		<?php the_excerpt(); ?>
                            	</div><!-- .entry-content -->
                            </article><!-- #post-## -->
                        <?php endwhile; ?>
                    <?php else : ?>
    					<?php get_template_part( 'template-parts/content', 'none' ); ?>
    				<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
/**
 * Module: Articles (Full Page Redirect or List)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-module-content">
    <h2>Professional Articles</h2>
    <p>Read the latest industry news and career advice.</p>

    <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 30px;">
        <?php
        $articles = new WP_Query( array(
            'post_type' => 'post', // Default WP posts as articles
            'posts_per_page' => 6
        ) );

        if ( $articles->have_posts() ) : while ( $articles->have_posts() ) : $articles->the_post();
        ?>
            <div class="article-card" style="border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; background: white;">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="article-thumb" style="height: 160px; overflow: hidden;">
                        <?php the_post_thumbnail('medium_large', array('style' => 'width:100%; height:100%; object-fit: cover;')); ?>
                    </div>
                <?php endif; ?>
                <div class="article-body" style="padding: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: var(--jobs-primary-color);"><?php the_title(); ?></h4>
                    <div style="font-size: 0.85em; color: #777; margin-bottom: 15px;"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></div>
                    <a href="<?php the_permalink(); ?>" class="jobs-btn-small">Read More</a>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); else : ?>
            <p>No articles published yet.</p>
        <?php endif; ?>
    </div>
</div>

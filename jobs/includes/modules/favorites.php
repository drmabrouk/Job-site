<?php
/**
 * Module: Favorites (Modal)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();
$last_five = array_slice( $favorites, 0, 5 );
?>
<div class="jobs-module-content">
    <h3>Saved Jobs</h3>
    <p>Your last 5 saved opportunities.</p>

    <div class="favorites-list" style="margin-top: 20px;">
        <?php if ( $last_five ) :
            $query = new WP_Query( array(
                'post_type' => 'job',
                'post__in' => $last_five,
                'orderby' => 'post__in'
            ) );
            if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        ?>
            <div class="fav-item" style="padding: 15px; border: 1px solid rgba(29, 52, 105, 0.05); border-radius: 12px; margin-bottom: 10px; background: rgba(252, 228, 236, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="color: var(--jobs-primary-color);"><?php the_title(); ?></strong>
                    <span class="jobs-favorite-toggle dashicons dashicons-heart" data-job-id="<?php the_ID(); ?>" style="cursor: pointer; color: #e91e63;"></span>
                </div>
                <div style="font-size: 0.8em; color: #666; margin-top: 5px;">
                    <?php echo get_post_meta(get_the_ID(), '_company_name', true); ?>
                </div>
                <div style="margin-top: 10px;">
                    <a href="<?php the_permalink(); ?>" class="jobs-btn-small" style="font-size: 0.75em; padding: 4px 10px;">View Full Details</a>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>
        <?php else : ?>
            <p style="text-align: center; color: #999; padding: 40px;">No saved jobs yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * Module: Favorites (Modal)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();
?>
<div class="jobs-module-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin:0;">Saved Opportunities</h3>
        <span style="font-size: 0.8em; color: #64748b;"><?php echo count($favorites); ?> Jobs Saved</span>
    </div>

    <div class="favorites-list" style="display: flex; flex-direction: column; gap: 12px;">
        <?php if ( $favorites ) :
            $query = new WP_Query( array(
                'post_type' => 'job',
                'post__in' => $favorites,
                'orderby' => 'post__in',
                'posts_per_page' => -1
            ) );
            if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $logo = get_post_meta(get_the_ID(), '_company_logo', true);
        ?>
            <div class="fav-item" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; transition: all 0.2s;">
                <div class="fav-logo" style="width: 45px; height: 45px; border-radius: 10px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f1f5f9;">
                    <?php if ($logo) : ?>
                        <img src="<?php echo esc_url($logo); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else : ?>
                        <span class="dashicons dashicons-building" style="color: #cbd5e1;"></span>
                    <?php endif; ?>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h4 style="margin: 0; font-size: 0.95em; color: var(--jobs-primary-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;"><?php the_title(); ?></h4>
                        <span class="jobs-favorite-toggle dashicons dashicons-heart active" data-job-id="<?php the_ID(); ?>" style="cursor: pointer; color: #f56565; font-size: 18px;"></span>
                    </div>
                    <div style="font-size: 0.75em; color: #64748b; margin-top: 2px;">
                        <?php echo get_post_meta(get_the_ID(), '_company_name', true); ?>
                    </div>
                </div>
                <div style="margin-left: auto;">
                    <a href="<?php the_permalink(); ?>" class="jobs-btn-minimal view-job-btn" style="padding: 6px 12px; font-size: 0.7em;">Details</a>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                <span class="dashicons dashicons-heart" style="font-size: 48px; width: 48px; height: 48px; opacity: 0.2; margin-bottom: 10px;"></span>
                <p>No saved jobs yet. Explore and save your favorite opportunities!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

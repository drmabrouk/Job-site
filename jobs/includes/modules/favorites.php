<?php
/**
 * Module: Favorites (Modal)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();

// Normalize to associative [id => timestamp]
if ( ! empty( $favorites ) && array_values( $favorites ) === $favorites ) {
    $temp = array();
    foreach ( $favorites as $fid ) {
        if ( is_numeric( $fid ) ) {
            $temp[$fid] = time();
        }
    }
    $favorites = $temp;
}
?>
<div class="jobs-module-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin:0;">Saved Opportunities</h3>
        <span style="font-size: 0.8em; color: #64748b;"><?php echo count($favorites); ?> Jobs Saved</span>
    </div>

    <div class="favorites-list" style="display: flex; flex-direction: column; gap: 12px;">
        <?php if ( $favorites ) :
            $job_ids = array_keys($favorites);
            $query = new WP_Query( array(
                'post_type' => 'job',
                'post__in' => $job_ids,
                'posts_per_page' => -1
            ) );
            if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $job_id = get_the_ID();
                $logo = get_post_meta($job_id, '_company_logo', true);
                $saved_date = isset($favorites[$job_id]) ? date('M d, Y', $favorites[$job_id]) : 'Recently';

                $deadline = get_post_meta($job_id, '_job_deadline', true);
                $expiry_text = 'No deadline';
                if ($deadline) {
                    $remaining = strtotime($deadline) - time();
                    if ($remaining > 0) {
                        $days = ceil($remaining / (24 * 3600));
                        $expiry_text = $days . ' days left';
                    } else {
                        $expiry_text = 'Expired';
                    }
                }
        ?>
            <div class="fav-item" style="display: flex; align-items: center; gap: 15px; padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; transition: all 0.2s; position: relative;">
                <div class="fav-logo" style="width: 50px; height: 50px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f1f5f9; flex-shrink: 0;">
                    <?php if ($logo) : ?>
                        <img src="<?php echo esc_url($logo); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else : ?>
                        <span class="dashicons dashicons-building" style="color: #cbd5e1;"></span>
                    <?php endif; ?>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600; color: var(--jobs-primary-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%;"><?php the_title(); ?></h4>
                        <span class="jobs-favorite-toggle dashicons dashicons-heart active" data-job-id="<?php echo $job_id; ?>" style="cursor: pointer; color: #f56565; font-size: 18px;"></span>
                    </div>
                    <div style="font-size: 0.8em; color: #64748b; margin-top: 4px; display: flex; align-items: center; gap: 10px;">
                        <span><?php echo get_post_meta($job_id, '_company_name', true); ?></span>
                        <span style="width: 3px; height: 3px; border-radius: 50%; background: #cbd5e1;"></span>
                        <span title="Date Saved">Saved: <?php echo $saved_date; ?></span>
                    </div>
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.7em; padding: 2px 8px; border-radius: 4px; background: <?php echo ($expiry_text === 'Expired' ? '#fee2e2' : '#f0fdf4'); ?>; color: <?php echo ($expiry_text === 'Expired' ? '#991b1b' : '#166534'); ?>; font-weight: 600;">
                            <span class="dashicons dashicons-clock" style="font-size: 12px; width: 12px; height: 12px; vertical-align: middle;"></span>
                            <?php echo $expiry_text; ?>
                        </span>
                    </div>
                </div>
                <div style="margin-left: 15px;">
                    <a href="<?php the_permalink(); ?>" class="jobs-btn-minimal view-job-btn" style="padding: 8px 16px; font-size: 0.75em; border-radius: 10px;">View</a>
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

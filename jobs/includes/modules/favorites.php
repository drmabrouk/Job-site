<?php
/**
 * Module: Favorites (Saved Jobs)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$favorites = get_user_meta( $current_user_id, 'jobs_favorites', true ) ?: array();

if ( ! empty( $favorites ) ) {
    // Filter to only existing and published jobs
    $args = array(
        'post_type' => 'job',
        'post__in'  => array_slice(array_reverse($favorites), 0, 5),
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'orderby' => 'post__in'
    );
    $query = new WP_Query( $args );

    // Sync user meta if some jobs were deleted/archived
    $valid_ids = array();
}

?>
<div class="jobs-module-content" id="jobs-favorites">
    <h3>My Saved Jobs</h3>
    <?php if ( ! empty( $favorites ) && $query->have_posts() ) : ?>
        <div class="jobs-results-container">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $valid_ids[] = get_the_ID();
                include JOBS_PLUGIN_DIR . 'templates/job-card.php';
            endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
        // Real-time sync: remove invalid IDs from user meta
        if ( count($valid_ids) !== count($favorites) ) {
            update_user_meta( $current_user_id, 'jobs_favorites', $valid_ids );
        }
        ?>
    <?php else : ?>
        <p>You haven't saved any jobs yet, or your saved jobs are no longer available.</p>
        <?php
        // If query failed but meta not empty, clear meta
        if ( ! empty($favorites) ) update_user_meta( $current_user_id, 'jobs_favorites', array() );
        ?>
    <?php endif; ?>
</div>

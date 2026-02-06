<?php
/**
 * Module: Applications Submitted (For Job Seekers)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();

$apps = new WP_Query( array(
    'post_type'   => 'application',
    'author'      => $current_user_id,
    'posts_per_page' => -1,
) );
?>
<div class="jobs-module-content" id="jobs-applications-submitted">
    <h3>Applications I've Submitted</h3>
    <?php if ( $apps->have_posts() ) : ?>
        <div class="applications-grid">
            <?php while ( $apps->have_posts() ) : $apps->the_post();
                $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                $job_status = get_post_status( $job_id );
            ?>
                <div class="app-card" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 15px; margin-bottom: 10px; border-radius: 8px;">
                    <strong>Job:</strong> <?php echo get_the_title($job_id); ?>
                    <span class="status-badge" style="font-size: 0.8em; padding: 2px 6px; background: #eee; border-radius: 4px;">
                        <?php echo ucfirst($job_status); ?>
                    </span>
                    <p style="margin-top: 5px; font-size: 0.9em;">Applied on: <?php echo get_the_date(); ?></p>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <p>You haven't applied for any jobs yet.</p>
    <?php endif; ?>
</div>

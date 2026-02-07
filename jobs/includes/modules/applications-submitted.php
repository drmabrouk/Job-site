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
    <div style="margin-bottom: 30px;">
        <h3 style="margin: 0;">My Job Applications</h3>
        <p style="font-size: 0.9em; color: #64748b;">Track the status of all your professional applications.</p>
    </div>

    <?php if ( $apps->have_posts() ) : ?>
        <div class="applications-list" style="display: flex; flex-direction: column; gap: 15px;">
            <?php while ( $apps->have_posts() ) : $apps->the_post();
                $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                $job_status = get_post_status( $job_id );
                $comp_name = get_post_meta($job_id, '_company_name', true);
            ?>
                <div class="app-card" style="display: flex; justify-content: space-between; align-items: center; background: white; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.2s;">
                    <div>
                        <h4 style="margin: 0; color: var(--jobs-primary-color); font-size: 1.1em;"><?php echo get_the_title($job_id); ?></h4>
                        <div style="display:flex; align-items: center; gap: 10px; margin-top: 4px; font-size: 0.85em; color: #64748b;">
                            <span><?php echo esc_html($comp_name); ?></span>
                            <span>•</span>
                            <span>Applied <?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span class="status-badge" style="display: inline-block; font-size: 0.75em; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; <?php echo ($job_status === 'publish' ? 'background: #dcfce7; color: #166534;' : 'background: #f1f5f9; color: #475569;'); ?>">
                            <?php echo ($job_status === 'publish' ? 'Active Listing' : ucfirst($job_status)); ?>
                        </span>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <p>You haven't applied for any jobs yet.</p>
    <?php endif; ?>
</div>

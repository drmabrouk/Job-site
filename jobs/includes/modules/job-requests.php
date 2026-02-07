<?php
/**
 * Module: Job Requests (User Applications List)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$args = array(
    'post_type' => 'application',
    'author'    => $user_id,
    'posts_per_page' => -1,
);
$apps_query = new WP_Query( $args );
?>
<div class="jobs-module-content" id="jobs-requests-module">
    <div style="margin-bottom: 25px;">
        <h3 style="margin: 0;">My Job Applications</h3>
        <p style="font-size: 0.9em; color: #64748b;">A professional overview of the positions you've applied for.</p>
    </div>

    <div class="apps-list-container">
        <?php if ( $apps_query->have_posts() ) : ?>
            <div class="apps-table-header" style="display: grid; grid-template-columns: 2fr 1fr 1fr; padding: 15px; background: #f1f5f9; border-radius: 12px; margin-bottom: 15px; font-weight: 700; font-size: 0.85em; color: #475569;">
                <span>Job Title</span>
                <span>Applied On</span>
                <span>Status</span>
            </div>
            <div class="apps-rows">
                <?php while ( $apps_query->have_posts() ) : $apps_query->the_post();
                    $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                    $status = get_post_meta( get_the_ID(), '_application_status', true ) ?: 'Pending';
                ?>
                    <div class="app-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr; padding: 20px 15px; border-bottom: 1px solid #f1f5f9; align-items: center; transition: background 0.2s;">
                        <div class="app-job-info">
                            <strong style="display: block; color: var(--jobs-primary-color);"><?php echo $job_id ? get_the_title($job_id) : 'Unknown Job'; ?></strong>
                            <span style="font-size: 0.8em; color: #94a3b8;"><?php echo $job_id ? get_post_meta($job_id, '_company_name', true) : ''; ?></span>
                        </div>
                        <div class="app-date" style="font-size: 0.9em; color: #64748b;">
                            <?php echo get_the_date('M d, Y'); ?>
                        </div>
                        <div class="app-status">
                            <span class="status-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 0.75em; font-weight: 600; background: <?php echo ($status === 'Rejected' ? '#fee2e2' : ($status === 'Accepted' ? '#dcfce7' : '#fef9c3')); ?>; color: <?php echo ($status === 'Rejected' ? '#991b1b' : ($status === 'Accepted' ? '#166534' : '#854d0e')); ?>;">
                                <?php echo esc_html($status); ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0;">
                <span class="dashicons dashicons-portfolio" style="font-size: 48px; width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 15px;"></span>
                <p style="color: #64748b;">You haven't applied for any jobs yet.</p>
                <a href="<?php echo home_url('/job-search'); ?>" class="jobs-btn-small" style="margin-top: 15px; display: inline-block;">Browse Jobs</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.app-row:hover {
    background: #f8fafc;
}
.app-row:last-child {
    border-bottom: none;
}
</style>

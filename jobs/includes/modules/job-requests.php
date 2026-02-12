<?php
/**
 * Module: Job Requests (User Applications List)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$is_system_admin = Jobs_Permission_Service::is_system_admin($user_id);
$is_employer = Jobs_Permission_Service::can_post_job($user_id);

if ($is_system_admin) {
    // System admin sees all applications
    $args = array(
        'post_type' => 'application',
        'posts_per_page' => -1
    );
} elseif ($is_employer) {
    // Get applications received for jobs posted by this employer
    $my_jobs = get_posts(array(
        'post_type' => 'job',
        'author' => $user_id,
        'fields' => 'ids',
        'posts_per_page' => -1
    ));

    if (!empty($my_jobs)) {
        $args = array(
            'post_type' => 'application',
            'meta_query' => array(
                array(
                    'key' => '_job_id',
                    'value' => $my_jobs,
                    'compare' => 'IN'
                )
            ),
            'posts_per_page' => -1
        );
    } else {
        $args = array('post_type' => 'none');
    }
} else {
    // Seeker's own applications
    $args = array(
        'post_type' => 'application',
        'author'    => $user_id,
        'posts_per_page' => -1,
    );
}

$apps_query = new WP_Query( $args );
?>
<div class="jobs-module-content" id="jobs-requests-module">
    <div style="margin-bottom: 25px;">
        <h3 style="margin: 0;"><?php echo $is_employer ? 'Applications Received' : 'My Job Applications'; ?></h3>
        <p style="font-size: 0.9em; color: #64748b;"><?php echo $is_employer ? 'Review and respond to candidates who applied for your listings.' : 'A professional overview of the positions you\'ve applied for.'; ?></p>
    </div>

    <div class="apps-list-container">
        <?php if ( $apps_query->have_posts() ) : ?>
            <div class="apps-table-header" style="display: grid; grid-template-columns: <?php echo $is_employer ? '2fr 1fr 1.5fr' : '2fr 1fr 1fr'; ?>; padding: 15px; background: #f1f5f9; border-radius: 12px; margin-bottom: 15px; font-weight: 700; font-size: 0.85em; color: #475569;">
                <span><?php echo $is_employer ? 'Candidate / Position' : 'Job Title'; ?></span>
                <span>Applied On</span>
                <span>Status / Actions</span>
            </div>
            <div class="apps-rows">
                <?php while ( $apps_query->have_posts() ) : $apps_query->the_post();
                    $app_id = get_the_ID();
                    $job_id = get_post_meta( $app_id, '_job_id', true );
                    $status = get_post_meta( $app_id, '_application_status', true ) ?: 'Pending';
                    $candidate = get_userdata(get_post_field('post_author', $app_id));
                ?>
                    <div class="app-row" style="display: grid; grid-template-columns: <?php echo $is_employer ? '2fr 1fr 1.5fr' : '2fr 1fr 1fr'; ?>; padding: 20px 15px; border-bottom: 1px solid #f1f5f9; align-items: center; transition: background 0.2s;">
                        <div class="app-job-info">
                            <?php if ($is_employer): ?>
                                <strong style="display: block; color: var(--jobs-primary-color);"><?php echo esc_html($candidate->display_name); ?></strong>
                                <span style="font-size: 0.8em; color: #94a3b8;">Applying for: <?php echo get_the_title($job_id); ?></span>
                                <a href="<?php echo jobs_get_profile_link($candidate->ID); ?>" target="_blank" style="font-size: 0.75em; color: #1d3469; text-decoration: underline; display: block; margin-top: 4px;">View Profile</a>
                            <?php else: ?>
                                <strong style="display: block; color: var(--jobs-primary-color);"><?php echo $job_id ? get_the_title($job_id) : 'Unknown Job'; ?></strong>
                                <span style="font-size: 0.8em; color: #94a3b8;"><?php echo $job_id ? get_post_meta($job_id, '_company_name', true) : ''; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="app-date" style="font-size: 0.9em; color: #64748b;">
                            <?php echo get_the_date('M d, Y'); ?>
                        </div>
                        <div class="app-status">
                            <?php if ($is_employer): ?>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <select class="v2-status-select" data-app-id="<?php echo $app_id; ?>" style="padding: 6px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85em;">
                                        <option value="Under Review" <?php selected($status, 'Under Review'); ?>>Under Review</option>
                                        <option value="Accepted" <?php selected($status, 'Accepted'); ?>>Accepted</option>
                                        <option value="Rejected" <?php selected($status, 'Rejected'); ?>>Rejected</option>
                                        <option value="Shortlisted" <?php selected($status, 'Shortlisted'); ?>>Shortlisted</option>
                                    </select>
                                    <button type="button" class="update-app-status-btn v2-btn-minimal" style="color: #10b981; font-size: 0.75em;">Update</button>
                                </div>
                            <?php else: ?>
                                <span class="status-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 0.75em; font-weight: 600; background: <?php echo ($status === 'Rejected' ? '#fee2e2' : ($status === 'Accepted' ? '#dcfce7' : '#fef9c3')); ?>; color: <?php echo ($status === 'Rejected' ? '#991b1b' : ($status === 'Accepted' ? '#166534' : '#854d0e')); ?>;">
                                    <?php echo esc_html($status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0;">
                <span class="dashicons dashicons-portfolio" style="font-size: 48px; width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 15px;"></span>
                <p style="color: #64748b;"><?php echo $is_employer ? 'No applications received yet.' : 'You haven\'t applied for any jobs yet.'; ?></p>
                <?php if (!$is_employer): ?>
                    <a href="<?php echo home_url('/job-search'); ?>" class="jobs-btn-small" style="margin-top: 15px; display: inline-block;">Browse Jobs</a>
                <?php endif; ?>
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

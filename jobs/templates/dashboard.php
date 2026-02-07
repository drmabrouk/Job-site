<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$role = $current_user->roles[0] ?? 'job_seeker';
?>
<div class="jobs-dashboard-wrapper">
    <aside class="jobs-dashboard-sidebar">
        <div class="jobs-dashboard-logo">
            <img src="<?php echo esc_url( get_option( 'jobs_site_logo' ) ); ?>" alt="Logo">
            <span>Jobs Portal</span>
        </div>
        <nav class="jobs-dashboard-nav">
            <ul id="jobs-dashboard-menu">
                <!-- Menu items will be injected or conditionally rendered here -->
                <?php if ( Jobs_Permission_Service::can_review_jobs() || $role === 'employer' ) : ?>
                    <li><a href="#job-requests" class="jobs-dash-tab" data-tab="job-requests">Job Requests</a></li>
                <?php endif; ?>

                <li><a href="#public-profile" class="jobs-dash-tab" data-tab="public-profile">Public Profile</a></li>

                <?php if ( $role === 'job_seeker' ) : ?>
                    <li><a href="#applications-submitted" class="jobs-dash-tab" data-tab="applications-submitted">Applications Submitted</a></li>
                <?php endif; ?>

                <?php if ( $role === 'employer' ) : ?>
                    <li><a href="#company-profile" class="jobs-dash-tab" data-tab="company-profile">Company Profile</a></li>
                <?php endif; ?>

                <?php if ( $role === 'employer' || Jobs_Permission_Service::is_admin() ) : ?>
                    <li><a href="#analytics-insights" class="jobs-dash-tab" data-tab="analytics-insights">Analytics / Insights</a></li>
                <?php endif; ?>

                <li><a href="#articles" class="jobs-dash-tab" data-tab="articles">Articles</a></li>
                <li><a href="#terms-conditions" class="jobs-dash-tab" data-tab="terms-conditions">Terms & Conditions</a></li>

                <?php if ( Jobs_Permission_Service::is_admin() ) : ?>
                    <li><a href="#advanced-settings" class="jobs-dash-tab" data-tab="advanced-settings">Advanced Settings</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <?php if ( Jobs_Permission_Service::is_admin() ) : ?>
        <div class="sidebar-footer" style="padding: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
            <a href="<?php echo admin_url( '?bypass_custom_admin=1' ); ?>" style="font-size: 0.8em; color: #999;">WP Admin Bypass</a>
        </div>
        <?php endif; ?>
    </aside>

    <main class="jobs-dashboard-main">
        <div id="jobs-dash-content-area">
            <!-- Content loaded via AJAX or hash change -->
            <div class="dash-loader">Loading your workspace...</div>
        </div>
    </main>
</div>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-admin-wrapper">
    <aside class="jobs-admin-sidebar">
        <div class="jobs-admin-logo">
            <img src="<?php echo esc_url( get_option( 'jobs_site_logo' ) ); ?>" alt="Logo">
            <span>Jobs Admin</span>
        </div>
        <nav class="jobs-admin-nav">
            <ul>
                <li><a href="#reports" class="jobs-admin-tab active" data-tab="reports">Reports & Analytics</a></li>
                <li><a href="#activity-log" class="jobs-admin-tab" data-tab="activity-log">Activity Logs</a></li>
                <li><a href="#user-management" class="jobs-admin-tab" data-tab="user-management">User Management</a></li>
                <li><a href="#verification" class="jobs-admin-tab" data-tab="verification">Account Verification</a></li>
                <li><a href="#articles" class="jobs-admin-tab" data-tab="articles">Articles Management</a></li>
                <li><a href="#design" class="jobs-admin-tab" data-tab="design">Design & Settings</a></li>
                <li><a href="#support" class="jobs-admin-tab" data-tab="support">Technical Support</a></li>
                <li><a href="#permissions" class="jobs-admin-tab" data-tab="permissions">Permissions & Roles</a></li>
                <li><a href="#ads" class="jobs-admin-tab" data-tab="ads">Ads & AdSense</a></li>
            </ul>
        </nav>
    </aside>

    <main class="jobs-admin-main">
        <div id="tab-reports" class="jobs-tab-content">
            <h2>Reports & Analytics</h2>
            <div class="reports-grid">
                <div class="report-card">Total Jobs: <?php echo wp_count_posts('job')->publish; ?></div>
                <div class="report-card">Total Users: <?php echo count_users()['total_users']; ?></div>
                <div class="report-card">Total Applications: 0</div>
                <div class="report-card">Total Messages: 0</div>
            </div>
            <div class="analytics-chart-placeholder" style="height: 200px; background: rgba(0,0,0,0.05); margin-top: 20px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <span>Analytics Chart Placeholder</span>
            </div>
        </div>

        <div id="tab-activity-log" class="jobs-tab-content" style="display:none;">
            <h2>Activity Logs</h2>
            <div class="log-section">
                <h3>General Activity</h3>
                <div class="log-table">No recent general activity recorded.</div>
            </div>
            <div class="log-section">
                <h3>Admin Activity</h3>
                <div class="log-table">No recent admin activity recorded.</div>
            </div>
        </div>

        <div id="tab-user-management" class="jobs-tab-content" style="display:none;">
            <h2>User Management</h2>
            <p>Direct management of Job Seekers, Employers, and Reviewers.</p>
            <div class="user-list-placeholder">
                <!-- User management UI -->
            </div>
        </div>

        <div id="tab-verification" class="jobs-tab-content" style="display:none;">
            <h2>Account Verification</h2>
            <p>Review and verify Employer and Job Seeker accounts.</p>
            <div class="verification-list">
                <p>No pending verification requests.</p>
            </div>
        </div>

        <div id="tab-articles" class="jobs-tab-content" style="display:none;">
            <h2>Published Articles Management</h2>
            <p>Manage professional articles and blog posts.</p>
        </div>

        <div id="tab-design" class="jobs-tab-content" style="display:none;">
            <h2>Design & General Settings</h2>
            <form method="POST" action="">
                <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>
                <div class="form-group">
                    <label>Site Logo URL</label>
                    <input type="text" name="jobs_site_logo" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Search Placeholder</label>
                    <input type="text" name="jobs_search_placeholder" value="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Job Archive Duration (Days)</label>
                    <input type="number" name="jobs_archive_days" value="<?php echo esc_attr( get_option( 'jobs_archive_days', 30 ) ); ?>" style="width:100%;">
                </div>
                <button type="submit" name="save_jobs_settings" class="jobs-btn">Save Changes</button>
            </form>
            <?php if ( isset( $_POST['save_jobs_settings'] ) ) echo '<p>Settings updated successfully!</p>'; ?>
        </div>

        <div id="tab-support" class="jobs-tab-content" style="display:none;">
            <h2>Technical Support</h2>
            <div class="support-inbox">
                <p>Internal support messaging system.</p>
            </div>
        </div>

        <div id="tab-permissions" class="jobs-tab-content" style="display:none;">
            <h2>Permissions & Roles Management</h2>
            <p>Fine-tune what Job Seekers, Employers, and Reviewers can access.</p>
        </div>

        <div id="tab-ads" class="jobs-tab-content" style="display:none;">
            <h2>External Ads & Google AdSense</h2>
            <div class="ads-config">
                <label>Google AdSense Code</label>
                <textarea style="width:100%; height:100px;"><?php echo esc_textarea( get_option( 'jobs_adsense_code' ) ); ?></textarea>
            </div>
        </div>
    </main>
</div>

<script>
document.querySelectorAll('.jobs-admin-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('data-tab');

        document.querySelectorAll('.jobs-admin-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.jobs-tab-content').forEach(content => {
            content.style.display = 'none';
        });
        document.getElementById('tab-' + target).style.display = 'block';
    });
});
</script>

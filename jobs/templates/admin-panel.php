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
                <li><a href="#reports" class="jobs-admin-tab active" data-tab="reports">Reports System</a></li>
                <li><a href="#activity-log" class="jobs-admin-tab" data-tab="activity-log">Activity Logs</a></li>
                <li><a href="#user-management" class="jobs-admin-tab" data-tab="user-management">User Management</a></li>
                <li><a href="#articles" class="jobs-admin-tab" data-tab="articles">Articles Management</a></li>
                <li><a href="#design" class="jobs-admin-tab" data-tab="design">Design Customization</a></li>
                <li><a href="#support" class="jobs-admin-tab" data-tab="support">Technical Support</a></li>
                <li><a href="#permissions" class="jobs-admin-tab" data-tab="permissions">Permissions & Roles</a></li>
                <li><a href="#ads" class="jobs-admin-tab" data-tab="ads">Ads & AdSense</a></li>
            </ul>
        </nav>
    </aside>

    <main class="jobs-admin-main">
        <div id="tab-reports" class="jobs-tab-content">
            <h2>Reports System</h2>
            <div class="reports-grid">
                <div class="report-card">Total Jobs: <?php echo wp_count_posts('job')->publish; ?></div>
                <div class="report-card">Total Users: <?php echo count_users()['total_users']; ?></div>
            </div>
        </div>

        <div id="tab-activity-log" class="jobs-tab-content" style="display:none;">
            <h2>Activity Logs</h2>
            <div class="log-section">
                <h3>General Activity</h3>
                <div class="log-table">No recent general activity.</div>
            </div>
            <div class="log-section">
                <h3>Admin Activity</h3>
                <div class="log-table">No recent admin activity.</div>
            </div>
        </div>

        <div id="tab-user-management" class="jobs-tab-content" style="display:none;">
            <h2>User Management</h2>
            <!-- User management UI would go here -->
            <p>List of all users and their roles.</p>
        </div>

        <div id="tab-articles" class="jobs-tab-content" style="display:none;">
            <h2>Published Articles Management</h2>
            <p>Manage and review professional articles.</p>
        </div>

        <div id="tab-design" class="jobs-tab-content" style="display:none;">
            <h2>Design Customization</h2>
            <form method="POST" action="">
                <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>
                <div class="form-group">
                    <label>Site Logo URL</label>
                    <input type="text" name="jobs_site_logo" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>">
                </div>
                <div class="form-group">
                    <label>Search Placeholder</label>
                    <input type="text" name="jobs_search_placeholder" value="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>">
                </div>
                <button type="submit" name="save_jobs_settings">Save Changes</button>
            </form>
            <?php if ( isset( $_POST['save_jobs_settings'] ) ) echo '<p>Settings updated!</p>'; ?>
        </div>

        <div id="tab-support" class="jobs-tab-content" style="display:none;">
            <h2>Technical Support</h2>
            <p>Support tickets and message history.</p>
        </div>

        <div id="tab-permissions" class="jobs-tab-content" style="display:none;">
            <h2>Permissions & Roles Management</h2>
            <p>Manage what each role can do.</p>
        </div>

        <div id="tab-ads" class="jobs-tab-content" style="display:none;">
            <h2>External Ads & Google AdSense</h2>
            <p>Configure AdSense snippets and ad banners.</p>
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

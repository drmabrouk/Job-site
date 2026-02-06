<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-admin-panel jobs-transparent-bg">
    <h1>Jobs Admin Control Panel</h1>
    <nav class="jobs-admin-nav">
        <ul>
            <li><a href="#reports" class="jobs-admin-tab" data-tab="reports">Reports System</a></li>
            <li><a href="#activity-log" class="jobs-admin-tab" data-tab="activity-log">Activity Logs</a></li>
            <li><a href="#user-management" class="jobs-admin-tab" data-tab="user-management">User Management</a></li>
            <li><a href="#articles" class="jobs-admin-tab" data-tab="articles">Articles Management</a></li>
            <li><a href="#design" class="jobs-admin-tab" data-tab="design">Design Customization</a></li>
            <li><a href="#support" class="jobs-admin-tab" data-tab="support">Technical Support</a></li>
            <li><a href="#permissions" class="jobs-admin-tab" data-tab="permissions">Permissions & Roles</a></li>
            <li><a href="#ads" class="jobs-admin-tab" data-tab="ads">Ads & AdSense</a></li>
        </ul>
    </nav>

    <div class="jobs-admin-content" id="jobs-admin-display">
        <div id="tab-reports" class="jobs-tab-content">
            <h2>Reports System</h2>
            <p>Overview of system performance and job applications.</p>
        </div>
        <div id="tab-activity-log" class="jobs-tab-content" style="display:none;">
            <h2>Activity Logs</h2>
            <h3>General Activity</h3>
            <div class="log-container">Loading general activity...</div>
            <h3>Admin Activity</h3>
            <div class="log-container">Loading admin activity...</div>
        </div>
        <div id="tab-user-management" class="jobs-tab-content" style="display:none;">
            <h2>User Management</h2>
            <p>Manage Job Seekers, Employers, and Reviewers.</p>
        </div>
        <div id="tab-articles" class="jobs-tab-content" style="display:none;">
            <h2>Published Articles Management</h2>
            <p>Review and edit professional articles.</p>
        </div>
        <div id="tab-design" class="jobs-tab-content" style="display:none;">
            <h2>Design Customization</h2>
            <p>Adjust colors, fonts, and buttons.</p>
            <form method="POST" action="">
                <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>
                <label for="jobs_site_logo">Site Logo URL:</label>
                <input type="text" name="jobs_site_logo" id="jobs_site_logo" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>" style="width:100%;">
                <br><br>
                <label for="jobs_search_placeholder">Search Placeholder:</label>
                <input type="text" name="jobs_search_placeholder" id="jobs_search_placeholder" value="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" style="width:100%;">
                <br><br>
                <button type="submit" name="save_jobs_settings">Save Settings</button>
            </form>
            <?php
            if ( isset( $_POST['save_jobs_settings'] ) ) {
                echo '<p>Settings updated!</p>';
            }
            ?>
        </div>
        <div id="tab-support" class="jobs-tab-content" style="display:none;">
            <h2>Technical Support</h2>
            <p>Handle support tickets and messages.</p>
        </div>
        <div id="tab-permissions" class="jobs-tab-content" style="display:none;">
            <h2>Permissions & Roles Management</h2>
            <p>Fine-tune access levels for each role.</p>
        </div>
        <div id="tab-ads" class="jobs-tab-content" style="display:none;">
            <h2>External Ads & Google AdSense</h2>
            <p>Manage advertisement placements and integration.</p>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.jobs-admin-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('data-tab');
        document.querySelectorAll('.jobs-tab-content').forEach(content => {
            content.style.display = 'none';
        });
        document.getElementById('tab-' + target).style.display = 'block';
    });
});
</script>

<style>
.jobs-admin-nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.jobs-admin-nav a {
    text-decoration: none;
    padding: 10px;
    border: 1px solid var(--jobs-primary-color);
    color: var(--jobs-primary-color);
}
.jobs-tab-content {
    margin-top: 20px;
}
</style>

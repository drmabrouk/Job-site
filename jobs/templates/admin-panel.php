<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-admin-top-actions" style="position: absolute; top: 20px; right: 20px; z-index: 100;">
    <a href="<?php echo admin_url( '?bypass_custom_admin=1' ); ?>" class="jobs-btn-small" style="background: #555;">Switch to WordPress Admin</a>
</div>

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
            <table class="jobs-admin-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $logs = Jobs_Activity_Service::get_recent_logs();
                    if ( $logs ) : foreach ( $logs as $log ) : ?>
                    <tr>
                        <td><?php echo $log->time; ?></td>
                        <td><?php echo get_userdata($log->user_id)->display_name ?? 'System'; ?></td>
                        <td><span class="status-badge" style="background:#f0f0f0;"><?php echo esc_html($log->type); ?></span></td>
                        <td><?php echo esc_html($log->message); ?></td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="4">No logs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div id="tab-user-management" class="jobs-tab-content" style="display:none;">
            <h2>User Management</h2>
            <table class="jobs-admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = get_users( array( 'role__in' => array( 'job_seeker', 'employer', 'reviewer' ) ) );
                    foreach ( $users as $u ) : ?>
                    <tr>
                        <td><?php echo $u->display_name; ?></td>
                        <td><?php echo ucfirst( str_replace('_', ' ', $u->roles[0]) ); ?></td>
                        <td><?php echo $u->user_email; ?></td>
                        <td><span class="status-badge active">Active</span></td>
                        <td>
                            <button class="jobs-btn-small" style="background:#555;">Edit</button>
                            <button class="jobs-btn-small" style="background:#d32f2f;">Suspend</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                <div class="form-row" style="display:flex; gap:10px;">
                    <div class="form-group" style="flex:1;">
                        <label>Logo Width (px)</label>
                        <input type="number" name="jobs_logo_width" value="<?php echo esc_attr( get_option( 'jobs_logo_width', '300' ) ); ?>" style="width:100%;">
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Logo Height (e.g. auto or px)</label>
                        <input type="text" name="jobs_logo_height" value="<?php echo esc_attr( get_option( 'jobs_logo_height', 'auto' ) ); ?>" style="width:100%;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Search Placeholder</label>
                    <input type="text" name="jobs_search_placeholder" value="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Job Archive Duration (Days)</label>
                    <input type="number" name="jobs_archive_days" value="<?php echo esc_attr( get_option( 'jobs_archive_days', 30 ) ); ?>" style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Visible Modules (Global Control)</label>
                    <?php
                    $all_modules = array(
                        'job-posting' => 'Job Posting',
                        'job-listings-history' => 'Job Listings History',
                        'public-profile' => 'Public Profile',
                        'applications-submitted' => 'Applications Submitted',
                        'job-requests' => 'Job Requests',
                        'cv-resume' => 'CV / Resume',
                        'company-profile' => 'Company Profile',
                        'favorites' => 'Favorites',
                        'drafts' => 'Drafts',
                        'support' => 'Support',
                        'settings' => 'Settings',
                        'articles' => 'Articles',
                    );
                    $visible_modules = get_option( 'jobs_visible_modules', array_keys( $all_modules ) );
                    foreach ( $all_modules as $slug => $label ) : ?>
                        <div style="margin-bottom: 5px;">
                            <input type="checkbox" name="jobs_visible_modules[]" value="<?php echo $slug; ?>" <?php checked( in_array( $slug, $visible_modules ) ); ?>>
                            <?php echo $label; ?>
                        </div>
                    <?php endforeach; ?>
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
            <form method="POST" action="">
                <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>
                <div class="ads-config">
                    <label>Google AdSense Code</label>
                    <textarea name="jobs_adsense_code" style="width:100%; height:100px;"><?php echo esc_textarea( get_option( 'jobs_adsense_code' ) ); ?></textarea>
                </div>
                <button type="submit" name="save_jobs_settings" class="jobs-btn" style="margin-top:10px;">Save Ads Settings</button>
            </form>
        </div>
    </main>
</div>

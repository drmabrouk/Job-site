<?php
/**
 * Module: Platform Control Center (Advanced Sidebar Design)
 * Reusing V4 design philosophy (Centered 1280px, Sidebar Layout).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! Jobs_Permission_Service::is_system_admin() ) {
    echo '<script>window.location.href="'.home_url().'";</script>';
    exit;
}

$current_user = wp_get_current_user();
$location_data = Jobs_Data_Service::get_location_data();
?>

<div class="jobs-premium-profile-v4 admin-control-center">
    <div class="profile-layout-container">

        <!-- HEADER: Mirrored from Profile -->
        <header class="profile-v4-header no-avatar">
            <div class="profile-v4-identity-box">
                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <h1 style="display: inline-flex; align-items: center; gap: 10px; margin: 0;">Platform Control Center <span class="badge-verified-circle" title="System Authority" style="background:#10b981;"><span class="dashicons dashicons-shield"></span></span></h1>
                </div>
                <div style="margin: 10px 0; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span class="v4-pastel-pill pill-blue">System Administration</span>
                    <span class="v4-pastel-pill pill-green">Active Session</span>
                </div>
                <p class="profile-v4-headline">Global authority panel for <?php echo get_bloginfo('name'); ?>. Manage settings, users, and infrastructure.</p>
            </div>
            <div class="profile-v4-actions">
                <div class="v4-action-group">
                    <button type="submit" form="jobs-site-settings-form" name="save_site_settings" class="v4-btn-primary"><span class="dashicons dashicons-saved"></span> Commit All Changes</button>
                </div>
            </div>
        </header>

        <div class="profile-v4-grid">

            <!-- SIDEBAR: NAVIGATION MENU -->
            <div class="profile-v4-sidebar">
                <section class="v4-card nav-card" style="padding: 15px 0;">
                    <div class="admin-sidebar-nav">
                        <a href="#" class="admin-nav-item active" data-tab="branding"><span class="dashicons dashicons-format-image"></span> Branding & Identity</a>
                        <a href="#" class="admin-nav-item" data-tab="appearance"><span class="dashicons dashicons-admin-appearance"></span> Visual Theme</a>
                        <a href="#" class="admin-nav-item" data-tab="locations"><span class="dashicons dashicons-location-alt"></span> Location Management</a>
                        <a href="#" class="admin-nav-item" data-tab="users"><span class="dashicons dashicons-groups"></span> User Management</a>
                        <a href="#" class="admin-nav-item" data-tab="system"><span class="dashicons dashicons-admin-generic"></span> System & Infrastructure</a>
                        <a href="#" class="admin-nav-item" data-tab="seo"><span class="dashicons dashicons-google"></span> SEO & Indexing</a>
                        <a href="#" class="admin-nav-item" data-tab="backups"><span class="dashicons dashicons-backup"></span> Portability & Backups</a>
                        <a href="#" class="admin-nav-item" data-tab="activity"><span class="dashicons dashicons-list-view"></span> Integrity Logs</a>
                        <a href="#" class="admin-nav-item" data-tab="security"><span class="dashicons dashicons-lock"></span> Platform Security</a>
                    </div>
                </section>

                <section class="v4-card" style="background: #f8fafc; border: 1px dashed #cbd5e1; text-align: center;">
                    <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Quick Stats</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div style="padding: 15px; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 18px; font-weight: 800; color: #1d3469;"><?php echo count_users()['total_users']; ?></div>
                            <div style="font-size: 9px; color: #94a3b8;">Users</div>
                        </div>
                        <div style="padding: 15px; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 18px; font-weight: 800; color: #1d3469;"><?php echo wp_count_posts('job')->publish; ?></div>
                            <div style="font-size: 9px; color: #94a3b8;">Live Jobs</div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- MAIN COLUMN: TAB PANELS -->
            <div class="profile-v4-main">
                <form id="jobs-site-settings-form" method="POST">
                    <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>

                    <!-- Branding Panel -->
                    <div id="branding-panel" class="tab-panel active">
                        <section class="v4-card">
                            <h3 class="v4-card-title">Branding & Identity</h3>
                            <div class="v4-card-body">
                                <div class="form-group-v4">
                                    <label>Site Official Name</label>
                                    <input type="text" name="blogname" value="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
                                </div>
                                <div class="form-group-v4">
                                    <label>Platform Bio / Description</label>
                                    <textarea name="blogdescription" style="height: 100px;"><?php echo esc_textarea( get_option( 'blogdescription' ) ); ?></textarea>
                                </div>
                                <div class="form-group-v4">
                                    <label>Global Logo Asset URL</label>
                                    <div style="display: flex; gap: 12px;">
                                        <input type="text" name="jobs_site_logo" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>" style="flex: 1;">
                                        <button type="button" class="v4-btn-secondary" style="height: 48px; padding: 0 20px;">Asset Library</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Appearance Panel -->
                    <div id="appearance-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">Visual Identity Theme</h3>
                            <div class="v4-card-body">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                    <div class="form-group-v4">
                                        <label>Primary Brand Color</label>
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            <input type="color" name="jobs_primary_color" value="<?php echo esc_attr( get_option( 'jobs_primary_color', '#1d3469' ) ); ?>" style="width: 60px; height: 48px; padding: 4px;">
                                            <input type="text" value="<?php echo esc_attr( get_option( 'jobs_primary_color', '#1d3469' ) ); ?>" readonly style="flex:1;">
                                        </div>
                                    </div>
                                    <div class="form-group-v4">
                                        <label>Secondary Accent Color</label>
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            <input type="color" name="jobs_secondary_color" value="<?php echo esc_attr( get_option( 'jobs_secondary_color', '#64748b' ) ); ?>" style="width: 60px; height: 48px; padding: 4px;">
                                            <input type="text" value="<?php echo esc_attr( get_option( 'jobs_secondary_color', '#64748b' ) ); ?>" readonly style="flex:1;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group-v4" style="margin-top: 24px;">
                                    <label>Typography Hierarchy</label>
                                    <select name="jobs_font_family">
                                        <option value="Rubik" <?php selected(get_option('jobs_font_family'), 'Rubik'); ?>>Rubik (Corporate Standard)</option>
                                        <option value="Inter" <?php selected(get_option('jobs_font_family'), 'Inter'); ?>>Inter (Modern UI)</option>
                                        <option value="Roboto" <?php selected(get_option('jobs_font_family'), 'Roboto'); ?>>Roboto</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Locations Management Panel -->
                    <div id="locations-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">Location Management System</h3>
                            <div class="v4-card-body">
                                <p style="font-size: 13px; color: #64748b; margin-bottom: 25px;">Manage countries, flags, phone codes, and primary states/provinces globally across the platform.</p>

                                <div id="location-groups-wrapper">
                                    <?php foreach ($location_data as $group_key => $group) : ?>
                                        <div class="location-group-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                                <h4 style="margin: 0; color: #1d3469; font-size: 15px; font-weight: 700;"><?php echo esc_html($group['label']); ?></h4>
                                            </div>

                                            <div class="countries-list" style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                                                <?php foreach ($group['countries'] as $slug => $c) : ?>
                                                    <div class="country-admin-row" style="background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px;">
                                                        <div style="display: flex; align-items: center; gap: 15px;">
                                                            <img src="https://flagcdn.com/w40/<?php echo $c['code']; ?>.png" style="width: 24px; border-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                                            <div style="flex: 1;">
                                                                <div style="font-weight: 700; font-size: 14px;"><?php echo esc_html($c['name']); ?></div>
                                                                <div style="font-size: 11px; color: #94a3b8;">Code: <?php echo strtoupper($c['code']); ?> | Phone: <?php echo $c['phone']; ?></div>
                                                            </div>
                                                            <button type="button" class="v4-btn-secondary edit-regions-btn" data-slug="<?php echo $slug; ?>" style="height: 32px; padding: 0 12px; font-size: 11px;">Manage States (<?php echo count($c['regions']); ?>)</button>
                                                        </div>
                                                        <div class="regions-edit-box" id="regions-<?php echo $slug; ?>" style="display:none; margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                                                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Official States / Provinces (Comma separated)</label>
                                                            <textarea class="region-input" data-slug="<?php echo $slug; ?>" data-group="<?php echo $group_key; ?>" style="width: 100%; height: 80px; margin-top: 8px; font-size: 13px;"><?php echo esc_textarea(implode(', ', $c['regions'])); ?></textarea>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Users Panel -->
                    <div id="users-panel" class="tab-panel">
                        <section class="v4-card" style="padding: 0; overflow: hidden;">
                            <div style="padding: 30px; border-bottom: 1px solid #f1f5f9;">
                                <h3 class="v4-card-title" style="margin: 0;">Identity Management</h3>
                            </div>
                            <div class="v4-table-responsive">
                                <table class="admin-v4-table">
                                    <thead>
                                        <tr>
                                            <th>Professional Identity</th>
                                            <th>Role Asset</th>
                                            <th>Last Pulse</th>
                                            <th>Management</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $users = get_users( array( 'number' => 20, 'orderby' => 'registered', 'order' => 'DESC' ) );
                                        foreach ( $users as $u ) :
                                            $last_active = get_user_meta( $u->ID, '_last_activity', true );
                                            $role = !empty($u->roles) ? $u->roles[0] : 'None';
                                            ?>
                                            <tr>
                                                <td>
                                                    <div style="display: flex; align-items: center; gap: 12px;">
                                                        <img src="<?php echo get_avatar_url($u->ID); ?>" style="width: 32px; height: 32px; border-radius: 50%;">
                                                        <div>
                                                            <div style="font-weight: 700; font-size: 13px;"><?php echo esc_html($u->display_name); ?></div>
                                                            <div style="font-size: 11px; color: #94a3b8;"><?php echo esc_html($u->user_email); ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="v4-pastel-pill" style="height: 24px; font-size: 10px;"><?php echo ucwords(str_replace('_', ' ', $role)); ?></span></td>
                                                <td style="font-size: 12px; color: #64748b;"><?php echo $last_active ? human_time_diff($last_active, time()).' ago' : 'N/A'; ?></td>
                                                <td>
                                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                                        <button type="button" class="v4-icon-btn delete-user-btn" data-user-id="<?php echo $u->ID; ?>" style="width: 32px; height: 32px; min-width: 32px; border-color: #fee2e2; color: #ef4444;"><span class="dashicons dashicons-trash"></span></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <!-- Other panels remain structured similarly but hidden for brevity in this block -->
                    <div id="system-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">System Infrastructure</h3>
                            <div class="v4-card-body">
                                <div class="form-group-v4">
                                    <label>Administrative Contact Email</label>
                                    <input type="email" name="admin_email" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
                                </div>
                                <div style="margin-top: 24px; padding: 20px; background: #eff6ff; border-radius: 12px; border: 1px solid #dbeafe;">
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <input type="checkbox" name="jobs_enable_notifs" value="1" <?php checked(get_option('jobs_enable_notifs', 1), 1); ?> style="width: 18px; height: 18px;">
                                        <span style="font-weight: 700; color: #1e40af; font-size: 14px;">Dynamic Pulse Notifications</span>
                                    </label>
                                    <p style="margin: 8px 0 0 30px; font-size: 12px; color: #60a5fa;">Allow the platform to process and push real-time alerts to active users.</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div id="seo-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">SEO & Global Indexing</h3>
                            <div class="v4-card-body">
                                <div class="form-group-v4">
                                    <label>XML Sitemap Engine</label>
                                    <div style="display: flex; gap: 12px; align-items: center; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                                        <span class="dashicons dashicons-rss" style="color: #64748b;"></span>
                                        <span style="flex: 1; font-family: monospace; font-size: 12px;"><?php echo home_url('/jobs-sitemap.xml'); ?></span>
                                        <button type="button" id="jobs-regenerate-sitemap" class="v4-btn-secondary" style="height: 36px; padding: 0 16px; font-size: 12px;">Sync Index</button>
                                    </div>
                                </div>
                                <div class="form-group-v4" style="margin-top: 24px;">
                                    <label>Global SEO Meta Description</label>
                                    <textarea name="jobs_seo_description" style="height: 80px;"><?php echo esc_textarea( get_option( 'jobs_seo_description', get_bloginfo('description') ) ); ?></textarea>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div id="backups-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">Portability & Backups</h3>
                            <div class="v4-card-body">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                    <div style="background: #f8fafc; padding: 24px; border-radius: 20px; border: 1px solid #e2e8f0; text-align: center;">
                                        <div style="width: 48px; height: 48px; background: #e0f2f1; color: #00796b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                            <span class="dashicons dashicons-download"></span>
                                        </div>
                                        <h4 style="margin: 0 0 8px; font-size: 14px;">Snapshot Export</h4>
                                        <p style="font-size: 11px; color: #64748b; margin-bottom: 20px;">Download complete platform configuration.</p>
                                        <button type="button" id="jobs-export-settings" class="v4-btn-primary" style="height: 40px; width: 100%; font-size: 12px;">Generate JSON</button>
                                    </div>
                                    <div style="background: #f8fafc; padding: 24px; border-radius: 20px; border: 1px solid #e2e8f0; text-align: center;">
                                        <div style="width: 48px; height: 48px; background: #f3e5f5; color: #7b1fa2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                            <span class="dashicons dashicons-upload"></span>
                                        </div>
                                        <h4 style="margin: 0 0 8px; font-size: 14px;">Snapshot Restore</h4>
                                        <p style="font-size: 11px; color: #64748b; margin-bottom: 20px;">Import settings from a valid snapshot file.</p>
                                        <input type="file" id="jobs-import-file" style="display: none;">
                                        <button type="button" onclick="document.getElementById('jobs-import-file').click()" class="v4-btn-secondary" style="height: 40px; width: 100%; font-size: 12px;">Upload Snapshot</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div id="activity-panel" class="tab-panel">
                        <section class="v4-card" style="padding: 0; overflow: hidden;">
                             <div style="padding: 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="v4-card-title" style="margin: 0;">Global Integrity Logs</h3>
                                <button type="button" class="v4-btn-secondary" style="height: 32px; padding: 0 12px; font-size: 11px;">Purge Logs</button>
                            </div>
                            <div style="max-height: 500px; overflow-y: auto; background: #0f172a; color: #94a3b8; padding: 25px; font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.6;">
                                <?php
                                $logs = Jobs_Activity_Service::get_recent_logs( 50 );
                                if ($logs) {
                                    foreach ( $logs as $log ) {
                                        $user = get_userdata($log->user_id);
                                        $uname = $user ? $user->user_login : 'System';
                                        echo '<div style="margin-bottom:12px; border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:8px;">';
                                        echo '<span style="color:#60a5fa;">[' . $log->time . ']</span> ';
                                        echo '<strong style="color:#34d399;">' . strtoupper($uname) . ':</strong> ';
                                        echo esc_html($log->message);
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p style="color:#475569; text-align:center;">STATION LOGS EMPTY // NO ANOMALIES DETECTED</p>';
                                }
                                ?>
                            </div>
                        </section>
                    </div>

                    <div id="security-panel" class="tab-panel">
                        <section class="v4-card">
                            <h3 class="v4-card-title">Platform Security</h3>
                            <div class="v4-card-body">
                                <div class="form-group-v4">
                                    <label>Administrative Credential Rotation</label>
                                    <input type="password" name="new_admin_pass" placeholder="Enter new authority password">
                                    <p style="font-size: 11px; color: #ef4444; margin-top: 8px;">Security Protocol: Changing this will terminate your current session.</p>
                                </div>
                                <div style="margin-top: 32px; padding: 24px; background: #fff1f2; border-radius: 16px; border: 1px solid #fecdd3;">
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <input type="checkbox" name="jobs_maintenance_mode" value="1" <?php checked(get_option('jobs_maintenance_mode', 0), 1); ?> style="width: 18px; height: 18px;">
                                        <span style="font-weight: 800; color: #9f1239; font-size: 14px;">ENGAGE MAINTENANCE PROTOCOL</span>
                                    </label>
                                    <p style="margin: 8px 0 0 30px; font-size: 12px; color: #e11d48;">Restrict all frontend access to System Administrators only.</p>
                                </div>
                            </div>
                        </section>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Sidebar Navigation logic
    $('.admin-nav-item').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');

        $('.admin-nav-item').removeClass('active');
        $(this).addClass('active');

        $('.tab-panel').removeClass('active');
        $('#' + tab + '-panel').addClass('active');
    });

    // Location Management - Edit Regions
    $('.edit-regions-btn').on('click', function() {
        var slug = $(this).data('slug');
        $('#regions-' + slug).slideToggle(300);
    });

    // Handle User Role Change & Deletion (mirrored from old version but polished)
    $('.delete-user-btn').on('click', function() {
        if (!confirm('Critical Alert: Permanent deletion of user record requested. Proceed?')) return;
        var userId = $(this).data('user-id');
        var $row = $(this).closest('tr');
        $.post(jobs_vars.ajax_url, { action: 'jobs_delete_user', user_id: userId, nonce: jobs_vars.nonce }, function(res) {
            if (res.success) $row.fadeOut();
        });
    });

    // Location data auto-sync on form submission
    $('#jobs-site-settings-form').on('submit', function(e) {
        // Collect location data
        var locationData = <?php echo json_encode($location_data); ?>;
        $('.region-input').each(function() {
            var slug = $(this).data('slug');
            var group = $(this).data('group');
            var regions = $(this).val().split(',').map(s => s.trim()).filter(s => s !== "");
            locationData[group].countries[slug].regions = regions;
        });

        // Save location data separately via AJAX before main form POST or just include in POST if handled
        // Actually we added an AJAX handler for it, let's use it.
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_save_locations',
            location_data: locationData,
            nonce: '<?php echo wp_create_nonce("jobs_save_settings"); ?>'
        });
    });
});
</script>

<style>
.admin-control-center {
    background: #f8fafc !important;
    min-height: 100vh;
}

.admin-control-center .profile-v4-grid {
    grid-template-columns: 320px 1fr;
}

.admin-sidebar-nav {
    display: flex;
    flex-direction: column;
}

.admin-nav-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px 25px;
    text-decoration: none;
    color: #64748b;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}

.admin-nav-item .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
    color: #94a3b8;
}

.admin-nav-item:hover {
    background: #f1f5f9;
    color: #1d3469;
}

.admin-nav-item.active {
    background: #eff6ff;
    color: #1d3469;
    border-left-color: #1d3469;
}

.admin-nav-item.active .dashicons {
    color: #1d3469;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
    animation: fadeIn 0.4s ease;
}

.form-group-v4 {
    margin-bottom: 24px;
}

.form-group-v4 label {
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: #1d3469;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 10px;
}

.form-group-v4 input[type="text"],
.form-group-v4 input[type="email"],
.form-group-v4 input[type="password"],
.form-group-v4 select,
.form-group-v4 textarea {
    width: 100%;
    padding: 14px 18px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    font-family: inherit;
    font-size: 14px;
    color: #1e293b;
    transition: all 0.3s ease;
}

.form-group-v4 input:focus, .form-group-v4 textarea:focus {
    border-color: #1d3469;
    box-shadow: 0 0 0 4px rgba(29, 52, 105, 0.05);
    outline: none;
}

.admin-v4-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-v4-table th {
    background: #f8fafc;
    padding: 18px 25px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #f1f5f9;
}

.admin-v4-table td {
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
}

.v4-table-responsive {
    overflow-x: auto;
}

@media (max-width: 960px) {
    .profile-v4-grid {
        grid-template-columns: 1fr;
    }
}
</style>

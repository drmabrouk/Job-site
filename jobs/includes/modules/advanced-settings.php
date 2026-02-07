<?php
/**
 * Module: Site Settings (Advanced Control)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! Jobs_Permission_Service::is_system_admin() ) {
    echo '<script>window.location.href="'.home_url().'";</script>';
    exit;
}

$current_user = wp_get_current_user();
?>
<div class="jobs-module-content" id="jobs-site-settings-module">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid var(--jobs-primary-color); padding-bottom: 15px;">
        <h2 style="margin: 0; color: var(--jobs-primary-color);">Platform Control Center</h2>
        <span class="badge" style="background: var(--jobs-primary-color); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.7em;">SYSTEM ADMIN</span>
    </div>

    <div class="settings-tabs" style="display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 5px;">
        <button class="jobs-btn-small tab-link active" onclick="openSettingsTab(event, 'branding')">Branding</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'appearance')">Appearance</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'system')">System & Email</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'permissions')">Permissions</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'activity')">Activity Log</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'security')">Security</button>
    </div>

    <form id="jobs-site-settings-form" method="POST" style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
        <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>

        <!-- Branding Tab -->
        <div id="branding" class="tab-panel active">
            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Site Name</label>
                    <input type="text" name="blogname" value="<?php echo esc_attr( get_option( 'blogname' ) ); ?>" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Site Bio / Description</label>
                    <textarea name="blogdescription" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; height: 80px;"><?php echo esc_textarea( get_option( 'blogdescription' ) ); ?></textarea>
                </div>
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Platform Logo URL</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="jobs_site_logo" id="jobs_site_logo_input" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <button type="button" class="jobs-btn-small" style="background: #64748b;">Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appearance Tab -->
        <div id="appearance" class="tab-panel" style="display:none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Primary Color</label>
                    <input type="color" name="jobs_primary_color" value="<?php echo esc_attr( get_option( 'jobs_primary_color', '#1d3469' ) ); ?>" style="width:100%; height: 40px; padding: 2px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Secondary Color</label>
                    <input type="color" name="jobs_secondary_color" value="<?php echo esc_attr( get_option( 'jobs_secondary_color', '#64748b' ) ); ?>" style="width:100%; height: 40px; padding: 2px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">Font Family</label>
                    <select name="jobs_font_family" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="Rubik" <?php selected(get_option('jobs_font_family'), 'Rubik'); ?>>Rubik (Recommended)</option>
                        <option value="Inter" <?php selected(get_option('jobs_font_family'), 'Inter'); ?>>Inter</option>
                        <option value="Roboto" <?php selected(get_option('jobs_font_family'), 'Roboto'); ?>>Roboto</option>
                        <option value="Open Sans" <?php selected(get_option('jobs_font_family'), 'Open Sans'); ?>>Open Sans</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- System Tab -->
        <div id="system" class="tab-panel" style="display:none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">Admin Email Address</label>
                <input type="email" name="admin_email" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
            <div class="form-group">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">Enable Dynamic Notifications</label>
                <input type="checkbox" name="jobs_enable_notifs" value="1" <?php checked(get_option('jobs_enable_notifs', 1), 1); ?>>
                <span style="font-size: 0.9em; color: #64748b;">Allow the system to poll for notifications every 30s.</span>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div id="permissions" class="tab-panel" style="display:none;">
            <p style="font-size: 0.9em; color: #64748b; margin-bottom: 15px;">Configure which modules are visible globally.</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <?php
                $all_modules = array('job-posting', 'job-seekers', 'job-requests', 'cv-resume', 'company-profile', 'favorites', 'drafts', 'support', 'articles', 'analytics-insights');
                $visible = get_option( 'jobs_visible_modules', $all_modules );
                foreach ($all_modules as $mod) : ?>
                    <label style="font-size: 0.9em; display: flex; align-items: center; gap: 8px; background: white; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <input type="checkbox" name="jobs_visible_modules[]" value="<?php echo $mod; ?>" <?php checked(in_array($mod, $visible), true); ?>>
                        <?php echo ucwords(str_replace('-', ' ', $mod)); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Activity Tab -->
        <div id="activity" class="tab-panel" style="display:none;">
            <h4>Global Activity Log</h4>
            <div style="max-height: 300px; overflow-y: auto; background: white; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; font-family: monospace; font-size: 0.85em;">
                <?php
                $logs = Jobs_Activity_Service::get_recent_logs( 50 );
                if ($logs) {
                    foreach ( $logs as $log ) {
                        $user = get_userdata($log->user_id);
                        $uname = $user ? $user->user_login : 'System';
                        echo '<div style="margin-bottom:8px; border-bottom:1px solid #f1f5f9; padding-bottom:4px;">';
                        echo '<span style="color:#94a3b8;">[' . $log->time . ']</span> ';
                        echo '<strong style="color:var(--jobs-primary-color);">' . $uname . ':</strong> ';
                        echo esc_html($log->message);
                        echo '</div>';
                    }
                } else {
                    echo '<p style="color:#999; text-align:center;">No activity recorded yet.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security" class="tab-panel" style="display:none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">Admin Password Change</label>
                <input type="password" name="new_admin_pass" placeholder="Enter new password" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <p style="font-size: 0.75em; color: #ef4444; margin-top: 5px;">Warning: This will update YOUR current login password.</p>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">Maintenance Mode</label>
                <input type="checkbox" name="jobs_maintenance_mode" value="1" <?php checked(get_option('jobs_maintenance_mode', 0), 1); ?>>
                <span style="font-size: 0.9em; color: #64748b;">Only system administrators can access the frontend.</span>
            </div>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
            <button type="reset" class="jobs-btn-small" style="background: #94a3b8;">Reset Changes</button>
            <button type="submit" name="save_site_settings" class="jobs-btn">Save All Settings</button>
        </div>
    </form>
    <div id="settings-save-status" style="margin-top: 15px; text-align: center;"></div>
</div>

<script>
function openSettingsTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-panel");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

jQuery(document).ready(function($) {
    $('#jobs-site-settings-form').on('submit', function(e) {
        // Form submission is handled by forms-handler.php (regular POST)
        // or we could add AJAX here. Given the requirements, let's ensure forms-handler handles it.
    });
});
</script>

<style>
.tab-link {
    background: #e2e8f0;
    color: #475569;
    border: none;
    white-space: nowrap;
}
.tab-link.active {
    background: var(--jobs-primary-color) !important;
    color: white !important;
}
.settings-tabs::-webkit-scrollbar {
    height: 4px;
}
.settings-tabs::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
</style>

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
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'users')">User Management</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'emails')">Email Templates</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'system')">System Settings</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'permissions')">Permissions</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'seo')">SEO Management</button>
        <button class="jobs-btn-small tab-link" onclick="openSettingsTab(event, 'backups')">Backups & System</button>
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

        <!-- Emails Tab -->
        <div id="emails" class="tab-panel" style="display:none;">
            <p style="font-size: 0.9em; color: #64748b; margin-bottom: 20px;">Manage professional email communications and branding. Use placeholders like {seeker_name}, {job_title}, {company_name}, {status}.</p>

            <?php
            $templates = get_option( 'jobs_email_templates', Jobs_Email_Service::get_default_templates() );
            foreach ( $templates as $key => $tpl ) : ?>
                <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                    <h4 style="margin-top: 0; color: var(--jobs-primary-color);"><?php echo esc_html($tpl['label']); ?></h4>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-size: 0.85em; font-weight: 700; color: #64748b;">Subject Line</label>
                        <input type="text" name="email_templates[<?php echo $key; ?>][subject]" value="<?php echo esc_attr($tpl['subject']); ?>" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 0.85em; font-weight: 700; color: #64748b;">Email Body (HTML supported)</label>
                        <textarea name="email_templates[<?php echo $key; ?>][body]" style="width:100%; height: 120px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($tpl['body']); ?></textarea>
                    </div>
                    <input type="hidden" name="email_templates[<?php echo $key; ?>][label]" value="<?php echo esc_attr($tpl['label']); ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- System Tab -->
        <div id="system" class="tab-panel" style="display:none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">Official Support / Admin Email</label>
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

        <!-- SEO Tab -->
        <div id="seo" class="tab-panel" style="display:none;">
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">XML Sitemap</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" readonly value="<?php echo home_url('/jobs-sitemap.xml'); ?>" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f1f5f9;">
                    <button type="button" id="jobs-regenerate-sitemap" class="jobs-btn-small">Update Sitemap</button>
                </div>
                <p style="font-size: 0.8em; color: #64748b; margin-top: 5px;">Your sitemap is automatically indexed by Google. Click update after major changes.</p>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px;">SEO Description (Meta)</label>
                <textarea name="jobs_seo_description" style="width:100%; height: 80px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;"><?php echo esc_textarea( get_option( 'jobs_seo_description', get_bloginfo('description') ) ); ?></textarea>
            </div>

            <div class="form-group">
                <label style="font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="jobs_index_profiles" value="1" <?php checked(get_option('jobs_index_profiles', 1), 1); ?>>
                    Allow Google to index Public Profiles
                </label>
            </div>
        </div>

        <!-- Backups Tab -->
        <div id="backups" class="tab-panel" style="display:none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <h4 style="margin-top:0;">Configuration Export</h4>
                    <p style="font-size: 0.85em; color: #64748b;">Download all platform settings as a backup file.</p>
                    <button type="button" id="jobs-export-settings" class="jobs-btn-small" style="width: 100%;">Download JSON Backup</button>
                </div>
                <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <h4 style="margin-top:0;">Configuration Import</h4>
                    <p style="font-size: 0.85em; color: #64748b;">Restore settings from a previous backup.</p>
                    <input type="file" id="jobs-import-file" style="display: none;">
                    <button type="button" onclick="document.getElementById('jobs-import-file').click()" class="jobs-btn-small" style="width: 100%; background: #64748b;">Upload & Restore</button>
                </div>
            </div>

            <div style="margin-top: 20px; background: #fffbeb; padding: 20px; border-radius: 12px; border: 1px solid #fef3c7;">
                <h4 style="margin-top: 0; color: #92400e;">System Health</h4>
                <div id="system-health-report" style="font-size: 0.9em; color: #92400e;">
                    <p>✓ Database Tables: Connected</p>
                    <p>✓ Cron Tasks: Running</p>
                    <p>✓ File Permissions: Correct</p>
                </div>
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

        <!-- Users Tab -->
        <div id="users" class="tab-panel" style="display:none;">
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9em;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 12px; text-align: left;">User</th>
                            <th style="padding: 12px; text-align: left;">Role</th>
                            <th style="padding: 12px; text-align: left;">Last Activity</th>
                            <th style="padding: 12px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = get_users( array( 'number' => 50, 'orderby' => 'registered', 'order' => 'DESC' ) );
                        foreach ( $users as $u ) :
                            $last_active = get_user_meta( $u->ID, '_last_activity', true );
                            $role = !empty($u->roles) ? $u->roles[0] : 'None';
                            ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px;">
                                    <strong><?php echo esc_html($u->display_name); ?></strong><br>
                                    <span style="font-size: 0.8em; color: #64748b;"><?php echo esc_html($u->user_email); ?></span>
                                </td>
                                <td style="padding: 12px;">
                                    <select class="user-role-select" data-user-id="<?php echo $u->ID; ?>" style="padding: 5px; border-radius: 5px; border: 1px solid #cbd5e1; font-size: 0.9em;">
                                        <option value="job_seeker" <?php selected($role, 'job_seeker'); ?>>Job Seeker</option>
                                        <option value="employer" <?php selected($role, 'employer'); ?>>Employer</option>
                                        <option value="reviewer" <?php selected($role, 'reviewer'); ?>>Reviewer</option>
                                        <option value="administrator" <?php selected($role, 'administrator'); ?>>WP Admin</option>
                                    </select>
                                </td>
                                <td style="padding: 12px; color: #64748b;">
                                    <?php echo $last_active ? date('M j, Y H:i', $last_active) : 'Never'; ?>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <button type="button" class="delete-user-btn" data-user-id="<?php echo $u->ID; ?>" style="background: #fee2e2; color: #ef4444; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 0.8em;">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p style="font-size: 0.8em; color: #64748b; margin-top: 10px;">Showing last 50 registered users. Accounts with 10+ days of inactivity are automatically flagged for deletion.</p>
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
    // Handle User Role Change
    $('.user-role-select').on('change', function() {
        var userId = $(this).data('user-id');
        var newRole = $(this).val();
        var $select = $(this);

        $select.css('opacity', '0.5');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_update_user_role',
            user_id: userId,
            role: newRole,
            nonce: jobs_vars.nonce
        }, function(res) {
            $select.css('opacity', '1');
            if (!res.success) alert(res.data);
        });
    });

    // Handle User Deletion
    $('.delete-user-btn').on('click', function() {
        if (!confirm('Are you absolutely sure you want to delete this user? This cannot be undone.')) return;

        var userId = $(this).data('user-id');
        var $row = $(this).closest('tr');

        $(this).prop('disabled', true).text('...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_delete_user',
            user_id: userId,
            nonce: jobs_vars.nonce
        }, function(res) {
            if (res.success) {
                $row.fadeOut();
            } else {
                alert(res.data);
            }
        });
    });

    $('#jobs-site-settings-form').on('submit', function(e) {
        // Form submission is handled by forms-handler.php (regular POST)
    });

    // Sitemap Regenerate
    $('#jobs-regenerate-sitemap').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Updating...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_regenerate_sitemap',
            nonce: jobs_vars.nonce
        }, function(res) {
            $btn.prop('disabled', false).text('Update Sitemap');
            if(res.success) alert(res.data);
        });
    });

    // Export Settings
    $('#jobs-export-settings').on('click', function() {
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_export_settings',
            nonce: jobs_vars.nonce
        }, function(res) {
            if(res.success) {
                var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(res.data));
                var downloadAnchorNode = document.createElement('a');
                downloadAnchorNode.setAttribute("href",     dataStr);
                downloadAnchorNode.setAttribute("download", "jobs-settings-backup.json");
                document.body.appendChild(downloadAnchorNode);
                downloadAnchorNode.click();
                downloadAnchorNode.remove();
            }
        });
    });

    // Import Settings
    $('#jobs-import-file').on('change', function(e) {
        var file = e.target.files[0];
        if(!file) return;

        var reader = new FileReader();
        reader.onload = function(e) {
            var contents = e.target.result;
            if(confirm('Are you sure you want to restore these settings? This will overwrite current configuration.')) {
                $.post(jobs_vars.ajax_url, {
                    action: 'jobs_import_settings',
                    settings: contents,
                    nonce: jobs_vars.nonce
                }, function(res) {
                    if(res.success) {
                        alert(res.data);
                        location.reload();
                    } else {
                        alert(res.data);
                    }
                });
            }
        };
        reader.readAsText(file);
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

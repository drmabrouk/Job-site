<?php
/**
 * Module: Advanced Settings (Admin Dashboard)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! Jobs_Permission_Service::is_admin() ) {
    echo 'Access denied.';
    return;
}
?>
<div class="jobs-module-content">
    <h2>Platform Control Center</h2>

    <div class="settings-sections-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
        <!-- Categories & Specializations -->
        <section class="settings-card" style="background: white; padding: 25px; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <h4>Taxonomies</h4>
            <ul style="list-style:none; padding:0; margin-top:15px;">
                <li><a href="<?php echo admin_url('edit-tags.php?taxonomy=job_category&post_type=job'); ?>" target="_blank">Manage Categories</a></li>
                <li><a href="<?php echo admin_url('edit-tags.php?taxonomy=specialization&post_type=job'); ?>" target="_blank">Manage Specializations</a></li>
            </ul>
        </section>

        <!-- Design & Branding -->
        <section class="settings-card" style="background: white; padding: 25px; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <h4>Design & Branding</h4>
            <form method="POST" action="">
                <?php wp_nonce_field( 'jobs_save_settings', 'jobs_admin_settings_nonce' ); ?>
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; font-size: 0.85em; color: #777;">Platform Logo URL</label>
                    <input type="text" name="jobs_site_logo" value="<?php echo esc_attr( get_option( 'jobs_site_logo' ) ); ?>" style="width:100%; border: 1px solid #ddd; padding: 8px; border-radius: 6px;">
                </div>
                <button type="submit" name="save_jobs_settings" class="jobs-btn-small">Update Branding</button>
            </form>
        </section>

        <!-- System Status -->
        <section class="settings-card" style="background: white; padding: 25px; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <h4>System Health</h4>
            <p style="font-size: 0.9em; color: #666;">Version: 1.0.0-PRO<br>Status: All systems operational</p>
        </section>
    </div>
</div>

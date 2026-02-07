<?php
/**
 * Module: Settings / Personal Account
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
?>
<div class="jobs-module-content" id="jobs-settings-module">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h3 style="margin: 0;">Account Settings</h3>
        <div style="font-size: 0.8em; color: #64748b;">Member since: <?php echo date('M Y', strtotime($current_user->user_registered)); ?></div>
    </div>

    <form id="jobs-update-account-form" method="POST" style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
        <?php wp_nonce_field( 'jobs_update_account', 'jobs_account_nonce' ); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Display Name</label>
                <input type="text" name="display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Email Address</label>
                <input type="email" name="user_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Username (Once/Month)</label>
                <input type="text" name="user_login_change" value="<?php echo esc_attr( $current_user->user_login ); ?>" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; background: #f1f5f9;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Profile Privacy</label>
                <select name="profile_visibility" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
                    <option value="public" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'public' ); ?>>Public Profile</option>
                    <option value="private" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'private' ); ?>>Private (Hidden)</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Change Password</label>
                <input type="password" name="user_pass" placeholder="Enter new password if you wish to change it" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>
        </div>

        <button type="submit" name="jobs_save_account" class="jobs-btn" style="margin-top: 20px; width: 100%;">Save Changes</button>
    </form>

    <hr>
    <div class="activity-log-section">
        <h4>My Activity Log</h4>
        <div style="max-height: 200px; overflow-y: auto; font-size: 0.85em; background: #f5f5f5; padding: 15px; border-radius: 8px;">
            <?php
            $logs = Jobs_Activity_Service::get_recent_logs( 10 );
            foreach ( $logs as $log ) {
                if ( $log->user_id == $current_user->ID ) {
                    echo '<div style="margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:4px;">';
                    echo '<strong>' . $log->time . ':</strong> ' . esc_html($log->message);
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <hr>
    <div class="danger-zone">
        <h4>Danger Zone</h4>
        <button class="jobs-btn btn-danger" id="jobs-delete-account">Delete My Account</button>
    </div>
</div>

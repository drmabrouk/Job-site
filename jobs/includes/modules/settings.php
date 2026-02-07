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
    <h3>Update Personal Account</h3>
    <form id="jobs-update-account-form" method="POST">
        <?php wp_nonce_field( 'jobs_update_account', 'jobs_account_nonce' ); ?>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="user_email" value="<?php echo esc_attr( $current_user->user_email ); ?>">
        </div>

        <div class="form-group">
            <label>Display Name</label>
            <input type="text" name="display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>">
        </div>

        <div class="form-group">
            <label>Username (Changeable once per month)</label>
            <input type="text" name="user_login_change" value="<?php echo esc_attr( $current_user->user_login ); ?>">
        </div>

        <div class="form-group">
            <label>Public Profile Visibility</label>
            <select name="profile_visibility">
                <option value="public" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'public' ); ?>>Public</option>
                <option value="private" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'private' ); ?>>Private</option>
            </select>
        </div>

        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="user_pass">
        </div>

        <button type="submit" name="jobs_save_account" class="jobs-btn">Update Account</button>
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

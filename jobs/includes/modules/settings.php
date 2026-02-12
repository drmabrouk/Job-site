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
                <input type="text" name="first_name" value="<?php echo esc_attr( get_user_meta($current_user->ID, 'first_name', true) ); ?>" placeholder="First Name" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <input type="text" name="last_name" value="<?php echo esc_attr( get_user_meta($current_user->ID, 'last_name', true) ); ?>" placeholder="Last Name" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <input type="email" name="user_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" placeholder="Email Address" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <input type="text" name="user_login_change" value="<?php echo esc_attr( $current_user->user_login ); ?>" placeholder="Username" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; background: #f1f5f9;">
                <small style="font-size: 0.7em; color: #999;">Changeable once per month</small>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <select name="profile_visibility" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
                    <option value="public" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'public' ); ?>>Public Profile Visibility</option>
                    <option value="private" <?php selected( get_user_meta( $current_user->ID, 'profile_visibility', true ), 'private' ); ?>>Private (Hidden from directory)</option>
                </select>
            </div>

            <div class="form-group">
                <input type="password" name="user_pass" placeholder="New Password" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <input type="password" name="user_pass_confirm" placeholder="Confirm New Password" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>
        </div>

        <button type="submit" name="jobs_save_account" class="jobs-btn" style="margin-top: 20px; width: 100%;">Save Changes</button>
        <div id="jobs-settings-status" style="margin-top:10px; text-align:center;"></div>
    </form>

    <div class="danger-zone" style="margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
        <a href="#" id="jobs-delete-account" style="color: #94a3b8; font-size: 0.85em; text-decoration: underline; transition: color 0.2s;">Delete my account permanently</a>
    </div>
</div>

<style>
#jobs-delete-account:hover { color: #ef4444; }
</style>

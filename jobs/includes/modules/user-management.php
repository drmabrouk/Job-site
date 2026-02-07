<?php
/**
 * Module: User Management (For Admins)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'system_admin' ) ) {
    echo '<p>Access denied.</p>';
    return;
}

$users = get_users();
?>
<div class="jobs-module-content" id="jobs-users-module">
    <h3>User Management</h3>
    <table class="jobs-table" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid var(--jobs-primary-color);">
                <th style="text-align:left; padding: 10px;">Username</th>
                <th style="text-align:left; padding: 10px;">Role</th>
                <th style="text-align:left; padding: 10px;">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $users as $user ) : ?>
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <td style="padding: 10px;"><?php echo esc_html($user->user_login); ?></td>
                    <td style="padding: 10px;"><?php echo implode(', ', $user->roles); ?></td>
                    <td style="padding: 10px;"><?php echo esc_html($user->user_email); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

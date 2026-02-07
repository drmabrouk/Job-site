<?php
/**
 * Module: Notifications (Modal)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
global $wpdb;
$table = Jobs_DB_Service::get_table( 'notifications' );
$notifs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %d ORDER BY timestamp DESC LIMIT 10", $user_id ) );

// Mark all as read when opened
$wpdb->update( $table, array( 'is_read' => 1 ), array( 'user_id' => $user_id ) );
?>
<div class="jobs-module-content">
    <h3>Recent Notifications</h3>
    <div class="notifications-list" style="margin-top: 20px;">
        <?php if ( $notifs ) : foreach ( $notifs as $n ) : ?>
            <div class="notification-item" style="padding: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; gap: 15px; align-items: flex-start;">
                <div class="notif-icon" style="color: var(--jobs-primary-color); margin-top: 3px;">
                    <span class="dashicons dashicons-bell"></span>
                </div>
                <div class="notif-body">
                    <p style="margin: 0; font-size: 0.95em;"><?php echo esc_html( $n->content ); ?></p>
                    <span style="font-size: 0.8em; color: #999;"><?php echo human_time_diff( strtotime($n->timestamp), current_time('timestamp') ); ?> ago</span>
                </div>
            </div>
        <?php endforeach; else : ?>
            <p style="text-align: center; color: #999; padding: 40px;">No new alerts.</p>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * Module: Support (Integrated Messaging)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
global $wpdb;
$table = Jobs_DB_Service::get_table( 'messages' );

// Get messages where user is sender or receiver (communicating with admin/system)
$messages = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE sender_id = %d OR receiver_id = %d ORDER BY timestamp ASC", $user_id, $user_id ) );
?>
<div class="jobs-module-content" id="jobs-support-module">
    <h3>Technical Support</h3>
    <p>Message our administrators for assistance.</p>

    <div class="support-chat-box" style="height: 300px; overflow-y: auto; background: #f9f9f9; border-radius: 12px; padding: 20px; margin-top: 20px; border: 1px solid rgba(0,0,0,0.05);">
        <?php if ( $messages ) : foreach ( $messages as $m ) :
            $is_me = ($m->sender_id == $user_id);
        ?>
            <div class="chat-message <?php echo $is_me ? 'me' : 'them'; ?>" style="margin-bottom: 15px; text-align: <?php echo $is_me ? 'right' : 'left'; ?>;">
                <div class="msg-bubble" style="display: inline-block; padding: 10px 15px; border-radius: 15px; background: <?php echo $is_me ? 'var(--jobs-primary-color)' : '#eee'; ?>; color: <?php echo $is_me ? 'white' : '#333'; ?>; max-width: 80%;">
                    <?php echo esc_html($m->message); ?>
                </div>
                <div style="font-size: 0.7em; color: #999; margin-top: 4px;">
                    <?php echo date('H:i', strtotime($m->timestamp)); ?>
                </div>
            </div>
        <?php endforeach; else : ?>
            <p style="text-align: center; color: #999; padding-top: 100px;">No messages yet. Start a conversation below.</p>
        <?php endif; ?>
    </div>

    <form id="jobs-support-form" style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
        <?php wp_nonce_field( 'jobs_messaging_nonce', 'nonce' ); ?>
        <input type="hidden" name="receiver_id" value="1">

        <div style="display:flex; gap: 10px;">
            <select name="issue_type" required style="border: 1px solid #ddd; border-radius: 25px; padding: 10px 20px; background: white; font-family: 'Rubik', sans-serif; color: #666; font-size: 0.9em;">
                <option value="">Select Issue Type</option>
                <option value="technical">Technical Problem</option>
                <option value="account">Account Access</option>
                <option value="billing">Billing/Payments</option>
                <option value="report">Report Content</option>
                <option value="other">Other Inquiry</option>
            </select>
            <input type="text" name="message" placeholder="Describe your issue..." required style="flex: 1; border: 1px solid #ddd; border-radius: 25px; padding: 10px 20px;">
            <button type="submit" class="jobs-btn" style="border-radius: 50%; width: 45px; height: 45px; padding: 0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <span class="dashicons dashicons-paper-plane"></span>
        </button>
    </form>
</div>

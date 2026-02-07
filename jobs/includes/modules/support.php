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
    <div style="margin-bottom: 25px;">
        <h3 style="margin: 0;">Help & Support</h3>
        <p style="font-size: 0.9em; color: #64748b;">Direct communication with our administration team.</p>
    </div>

    <div class="support-chat-box" style="height: 350px; overflow-y: auto; background: #f8fafc; border-radius: 16px; padding: 25px; border: 1px solid #e2e8f0;">
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

    <form id="jobs-support-form" style="margin-top: 25px; background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 15px;">
        <?php wp_nonce_field( 'jobs_messaging_nonce', 'nonce' ); ?>
        <input type="hidden" name="receiver_id" value="1">

        <div style="display:grid; grid-template-columns: 1fr 2fr; gap: 10px;">
            <select name="issue_type" required style="border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 15px; background: #f8fafc; font-family: 'Rubik', sans-serif; color: #334155; font-size: 0.9em;">
                <option value="">Select Issue Category</option>
                <option value="technical">Technical Support</option>
                <option value="account">Account & Security</option>
                <option value="billing">Payments & Billing</option>
                <option value="feedback">Feedback & Suggestions</option>
                <option value="other">General Inquiry</option>
            </select>
            <input type="text" name="message" placeholder="How can we help you today?" required style="flex: 1; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 15px;">
        </div>

        <button type="submit" class="jobs-btn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span class="dashicons dashicons-paper-plane"></span>
            Send Message to Support
        </button>
    </form>
</div>

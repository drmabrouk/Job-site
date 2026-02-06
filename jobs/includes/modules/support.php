<?php
/**
 * Module: Support (Internal Messaging)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
global $wpdb;
$table = $wpdb->prefix . 'jobs_messages';
$messages = $wpdb->get_results( $wpdb->prepare(
    "SELECT * FROM $table WHERE receiver_id = %d OR sender_id = %d ORDER BY timestamp DESC LIMIT 20",
    $current_user_id, $current_user_id
) );
?>
<div class="jobs-module-content" id="jobs-support-module">
    <h3>Technical Support & Messages</h3>

    <div class="jobs-messaging-interface">
        <div class="jobs-message-list" style="max-height: 300px; overflow-y: auto; border: 1px solid rgba(0,0,0,0.1); padding: 10px; margin-bottom: 20px;">
            <?php if ( $messages ) : foreach ( $messages as $msg ) : ?>
                <div class="jobs-message <?php echo ($msg->sender_id == $current_user_id) ? 'sent' : 'received'; ?>" style="margin-bottom: 10px; padding: 10px; border-radius: 8px; background: <?php echo ($msg->sender_id == $current_user_id) ? 'rgba(29, 52, 105, 0.1)' : 'rgba(0,0,0,0.05)'; ?>;">
                    <strong><?php echo ($msg->sender_id == $current_user_id) ? 'Me' : get_userdata($msg->sender_id)->display_name; ?>:</strong>
                    <p style="margin: 5px 0;"><?php echo esc_html($msg->message); ?></p>
                    <small style="font-size: 0.8em; opacity: 0.6;"><?php echo $msg->timestamp; ?></small>
                </div>
            <?php endforeach; else : ?>
                <p>No messages yet.</p>
            <?php endif; ?>
        </div>

        <form id="jobs-send-support-form">
            <?php wp_nonce_field( 'jobs_messaging_nonce', 'messaging_nonce' ); ?>
            <textarea name="message" id="jobs-support-msg-text" placeholder="Type your message to administration..." style="width:100%; height: 80px; margin-bottom: 10px; background: transparent; border: 1px solid var(--jobs-primary-color);"></textarea>
            <input type="hidden" name="receiver_id" value="1"> <!-- Assuming Admin ID is 1 for support -->
            <button type="button" id="jobs-send-msg-btn" class="jobs-btn">Send Message</button>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#jobs-send-msg-btn').on('click', function() {
        var msg = $('#jobs-support-msg-text').val();
        if(!msg) return;

        var data = {
            action: 'jobs_send_message',
            nonce: $('#messaging_nonce').val(),
            message: msg,
            receiver_id: $('input[name="receiver_id"]').val()
        };

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                alert('Message sent successfully!');
                location.reload(); // Simple refresh to show new message
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});
</script>

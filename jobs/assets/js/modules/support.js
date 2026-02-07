(function($) {
    $(document).ready(function() {
        $(document).on('submit', '#jobs-support-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var data = $form.serialize() + '&action=jobs_send_message';

            $.post(jobs_vars.ajax_url, data, function(response) {
                if(response.success) {
                    var msg = $form.find('input[name="message"]').val();
                    var type = $form.find('select[name="issue_type"] option:selected').text();
                    var time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    var displayMsg = '[' + type + '] ' + msg;
                    var newHtml = '<div class="chat-message me" style="margin-bottom: 15px; text-align: right;">' +
                                  '<div class="msg-bubble" style="display: inline-block; padding: 10px 15px; border-radius: 15px; background: var(--jobs-primary-color); color: white; max-width: 80%;">' + displayMsg + '</div>' +
                                  '<div style="font-size: 0.7em; color: #999; margin-top: 4px;">' + time + '</div>' +
                                  '</div>';
                    $('.support-chat-box').append(newHtml).scrollTop($('.support-chat-box')[0].scrollHeight);
                    $form[0].reset();
                } else {
                    alert('Error: ' + response.data);
                }
            });
        });
    });
})(jQuery);

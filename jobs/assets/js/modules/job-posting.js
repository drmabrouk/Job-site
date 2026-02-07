jQuery(document).ready(function($) {
    $(document).on('submit', '#jobs-post-job-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $msg = $form.find('.jobs-module-message');
        var formData = $form.serialize();

        $form.css('opacity', '0.5');

        $.post(jobs_vars.ajax_url, formData + '&action=jobs_post_job_handler', function(response) {
            $form.css('opacity', '1');
            if (response.success) {
                $msg.html('<p style="color: green; background: #e6ffed; padding: 10px; border-radius: 5px; margin-bottom: 20px;">' + response.data + '</p>');
                if ($form.find('[name="is_draft"]').val() !== '1') {
                    $form[0].reset();
                }
            } else {
                $msg.html('<p style="color: red; background: #fff1f0; padding: 10px; border-radius: 5px; margin-bottom: 20px;">' + response.data + '</p>');
            }

            // Scroll to message
            $('.jobs-module-container').animate({ scrollTop: 0 }, 'slow');
        });
    });

    // Handle draft saving
    $(document).on('click', '.jobs-save-draft-btn', function() {
        $('#is_draft').val('1');
        $('#jobs-post-job-form').submit();
        // Reset is_draft after submit
        setTimeout(function() {
            $('#is_draft').val('0');
        }, 500);
    });
});

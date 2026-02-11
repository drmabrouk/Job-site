(function($) {
    $(document).on('click', '.approve-job-btn', function() {
        var btn = $(this);
        var jobId = btn.data('job-id');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_approve_job',
            job_id: jobId,
            nonce: jobs_vars.nonce // Ensure nonce matches or localized specifically
        }, function(response) {
            if(response.success) {
                btn.closest('.job-review-card').fadeOut();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });

    $(document).on('click', '.reject-job-btn', function() {
        var btn = $(this);
        var id = btn.data('job-id');
        if(!confirm('Reject and delete this job listing?')) return;

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_delete_job',
            job_id: id,
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                btn.closest('.job-review-card').fadeOut();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });

    $(document).on('click', '.update-app-status-btn', function() {
        var $btn = $(this);
        var $select = $btn.siblings('.v2-status-select');
        var appId = $select.data('app-id');
        var status = $select.val();

        $btn.prop('disabled', true).text('...');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_update_app_status',
            app_id: appId,
            status: status,
            nonce: jobs_vars.nonce
        }, function(response) {
            $btn.prop('disabled', false).text('Update');
            if(response.success) {
                alert('Status updated successfully!');
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
})(jQuery);

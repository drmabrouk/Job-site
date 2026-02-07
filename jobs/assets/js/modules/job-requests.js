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
})(jQuery);

jQuery(document).ready(function($) {
    function updateSeekersList() {
        var formData = $('#jobs-seekers-filter-form').serialize();
        formData += '&action=jobs_filter_seekers';

        $('#jobs-seekers-results').css('opacity', '0.5');

        $.post(jobs_vars.ajax_url, formData, function(response) {
            $('#jobs-seekers-results').html(response).css('opacity', '1');
        });
    }

    $('#jobs-filter-seekers-btn').on('click', function(e) {
        e.preventDefault();
        updateSeekersList();
    });

    // Initial load
    updateSeekersList();

    // Handle Direct Job Offer
    $(document).on('click', '.send-offer-btn', function() {
        var seekerId = $(this).data('seeker-id');
        var seekerName = $(this).data('seeker-name');
        var message = prompt("Enter your job offer message for " + seekerName + ":");

        if (message) {
            $.post(jobs_vars.ajax_url, {
                action: 'jobs_send_job_offer',
                seeker_id: seekerId,
                message: message,
                nonce: jobs_vars.nonce
            }, function(response) {
                if (response.success) {
                    alert(response.data);
                } else {
                    alert("Error: " + response.data);
                }
            });
        }
    });
});

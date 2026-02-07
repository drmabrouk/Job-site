function toggleCvStep(step) {
    jQuery('.cv-step-content').slideUp();
    jQuery('#cv-step-' + step).slideDown();
}

(function($) {
    $(document).on('submit', '#jobs-cv-form', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_cv_handler';
        $('#jobs-cv-status').html('<p>Saving updates...</p>');
        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $('#jobs-cv-status').html('<p style="color: green;">' + response.data + '</p>');
            } else {
                $('#jobs-cv-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    $(document).on('click', '#jobs-generate-pdf', function() {
        // Find profile link from the UI
        var profileUrl = $('.jobs-share-link-box a').attr('href');
        if (profileUrl) {
            window.open(profileUrl + '?format=pdf', '_blank');
        }
    });
})(jQuery);

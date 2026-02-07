window.toggleCvStep = function(step) {
    var $content = jQuery('#cv-step-' + step);
    var $item = $content.closest('.cv-step-item');

    if ($content.is(':visible')) {
        $content.slideUp();
        $item.removeClass('active');
    } else {
        jQuery('.cv-step-content').slideUp();
        jQuery('.cv-step-item').removeClass('active');
        $content.slideDown();
        $item.addClass('active');
    }
};

(function($) {
    $(document).ready(function() {
        $(document).on('submit', '#jobs-cv-form', function(e) {
            e.preventDefault();
            var data = $(this).serialize() + '&action=jobs_save_cv_handler';

            var $status = $('#jobs-cv-status');
            $status.html('<p style="color: #666;">Saving your CV data...</p>');

            $.post(jobs_vars.ajax_url, data, function(response) {
                if(response.success) {
                    $status.html('<p style="color: #2e7d32; font-weight: 500;">✓ CV updated successfully!</p>');
                    setTimeout(function() { $status.fadeOut(); }, 3000);
                } else {
                    $status.html('<p style="color: #d32f2f;">Error: ' + response.data + '</p>');
                }
            });
        });

        $(document).on('click', '#jobs-generate-pdf', function() {
            var profileUrl = $('.jobs-share-link-box a').attr('href');
            if (profileUrl) {
                window.open(profileUrl + '?format=pdf', '_blank');
            } else {
                alert('Please update your profile first.');
            }
        });
    });
})(jQuery);

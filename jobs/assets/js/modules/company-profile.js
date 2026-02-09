(function($) {
    $(document).on('submit', '#jobs-company-form', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_company_handler';
        var $status = $('#jobs-company-status');
        $status.html('<p style="color: #666; font-weight: 600;">Updating corporate identity...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $status.html('<div style="background:#dcfce7; color:#166534; padding:20px; border-radius:12px; font-weight:600;">✓ Corporate profile updated! Your public presence has been refreshed.</div>');
                if (typeof profileLink !== 'undefined') {
                    setTimeout(function() { window.location.href = profileLink; }, 2000);
                }
            } else {
                $status.html('<p style="color: #ef4444; font-weight: 600;">Error: ' + response.data + '</p>');
            }
        });
    });
})(jQuery);

(function($) {
    // Photo Preview
    $(document).on('change', '#cv-photo-input', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cv-photo-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $(document).on('submit', '#jobs-company-form', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'jobs_save_company_handler');
        formData.append('nonce', jobs_vars.nonce);

        var $status = $('#jobs-company-status');
        $status.html('<p style="color: #666; font-weight: 600;">Updating corporate identity...</p>');

        $.ajax({
            url: jobs_vars.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    $status.html('<div style="background:#dcfce7; color:#166534; padding:20px; border-radius:12px; font-weight:600;">✓ Corporate profile updated! Your public presence has been refreshed.</div>');
                    if (typeof profileLink !== 'undefined') {
                        setTimeout(function() { window.location.href = profileLink; }, 2000);
                    }
                } else {
                    $status.html('<p style="color: #ef4444; font-weight: 600;">Error: ' + response.data + '</p>');
                }
            }
        });
    });
})(jQuery);

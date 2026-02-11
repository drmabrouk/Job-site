(function($) {
    // Immediate Photo Upload & Preview
    $(document).on('change', '#cv-photo-input', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cv-photo-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);

            // Immediate AJAX persistence
            var formData = new FormData();
            formData.append('action', 'jobs_save_company_handler');
            formData.append('profile_photo', file);
            formData.append('nonce', $('input[name="jobs_company_nonce"]').val());

            var $status = $('#jobs-company-status');
            $status.html('<p style="color: #1d3469; font-weight: 600;">Syncing brand asset...</p>').show();

            $.ajax({
                url: jobs_vars.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        $status.html('<p style="color: #059669; font-weight: 700;">✓ Brand identity updated globally.</p>');
                        setTimeout(function() { $status.fadeOut(); }, 3000);
                    }
                }
            });
        }
    });

    $(document).on('submit', '#jobs-company-form', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'jobs_save_company_handler');
        // Use the specific nonce from the form field
        formData.append('nonce', $('input[name="jobs_company_nonce"]').val());

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
                    if (typeof window.profileLink !== 'undefined') {
                        setTimeout(function() { window.location.href = window.profileLink; }, 2000);
                    }
                } else {
                    $status.html('<p style="color: #ef4444; font-weight: 600;">Error: ' + response.data + '</p>');
                }
            }
        });
    });
})(jQuery);

(function($) {
    $(document).ready(function() {
        $(document).on('submit', '#jobs-update-account-form', function(e) {
            e.preventDefault();
            var $status = $('#jobs-settings-status');
            $status.html('<p style="color: #666;">Saving changes...</p>');

            var data = $(this).serialize() + '&jobs_save_account=1';

            $.post(window.location.href, data, function() {
                $status.html('<p style="color: green; font-weight: 500;">✓ Account updated successfully!</p>');
                setTimeout(function() { $status.fadeOut(); }, 3000);
            });
        });

        $(document).on('click', '#jobs-delete-account', function() {
            if (confirm('Are you absolutely sure? This will permanently delete your account and all associated data. This action cannot be undone.')) {
                // Submit a hidden form or use AJAX to trigger deletion
                var form = $('<form method="POST"></form>');
                form.append('<input type="hidden" name="jobs_delete_account" value="1">');
                form.append($('#jobs-update-account-form [name="jobs_account_nonce"]').clone());
                $('body').append(form);
                form.submit();
            }
        });
    });
})(jQuery);

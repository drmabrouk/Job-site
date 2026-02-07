(function($) {
    $(document).on('submit', '#jobs-company-form', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_company_handler';
        $('#jobs-company-status').html('<p>Saving company details...</p>');
        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $('#jobs-company-status').html('<p style="color: green;">' + response.data + '</p>');
            } else {
                $('#jobs-company-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });
})(jQuery);

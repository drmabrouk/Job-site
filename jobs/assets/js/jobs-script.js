jQuery(document).ready(function($) {
    // Toggle dropdown menu
    $('#jobs-avatar-toggle').on('click', function(e) {
        e.stopPropagation();
        $('#jobs-dropdown').toggleClass('active');
    });

    $(document).on('click', function() {
        $('#jobs-dropdown').removeClass('active');
    });

    $('#jobs-dropdown').on('click', function(e) {
        e.stopPropagation();
    });

    // Module link clicks
    $('.jobs-module-link').on('click', function(e) {
        var module = $(this).data('module');

        if (module === 'advanced-settings') {
            window.location.href = '/jobs-admin-panel';
            return;
        }

        e.preventDefault();
        console.log('Loading module: ' + module);

        // Actual implementation: Load module content via AJAX
        var data = {
            action: 'jobs_load_module',
            module: module,
            nonce: jobs_vars.nonce
        };

        $('#jobs-module-overlay').fadeIn();
        $('#jobs-module-container').html('<p>Loading...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $('#jobs-module-container').html(response.data);
            } else {
                $('#jobs-module-container').html('<p style="color:red;">Error loading module: ' + response.data + '</p>');
            }
        });
    });

    $(document).on('click', '#jobs-close-module', function() {
        $('#jobs-module-overlay').fadeOut();
    });

    // Job Card Scripts
    $(document).on('click', '.quick-apply-toggle', function() {
        var jobId = $(this).data('job-id');
        $('#quick-apply-' + jobId).slideToggle();
    });

    $(document).on('click', '.submit-quick-apply', function() {
        var form = $(this).closest('form');
        var container = form.closest('.quick-apply-form-container');
        var data = form.serialize() + '&action=jobs_quick_apply';

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                container.html('<p style="color: green;">Application submitted successfully!</p>');
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});

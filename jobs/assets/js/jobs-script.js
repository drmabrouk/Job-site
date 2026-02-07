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
    $(document).on('click', '.jobs-module-link', function(e) {
        var module = $(this).data('module');

        if (module === 'advanced-settings') {
            window.location.href = '/jobs-admin-panel';
            return;
        }

        e.preventDefault();

        // Close dropdown
        $('#jobs-dropdown').removeClass('active');

        // Show overlay with fade effect
        if ($('#jobs-module-overlay').is(':hidden')) {
            $('#jobs-module-overlay').fadeIn(300);
        }

        $('#jobs-module-container').css('opacity', '0.5');

        var data = {
            action: 'jobs_load_module',
            module: module,
            nonce: jobs_vars.nonce
        };

        $.post(jobs_vars.ajax_url, data, function(response) {
            $('#jobs-module-container').css('opacity', '1');
            if(response.success) {
                $('#jobs-module-container').html(response.data);
                // Trigger any re-init if needed
            } else {
                $('#jobs-module-container').html('<p style="color:red; padding:20px;">Error loading module: ' + response.data + '</p>');
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

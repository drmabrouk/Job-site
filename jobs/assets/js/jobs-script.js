jQuery(document).ready(function($) {
    // Toggles for Top Bar
    $('#jobs-apps-toggle').on('click', function(e) {
        e.stopPropagation();
        $('#jobs-apps-menu').toggleClass('active');
        $('#jobs-profile-menu').removeClass('active');
        $('body').css('overflow', $('#jobs-apps-menu').hasClass('active') ? 'hidden' : '');
    });

    $(document).on('click', '#jobs-apps-close', function() {
        $('#jobs-apps-menu').removeClass('active');
        $('body').css('overflow', '');
    });

    $('#jobs-profile-toggle').on('click', function(e) {
        e.stopPropagation();
        $('#jobs-profile-menu').toggleClass('active');
        $('#jobs-apps-menu').removeClass('active');
    });

    $(document).on('click', function() {
        $('#jobs-apps-menu, #jobs-profile-menu').removeClass('active');
    });

    $('#jobs-apps-menu, #jobs-profile-menu').on('click', function(e) {
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

        // Open central modal instead of card slideToggle
        $('#jobs-module-overlay').fadeIn();
        $('#jobs-module-container').html('<p>Loading application form...</p>');

        // Fetch application form via AJAX
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_quick_apply_form',
            job_id: jobId,
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                $('#jobs-module-container').html(response.data);
            } else {
                $('#jobs-module-container').html('<p style="color:red;">' + response.data + '</p>');
            }
        });
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

    // Resume Job Draft
    $(document).on('click', '.resume-draft-job', function() {
        var draftId = $(this).data('id');

        // First load the posting module
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_module',
            module: 'job-posting',
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                $('#jobs-module-container').html(response.data);

                // Then fetch draft data
                $.post(jobs_vars.ajax_url, {
                    action: 'jobs_get_draft_data',
                    draft_id: draftId,
                    nonce: jobs_vars.nonce
                }, function(dataResponse) {
                    if (dataResponse.success) {
                        var data = dataResponse.data;
                        $('[name="job_title"]').val(data.title);
                        $('[name="specialization"]').val(data.specialization);
                        $('[name="country"]').val(data.country);
                        $('[name="city"]').val(data.city);
                        $('[name="company_name"]').val(data.company_name);
                        $('[name="company_logo"]').val(data.company_logo || '');
                        $('[name="job_description"]').val(data.job_description);
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'draft_id',
                            value: draftId
                        }).appendTo('#job-posting-form');
                    }
                });
            }
        });
    });
});

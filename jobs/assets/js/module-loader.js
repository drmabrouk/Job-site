jQuery(document).ready(function($) {
    $(document).on('click', '.jobs-module-link', function(e) {
        var module = $(this).data('module');

        if (module === 'advanced-settings') {
            window.location.href = jobs_vars.admin_url;
            return;
        }

        e.preventDefault();

        $('#jobs-apps-menu').removeClass('active');
        $('body').css('overflow', '');

        if ($('#jobs-module-overlay').is(':hidden')) {
            $('#jobs-module-overlay').fadeIn(300);
        }

        $('#jobs-module-container').css('opacity', '0.5');

        // Load specific module assets if they exist
        if (jobs_vars.module_assets[module]) {
            var assets = jobs_vars.module_assets[module];
            if (assets.css) {
                var cssId = 'jobs-module-css-' + module;
                if (!$('#' + cssId).length) {
                    $('<link>', {
                        id: cssId,
                        rel: 'stylesheet',
                        type: 'text/css',
                        href: jobs_vars.plugin_url + assets.css
                    }).appendTo('head');
                }
            }
            if (assets.js) {
                var jsId = 'jobs-module-js-' + module;
                if (!$('#' + jsId).length) {
                    $('<script>', {
                        id: jsId,
                        src: jobs_vars.plugin_url + assets.js
                    }).appendTo('head');
                }
            }
        }

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_module',
            module: module,
            nonce: jobs_vars.nonce
        }, function(response) {
            $('#jobs-module-container').css('opacity', '1');
            if(response.success) {
                $('#jobs-module-container').html(response.data);
                window.JobsState.ui.activeModule = module;
            } else {
                $('#jobs-module-container').html('<p style="color:red; padding:20px;">Error: ' + response.data + '</p>');
            }
        });
    });

    $(document).on('click', '#jobs-close-module', function() {
        $('#jobs-module-overlay').fadeOut();
        window.JobsState.ui.activeModule = null;
    });

    // Quick Apply
    $(document).on('click', '.quick-apply-toggle', function() {
        const jobId = $(this).data('job-id');
        $('#jobs-module-overlay').fadeIn();
        $('#jobs-module-container').html('<p>Loading application form...</p>');

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
        const form = $(this).closest('form');
        const container = form.closest('.quick-apply-modal-content');
        const data = form.serialize() + '&action=jobs_quick_apply';

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                container.html('<p style="color: green;">Application submitted successfully!</p>');
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});

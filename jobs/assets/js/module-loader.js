jQuery(document).ready(function($) {
    $(document).on('click', '.jobs-module-link', function(e) {
        var module = $(this).data('module');
        var type = $(this).data('type') || 'modal';

        if (type === 'direct') {
            return; // Follow href
        }

        if (type === 'page') {
            window.location.href = jobs_vars.admin_url + '#' + module;
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

    // Resume Job Draft
    $(document).on('click', '.resume-draft-job', function() {
        var draftId = $(this).data('id');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_module',
            module: 'job-posting',
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                $('#jobs-module-container').html(response.data);

                $.post(jobs_vars.ajax_url, {
                    action: 'jobs_get_draft_data',
                    draft_id: draftId,
                    nonce: jobs_vars.nonce
                }, function(dataResponse) {
                    if (dataResponse.success) {
                        var data = dataResponse.data;
                        $('[name="job_title"]').val(data.title);
                        $('[name="specialization"]').val(data.specialization);
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

    // Favorite Toggle
    $(document).on('click', '.jobs-favorite-toggle', function() {
        var $btn = $(this);
        var jobId = $btn.data('job-id');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_toggle_favorite',
            job_id: jobId,
            nonce: jobs_vars.nonce
        }, function(response) {
            if (response.success) {
                if (response.data.status === 'added') {
                    $btn.css('color', '#e91e63');
                } else {
                    $btn.css('color', '#ccc');
                }
            }
        });
    });
});

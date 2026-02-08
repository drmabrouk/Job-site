jQuery(document).ready(function($) {
    $(document).on('click', '.jobs-module-link', function(e) {
        var module = $(this).data('module');
        var type = $(this).data('type') || 'modal';

        if (type === 'direct') {
            return; // Follow href
        }

        if (type === 'page') {
            // Reverted from SPA dashboard.
            // The link will be followed if we don't prevent default.
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
        const $btn = $(this);
        const form = $('.jobs-quick-apply-form');
        const data = form.serialize() + '&action=jobs_quick_apply' + '&nonce=' + jobs_vars.nonce;

        $btn.prop('disabled', true).text('Submitting...');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $('.quick-apply-multi-step').html('<div style="text-align:center; padding: 40px;"><span class="dashicons dashicons-yes-alt" style="font-size: 60px; width: 60px; height: 60px; color: #16a34a; margin-bottom: 20px;"></span><h3>Success!</h3><p>' + response.data + '</p><button class="jobs-btn" onclick="jQuery(\'#jobs-module-overlay\').fadeOut()" style="margin-top: 20px;">Close</button></div>');
            } else {
                $btn.prop('disabled', false).text('Confirm and Send Application');
                alert('Error: ' + response.data);
            }
        });
    });

    // Multi-step Apply Logic
    $(document).on('click', '.next-apply-step', function() {
        const nextStep = $(this).data('next');

        if (nextStep == 3) {
            const letter = $('#apply-cover-letter-text').val();
            $('#review-letter-content').text(letter);
        }

        $('.apply-step-panel').removeClass('active');
        $('#apply-step-' + nextStep).addClass('active');

        $('.apply-step-indicator').removeClass('active');
        $('.apply-step-indicator[data-step="' + nextStep + '"]').addClass('active');
    });

    $(document).on('click', '.select-saved-letter', function() {
        const index = $(this).data('index');
        const letter = window.savedCoverLetters[index];
        $('#apply-cover-letter-text').val(letter);

        $('.select-saved-letter').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', '.save-current-letter', function() {
        const $btn = $(this);
        const index = $btn.data('index');
        const content = $('#apply-cover-letter-text').val();

        if (!content) return alert('Letter is empty!');

        $btn.text('Saving...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_save_cover_letter',
            index: index,
            content: content,
            nonce: jobs_vars.nonce
        }, function(response) {
            if (response.success) {
                $btn.text('Saved to Slot ' + (index + 1));
                window.savedCoverLetters[index] = content;
                setTimeout(() => $btn.text('Save to Slot ' + (index + 1)), 2000);
            }
        });
    });

    $(document).on('click', '.print-letter, .export-pdf-letter', function() {
        const content = $('#apply-cover-letter-text').val();
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Cover Letter Export</title><style>body{font-family: "Rubik", sans-serif; padding: 60px; line-height: 1.8; color: #333; max-width: 800px; margin: 0 auto;} .header{border-bottom: 2px solid #1d3469; margin-bottom: 30px; padding-bottom: 10px;} h1{color: #1d3469; margin:0;} .content{white-space: pre-wrap;}</style></head><body><div class="header"><h1>Cover Letter</h1></div><div class="content">' + content + '</div><script>window.onload = function() { window.print(); }</script></body></html>');
        printWindow.document.close();
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
                    $btn.addClass('active');
                } else {
                    $btn.removeClass('active');
                }
            }
        });
    });
});

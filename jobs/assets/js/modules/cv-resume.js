(function($) {
    $(document).ready(function() {
        // Step Management
        function updateProgress(step) {
            var totalSteps = 6;
            var progress = (step / (totalSteps - 1)) * 100;
            $('#cv-progress-line').css('width', progress + '%');

            $('.cv-progress-step').each(function() {
                var s = $(this).data('step');
                if (s <= step) {
                    $(this).addClass('active').find('.step-circle').css({
                        'background': 'var(--jobs-primary-color)',
                        'color': 'white',
                        'border-color': 'var(--jobs-primary-color)'
                    });
                    $(this).find('span').css('color', '#1d3469');
                } else {
                    $(this).removeClass('active').find('.step-circle').css({
                        'background': 'white',
                        'color': '#94a3b8',
                        'border-color': '#e2e8f0'
                    });
                    $(this).find('span').css('color', '#94a3b8');
                }
            });
        }

        $(document).on('click', '.next-cv-step', function() {
            var next = $(this).data('next');
            $('.cv-step-panel').hide();
            $('#cv-step-' + next).fadeIn();
            updateProgress(next);
        });

        $(document).on('click', '.prev-cv-step', function() {
            var prev = $(this).data('prev');
            $('.cv-step-panel').hide();
            $('#cv-step-' + prev).fadeIn();
            updateProgress(prev);
        });

        // Repeater Logic
        $(document).on('click', '.add-repeater', function() {
            var type = $(this).data('type');
            var $container = $('#' + type + '-repeater');
            var index = $container.find('.repeater-item').length;
            var $clone = $container.find('.repeater-item').first().clone();

            $clone.find('input, select, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', newName).val('');
                }
            });

            if($clone.find('.remove-repeater').length === 0) {
                $clone.append('<button type="button" class="remove-repeater">Remove</button>');
            }

            $clone.hide().appendTo($container).fadeIn();
        });

        $(document).on('click', '.remove-repeater', function() {
            $(this).closest('.repeater-item').fadeOut(function() { $(this).remove(); });
        });

        // Skills Suggestions
        $(document).on('input', '#cv-skills-autocomplete', function() {
            const val = $(this).val();
            const parts = val.split(',');
            const query = parts[parts.length - 1].trim().toLowerCase();

            if (query.length < 1) {
                $('#cv-skills-suggestions').hide();
                return;
            }

            if (typeof skillsList === 'undefined') return;

            const matches = skillsList.filter(s => s.toLowerCase().includes(query));
            if (matches.length > 0) {
                let html = '';
                matches.slice(0, 10).forEach(m => {
                    html += `<div class="suggestion-item skill-suggestion-cv" data-val="${m}">${m}</div>`;
                });
                $('#cv-skills-suggestions').html(html).show();
            } else {
                $('#cv-skills-suggestions').hide();
            }
        });

        $(document).on('click', '.skill-suggestion-cv', function() {
            const skill = $(this).data('val');
            const $input = $('#cv-skills-autocomplete');
            const parts = $input.val().split(',');
            parts[parts.length - 1] = ' ' + skill;
            $input.val(parts.join(',').trim() + ', ');
            $('#cv-skills-suggestions').hide();
            $input.focus();
        });

        // Employer Suggestions
        let cvSuggestionTimeout;
        $(document).on('input', '.employer-suggestion-cv', function() {
            const $input = $(this);
            const $list = $input.siblings('.employer-suggestions-cv-list');
            const query = $input.val();

            clearTimeout(cvSuggestionTimeout);
            if (query.length < 2) {
                $list.hide();
                return;
            }

            cvSuggestionTimeout = setTimeout(() => {
                $.post(jobs_vars.ajax_url, {
                    action: 'jobs_suggest_employers',
                    nonce: jobs_vars.nonce,
                    q: query
                }, function(response) {
                    if (response.success && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(item => {
                            html += `<div class="suggestion-item cv-emp-suggestion" data-val="${item}">${item}</div>`;
                        });
                        $list.html(html).show();
                    } else {
                        $list.hide();
                    }
                });
            }, 300);
        });

        $(document).on('click', '.cv-emp-suggestion', function() {
            $(this).closest('.form-group').find('input').val($(this).data('val'));
            $('.employer-suggestions-cv-list').hide();
        });

        // Professions/Regions Dynamic Update
        $(document).on('change', '#cv-specialization', function() {
            const spec = $(this).val();
            const $profSelect = $('#cv-profession');
            $profSelect.empty().append('<option value="">Select Profession</option>');
            if (spec && window.specializationsData[spec]) {
                window.specializationsData[spec].forEach(prof => {
                    $profSelect.append(`<option value="${prof}">${prof}</option>`);
                });
                $profSelect.prop('disabled', false);
            } else {
                $profSelect.prop('disabled', true);
            }
        });

        $(document).on('change', '#cv-country', function() {
            const country = $(this).val();
            const $regionSelect = $('#cv-region');
            $regionSelect.empty().append('<option value="">Select Region / State</option>');
            if (country && window.locationData[country]) {
                window.locationData[country].forEach(region => {
                    $regionSelect.append(`<option value="${region}">${region}</option>`);
                });
                $regionSelect.prop('disabled', false);
            } else {
                $regionSelect.prop('disabled', true);
            }
        });

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
                formData.append('action', 'jobs_save_cv_handler_v3');
                formData.append('profile_photo', file);
                formData.append('jobs_cv_nonce', $('input[name="jobs_cv_nonce"]').val());

                var $status = $('#jobs-cv-status-v3');
                $status.html('<p style="color: #1d3469; font-weight: 600;">Syncing identity...</p>').show();

                $.ajax({
                    url: jobs_vars.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            $status.html('<p style="color: #059669; font-weight: 700;">✓ Identity synchronized globally.</p>');
                            setTimeout(function() { $status.fadeOut(); }, 3000);
                        }
                    }
                });
            }
        });

        // Form Submit
        $(document).on('submit', '#jobs-cv-form-v3', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'jobs_save_cv_handler_v3');
            // Use specific nonce
            formData.append('jobs_cv_nonce', $('input[name="jobs_cv_nonce"]').val());

            var $status = $('#jobs-cv-status-v3');
            $status.html('<p style="color:#666; font-weight:600;">Updating your account data and profile...</p>').show();

            $.ajax({
                url: jobs_vars.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        $status.html('<div style="background:#dcfce7; color:#166534; padding:20px; border-radius:12px; font-weight:600;">✓ Account data updated successfully! Your public profile has been updated instantly.</div>');
                        setTimeout(function() {
                            if (typeof window.profileLink !== 'undefined') window.location.href = window.profileLink;
                        }, 2000);
                    } else {
                        $status.html('<p style="color:#ef4444; font-weight:600;">Error: ' + response.data + '</p>');
                    }
                }
            });
        });
    });
})(jQuery);

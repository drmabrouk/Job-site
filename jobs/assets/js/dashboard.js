(function($) {
    $(document).ready(function() {
        if (!$('.jobs-dashboard-wrapper').length) return;

        function loadDashModule(module) {
            $('.jobs-dash-tab').removeClass('active');
            $('[data-tab="' + module + '"]').addClass('active');

            $('#jobs-dash-content-area').css('opacity', '0.5');

            $.post(jobs_vars.ajax_url, {
                action: 'jobs_load_module',
                module: module,
                nonce: jobs_vars.nonce
            }, function(response) {
                $('#jobs-dash-content-area').css('opacity', '1');
                if(response.success) {
                    $('#jobs-dash-content-area').html(response.data);
                } else {
                    $('#jobs-dash-content-area').html('<p style="color:red; padding:40px;">Error: ' + response.data + '</p>');
                }
            });
        }

        // Handle hash changes
        $(window).on('hashchange', function() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                loadDashModule(hash);
            }
        });

        // Initial load
        const initialHash = window.location.hash.substring(1);
        if (initialHash) {
            loadDashModule(initialHash);
        } else {
            // Default based on role or just pick first visible
            const firstTab = $('.jobs-dash-tab').first().data('tab');
            if (firstTab) {
                window.location.hash = firstTab;
            }
        }

        $(document).on('click', '.jobs-dash-tab', function(e) {
            // Hashchange will handle the load
        });
    });
})(jQuery);

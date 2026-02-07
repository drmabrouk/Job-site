jQuery(document).ready(function($) {
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

    $(document).on('click', function(event) {
        // Click outside apps menu
        if (!$(event.target).closest('.apps-grid-card').length && !$(event.target).closest('#jobs-apps-toggle').length) {
            if ($('#jobs-apps-menu').hasClass('active')) {
                $('#jobs-apps-menu').removeClass('active');
                $('body').css('overflow', '');
            }
        }

        // Click outside profile menu
        if (!$(event.target).closest('#jobs-profile-menu').length && !$(event.target).closest('#jobs-profile-toggle').length) {
            $('#jobs-profile-menu').removeClass('active');
        }
    });

    $('.apps-grid-card, #jobs-profile-menu').on('click', function(e) {
        e.stopPropagation();
    });

    // Simple Polling for Notifications
    function checkNotifications() {
        $.post(jobs_vars.ajax_url, { action: 'jobs_get_unread_count', nonce: jobs_vars.nonce }, function(response) {
            if (response.success) {
                var count = response.data;
                var $badge = $('.notif-badge');
                if (count > 0) {
                    if ($badge.length) {
                        $badge.text(count);
                    } else {
                        $('#jobs-notif-toggle').append('<span class="notif-badge">' + count + '</span>');
                    }
                } else {
                    $badge.remove();
                }
            }
        });
    }

    // Run every 30 seconds
    if (jobs_vars.nonce) {
        setInterval(checkNotifications, 30000);
    }
});

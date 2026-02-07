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

    $(document).on('click', function() {
        $('#jobs-apps-menu, #jobs-profile-menu').removeClass('active');
    });

    $('#jobs-apps-menu, #jobs-profile-menu').on('click', function(e) {
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
    // setInterval(checkNotifications, 30000); // Check every 30 seconds
});

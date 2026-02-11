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
        $('#jobs-notif-menu').removeClass('active');
    });

    $('#jobs-notif-toggle').on('click', function(e) {
        if ($(e.target).closest('#jobs-notif-menu').length) return; // Don't toggle if clicking inside the menu

        e.stopPropagation();
        $('#jobs-notif-menu').toggleClass('active');
        $('#jobs-profile-menu').removeClass('active');
        $('#jobs-apps-menu').removeClass('active');

        if ($('#jobs-notif-menu').hasClass('active')) {
            $('#jobs-notif-detail').hide();
            $('#jobs-notif-list').show();
            $('.dropdown-header strong').text('Notifications');
            loadNotifications();
        }
    });

    $('#jobs-notif-menu').on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.notif-item', function() {
        var $this = $(this);
        var content = $this.data('content');
        var time = $this.data('time');
        var avatar = $this.data('avatar');
        var sender = $this.data('sender');

        $('#notif-detail-content').text(content);
        $('#notif-detail-time').text(time);
        $('#notif-detail-sender').text(sender);

        if (avatar) {
            $('#notif-detail-avatar').attr('src', avatar).show();
            $('#notif-detail-icon-placeholder').hide();
        } else {
            $('#notif-detail-avatar').hide();
            $('#notif-detail-icon-placeholder').show();
        }

        $('#jobs-notif-list').hide();
        $('#jobs-notif-detail').show();
        $('.dropdown-header strong').text('Notification Detail');
    });

    $(document).on('click', '#notif-back', function() {
        $('#jobs-notif-detail').hide();
        $('#jobs-notif-list').show();
        $('.dropdown-header strong').text('Notifications');
    });

    function loadNotifications() {
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_get_notifications',
            nonce: jobs_vars.nonce
        }, function(response) {
            if (response.success) {
                $('#jobs-notif-list').html(response.data);
            }
        });
    }

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

        // Click outside notifications menu
        if (!$(event.target).closest('#jobs-notif-menu').length && !$(event.target).closest('#jobs-notif-toggle').length) {
            $('#jobs-notif-menu').removeClass('active');
        }
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

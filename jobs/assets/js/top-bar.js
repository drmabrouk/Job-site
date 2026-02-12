jQuery(document).ready(function($) {
    $(document).on('click', '#jobs-account-mgmt-toggle', function(e) {
        e.stopPropagation();
        $('#jobs-apps-menu').toggleClass('active');
        $('#jobs-profile-menu').removeClass('active');
        $('#jobs-notif-menu').removeClass('active');
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

    $(document).on('click', '#jobs-notif-toggle', function(e) {
        if ($(e.target).closest('#jobs-notif-menu').length) return;

        e.stopPropagation();
        $('#jobs-notif-menu').toggleClass('active');
        $('#jobs-profile-menu').removeClass('active');
        $('#jobs-apps-menu').removeClass('active');

        if ($('#jobs-notif-menu').hasClass('active')) {
            $('#jobs-notif-detail').hide();
            $('#jobs-notif-list').show();
            $('#jobs-notif-menu .dropdown-header strong').text('Notifications');
            loadNotifications();
        }
    });

    $('#jobs-notif-menu').on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.notif-item', function() {
        var $this = $(this);

        // Remove existing detail panels if any
        $('.notif-inline-detail').slideUp(200, function() { $(this).remove(); });

        if ($this.hasClass('is-open')) {
            $this.removeClass('is-open');
            return;
        }

        $('.notif-item').removeClass('is-open');
        $this.addClass('is-open');

        var content = $this.data('content');
        var time = $this.data('time');
        var sender = $this.data('sender');

        var detailHtml = `
            <div class="notif-inline-detail" style="display:none; padding: 15px; background: #f8fafc; border-top: 1px solid #edf2f7; margin-top: 10px; border-radius: 8px;">
                <div style="font-weight: 700; color: #1d3469; font-size: 0.9em; margin-bottom: 5px;">From: ${sender}</div>
                <div style="color: #475569; font-size: 0.95em; line-height: 1.5;">${content}</div>
                <div style="font-size: 0.8em; color: #94a3b8; margin-top: 10px;">Received: ${time}</div>
            </div>
        `;

        $this.append(detailHtml);
        $this.find('.notif-inline-detail').slideDown(200);
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
        // Click outside account management menu
        if (!$(event.target).closest('.apps-grid-card').length && !$(event.target).closest('#jobs-account-mgmt-toggle').length) {
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

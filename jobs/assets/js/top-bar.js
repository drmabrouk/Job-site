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
});

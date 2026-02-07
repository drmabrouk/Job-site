jQuery(document).ready(function($) {
    $('.auth-tab').on('click', function() {
        var target = $(this).data('target');
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-panel').removeClass('active');
        $('#auth-' + target).addClass('active');
    });

    // Add placeholders to wp_login_form fields
    $('#user_login').attr('placeholder', 'Username or Email');
    $('#user_pass').attr('placeholder', 'Password');
});

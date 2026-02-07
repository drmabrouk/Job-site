jQuery(document).ready(function($) {
    $('.auth-tab').on('click', function() {
        var target = $(this).data('target');
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-panel').removeClass('active');
        $('#auth-' + target).addClass('active');
    });
});

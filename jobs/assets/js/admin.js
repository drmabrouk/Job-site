jQuery(document).ready(function($) {
    $('.jobs-admin-tab').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('tab');

        $('.jobs-admin-tab').removeClass('active');
        $(this).addClass('active');

        $('.jobs-tab-content').hide();
        $('#tab-' + target).show();
    });
});

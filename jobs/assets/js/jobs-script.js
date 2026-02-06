jQuery(document).ready(function($) {
    // Toggle dropdown menu
    $('#jobs-avatar-toggle').on('click', function(e) {
        e.stopPropagation();
        $('#jobs-dropdown').toggleClass('active');
    });

    $(document).on('click', function() {
        $('#jobs-dropdown').removeClass('active');
    });

    $('#jobs-dropdown').on('click', function(e) {
        e.stopPropagation();
    });

    // Module link clicks
    $('.jobs-module-link').on('click', function(e) {
        var module = $(this).data('module');

        if (module === 'advanced-settings') {
            window.location.href = '/jobs-admin-panel'; // Assuming the slug
            return;
        }
        if (module === 'articles') {
            window.location.href = '/articles'; // Assuming the slug
            return;
        }

        e.preventDefault();
        console.log('Loading module: ' + module);
        // In a real implementation, this would load content via AJAX
        alert('Module "' + $(this).text() + '" is prepared and will be implemented in the future.');
    });
});

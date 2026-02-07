jQuery(document).ready(function($) {
    function updateSearchResults(page = 1) {
        if (window.JobsState.ui.isSearching) return;

        window.JobsState.search.job_search = $('#jobs-input-search').val();
        window.JobsState.search.specialization = $('#jobs-input-specialization').val();
        window.JobsState.search.paged = page;

        if (window.JobsState.search.job_search.length > 0 && window.JobsState.search.job_search.length < 3) return;

        window.JobsState.ui.isSearching = true;
        $('#jobs-status-indicator').fadeIn();

        const data = {
            action: 'jobs_filter',
            ...window.JobsState.search
        };

        $.get(jobs_vars.ajax_url, data, function(response) {
            $('#jobs-results-container').html(response);
            $('#jobs-status-indicator').fadeOut();
            window.JobsState.ui.isSearching = false;
        });
    }

    let searchDebounce;
    $('#jobs-input-search').on('keyup', function() {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => updateSearchResults(1), 500);
    });

    $('#jobs-input-specialization').on('change', () => updateSearchResults(1));

    $(document).on('click', '.jobs-pagination a', function(e) {
        e.preventDefault();
        updateSearchResults($(this).data('page'));
        $('html, body').animate({ scrollTop: $('#jobs-search-form').offset().top - 100 }, 500);
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            window.JobsState.search.lat = position.coords.latitude;
            window.JobsState.search.lng = position.coords.longitude;
            updateSearchResults(1);
        }, () => updateSearchResults(1));
    } else {
        updateSearchResults(1);
    }
});

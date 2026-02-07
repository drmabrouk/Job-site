jQuery(document).ready(function($) {
    const locationData = {
        "uae": ["Dubai", "Abu Dhabi", "Sharjah", "Ajman", "Fujairah", "Ras Al Khaimah", "Umm Al Quwain"],
        "saudi-arabia": ["Riyadh", "Jeddah", "Mecca", "Medina", "Dammam", "Khobar", "Abha"],
        "qatar": ["Doha", "Al Wakrah", "Al Rayyan", "Al Khor"],
        "kuwait": ["Kuwait City", "Al Ahmadi", "Hawalli", "Salmiya"],
        "egypt": ["Cairo", "Alexandria", "Giza", "Sharm El Sheikh", "Hurghada", "Luxor"],
        "jordan": ["Amman", "Zarqa", "Irbid", "Aqaba"],
        "lebanon": ["Beirut", "Tripoli", "Sidon", "Tyre"],
        "oman": ["Muscat", "Salalah", "Sohar", "Nizwa"],
        "bahrain": ["Manama", "Riffa", "Muharraq", "Hamad Town"]
    };

    function updateSearchResults(page = 1, append = false) {
        if (window.JobsState.ui.isSearching) return;

        // Limit to max 12 jobs total
        if (append && $('.job-card').length >= 12) return;

        window.JobsState.search.job_search = $('#jobs-input-search').val();
        window.JobsState.search.specialization = $('#jobs-input-specialization').val();
        window.JobsState.search.country = $('#jobs-input-country').val();
        window.JobsState.search.city = $('#jobs-input-city').val();
        window.JobsState.search.paged = page;
        window.JobsState.search.per_page = 6; // Always 6 per chunk

        if (window.JobsState.search.job_search.length > 0 && window.JobsState.search.job_search.length < 3) return;

        window.JobsState.ui.isSearching = true;
        if (!append) $('#jobs-status-indicator').fadeIn();

        const data = {
            action: 'jobs_filter',
            ...window.JobsState.search
        };
        if (append) data.load_more = 1;

        $.get(jobs_vars.ajax_url, data, function(response) {
            if (append) {
                $('.jobs-results-grid').append(response);
                // Update hidden next page flag
                const nextPage = page + 1;
                $('#jobs-has-more').data('next-page', nextPage);
            } else {
                $('#jobs-results-container').html(response);
            }

            $('#jobs-status-indicator').fadeOut();
            window.JobsState.ui.isSearching = false;
        });
    }

    // Country change logic
    $('#jobs-input-country').on('change', function() {
        const country = $(this).val();
        const $citySelect = $('#jobs-input-city');
        $citySelect.empty().append('<option value="">City</option>');

        if (country && locationData[country]) {
            locationData[country].forEach(function(city) {
                $citySelect.append('<option value="' + city.toLowerCase().replace(/ /g, '-') + '">' + city + '</option>');
            });
        }
        updateSearchResults(1);
    });

    let searchDebounce;
    $('#jobs-input-search').on('keyup', function() {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => updateSearchResults(1), 500);
    });

    $('#jobs-input-specialization, #jobs-input-city').on('change', () => updateSearchResults(1));

    // Infinite scroll logic
    $(window).on('scroll', function() {
        if (window.JobsState.ui.isSearching) return;

        const $hasMore = $('#jobs-has-more');
        if (!$hasMore.length) return;

        const nextPage = parseInt($hasMore.data('next-page'));
        const maxPages = parseInt($hasMore.data('max-pages'));

        if (nextPage > maxPages) return;
        if ($('.job-card').length >= 12) return; // Hard limit 12

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
            updateSearchResults(nextPage, true);
        }
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

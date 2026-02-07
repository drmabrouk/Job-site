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

    function getPerPage() {
        const width = $(window).width();
        if (width > 991) return 6; // Desktop initial
        if (width > 767) return 4; // Tablet initial
        return 3; // Mobile initial
    }

    function getLoadMoreCount() {
        const width = $(window).width();
        if (width > 991) return 3;
        return 2;
    }

    function getMaxCards() {
        const width = $(window).width();
        if (width > 991) return 12;
        if (width > 767) return 8;
        return 6;
    }

    function updateSearchResults(page = 1, append = false) {
        if (window.JobsState.ui.isSearching) return;

        window.JobsState.search.job_search = $('#jobs-input-search').val();
        window.JobsState.search.specialization = $('#jobs-input-specialization').val();
        window.JobsState.search.country = $('#jobs-input-country').val();
        window.JobsState.search.city = $('#jobs-input-city').val();
        window.JobsState.search.paged = page;
        window.JobsState.search.per_page = append ? getLoadMoreCount() : getPerPage();

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
            } else {
                $('#jobs-results-container').html(response);
            }

            $('#jobs-status-indicator').fadeOut();
            window.JobsState.ui.isSearching = false;

            // Check if we reached max limit
            const currentCount = $('.job-card').length;
            if (currentCount >= getMaxCards()) {
                $('#jobs-load-more-btn').hide();
            }
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

    $(document).on('click', '#jobs-load-more-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const page = parseInt($btn.data('page'));
        updateSearchResults(page, true);
        $btn.data('page', page + 1);
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

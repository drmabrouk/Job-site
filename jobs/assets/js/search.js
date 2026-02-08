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
        "bahrain": ["Manama", "Riffa", "Muharraq", "Hamad Town"],
        "algeria": ["Algiers", "Oran", "Constantine"],
        "iran": ["Tehran", "Mashhad", "Isfahan"],
        "iraq": ["Baghdad", "Basra", "Erbil"],
        "libya": ["Tripoli", "Benghazi"],
        "morocco": ["Casablanca", "Rabat", "Marrakesh"],
        "palestine": ["Gaza City", "Ramallah", "Hebron"],
        "syria": ["Damascus", "Aleppo"],
        "tunisia": ["Tunis", "Sfax"],
        "yemen": ["Sanaa", "Aden"],
        "usa": ["New York", "Los Angeles", "Chicago", "Houston", "Phoenix"],
        "uk": ["London", "Birmingham", "Manchester", "Glasgow"],
        "canada": ["Toronto", "Montreal", "Vancouver"],
        "australia": ["Sydney", "Melbourne", "Brisbane"],
        "new-zealand": ["Auckland", "Wellington"],
        "ireland": ["Dublin", "Cork"],
        "south-africa": ["Johannesburg", "Cape Town", "Durban"]
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
        window.JobsState.search.per_page = 3; // Progressive loading: 3 at a time

        if (window.JobsState.search.job_search.length > 0 && window.JobsState.search.job_search.length < 3) return;

        window.JobsState.ui.isSearching = true;

        const $indicator = $('#jobs-status-indicator');
        const $results = $('#jobs-results-container');

        if (!append) {
            $indicator.css('display', 'flex').hide().fadeIn(400);
        } else {
            // Show bottom loader for append
            if (!$('#jobs-bottom-loader').length) {
                $('.jobs-results-grid').after('<div id="jobs-bottom-loader" class="jobs-status-indicator" style="display:flex; flex-direction:column; align-items:center; justify-content:center;"><div class="indicator-spinner"></div><p>Discovering more opportunities...</p></div>');
            }
            $('#jobs-bottom-loader').fadeIn(400);
        }

        const data = {
            action: 'jobs_filter',
            ...window.JobsState.search
        };
        if (append) data.load_more = 1;

        // Mandate 1-second delay for smooth rendering and professional effect
        const delay = 1000;

        setTimeout(function() {
            $.get(jobs_vars.ajax_url, data, function(response) {
                if (append) {
                    const $newCards = $(response).hide();
                    $('.jobs-results-grid').append($newCards);
                    $newCards.fadeIn(600);

                    const nextPage = page + 1;
                    $('#jobs-has-more').data('next-page', nextPage);
                    $('#jobs-bottom-loader').fadeOut(300);

                    if ($('.job-card').length >= 12) {
                        if (!$('#jobs-limit-notif').length) {
                            var limitMsg = '<div id="jobs-limit-notif" style="text-align:center; padding: 50px 30px; background: #fff; border-radius: 32px; margin: 50px 0; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02); animation: fadeIn 0.8s ease;">';
                            limitMsg += '<h4 style="color:#1d3469; margin-bottom:15px; font-size:1.2em;">Showing the most relevant opportunities matching your search.</h4>';
                            limitMsg += '<div style="max-width:500px; margin:0 auto; text-align:left; background:#f8fafc; padding:25px; border-radius:20px; font-size:0.9em; color:#64748b;">';
                            limitMsg += '<strong>💡 Pro Tips for better results:</strong><ul style="margin-top:10px; padding-left:20px;">';
                            limitMsg += '<li style="margin-bottom:8px;">Try using more general keywords.</li>';
                            limitMsg += '<li style="margin-bottom:8px;">Filter by your specific city or specialization.</li>';
                            limitMsg += '<li>Check back tomorrow for fresh listings!</li></ul></div></div>';
                            $('.jobs-results-grid').after(limitMsg);
                        }
                        $('#jobs-has-more').remove();
                    }
                } else {
                    $results.hide().html(response).fadeIn(600);
                    $indicator.fadeOut(300);

                    if ($('.job-card').length >= 12) {
                        $('#jobs-has-more').remove();
                        if (!$('#jobs-limit-notif').length) {
                            var limitMsg = '<div id="jobs-limit-notif" style="text-align:center; padding: 50px 30px; background: #fff; border-radius: 32px; margin: 50px 0; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02); animation: fadeIn 0.8s ease;">';
                            limitMsg += '<h4 style="color:#1d3469; margin-bottom:15px; font-size:1.2em;">Showing the most relevant opportunities matching your search.</h4>';
                            limitMsg += '<div style="max-width:500px; margin:0 auto; text-align:left; background:#f8fafc; padding:25px; border-radius:20px; font-size:0.9em; color:#64748b;">';
                            limitMsg += '<strong>💡 Pro Tips for better results:</strong><ul style="margin-top:10px; padding-left:20px;">';
                            limitMsg += '<li style="margin-bottom:8px;">Try using more general keywords.</li>';
                            limitMsg += '<li style="margin-bottom:8px;">Filter by your specific city or specialization.</li>';
                            limitMsg += '<li>Check back tomorrow for fresh listings!</li></ul></div></div>';
                            $('.jobs-results-grid').after(limitMsg);
                        }
                    }
                }
                window.JobsState.ui.isSearching = false;
            });
        }, delay);
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

    // Toggle card application dropdown
    $(document).on('click', '.card-quick-apply-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#apply-dropdown-' + id).slideToggle(300);
    });

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

    // Initial Load
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

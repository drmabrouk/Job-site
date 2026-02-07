jQuery(document).ready(function($) {
    // Centralized State Management
    const JobsState = {
        search: {
            job_search: '',
            specialization: '',
            lat: 0,
            lng: 0,
            paged: 1
        },
        ui: {
            isSearching: false,
            activeModule: null
        }
    };

    // Toggles for Top Bar
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

    // Module link clicks
    $(document).on('click', '.jobs-module-link', function(e) {
        var module = $(this).data('module');

        if (module === 'advanced-settings') {
            window.location.href = jobs_vars.admin_url;
            return;
        }

        e.preventDefault();

        $('#jobs-apps-menu').removeClass('active');
        $('body').css('overflow', '');

        if ($('#jobs-module-overlay').is(':hidden')) {
            $('#jobs-module-overlay').fadeIn(300);
        }

        $('#jobs-module-container').css('opacity', '0.5');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_module',
            module: module,
            nonce: jobs_vars.nonce
        }, function(response) {
            $('#jobs-module-container').css('opacity', '1');
            if(response.success) {
                $('#jobs-module-container').html(response.data);
                JobsState.ui.activeModule = module;
            } else {
                $('#jobs-module-container').html('<p style="color:red; padding:20px;">Error: ' + response.data + '</p>');
            }
        });
    });

    $(document).on('click', '#jobs-close-module', function() {
        $('#jobs-module-overlay').fadeOut();
        JobsState.ui.activeModule = null;
    });

    // Search Logic with State
    function updateSearchResults(page = 1) {
        if (JobsState.ui.isSearching) return;

        JobsState.search.job_search = $('#jobs-input-search').val();
        JobsState.search.specialization = $('#jobs-input-specialization').val();
        JobsState.search.paged = page;

        if (JobsState.search.job_search.length > 0 && JobsState.search.job_search.length < 3) return;

        JobsState.ui.isSearching = true;
        $('#jobs-status-indicator').fadeIn();

        const data = {
            action: 'jobs_filter',
            ...JobsState.search
        };

        $.get(jobs_vars.ajax_url, data, function(response) {
            $('#jobs-results-container').html(response);
            $('#jobs-status-indicator').fadeOut();
            JobsState.ui.isSearching = false;
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

    // Initialize Geolocation in State
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            JobsState.search.lat = position.coords.latitude;
            JobsState.search.lng = position.coords.longitude;
            updateSearchResults(1);
        }, () => updateSearchResults(1));
    } else {
        updateSearchResults(1);
    }

    // Quick Apply
    $(document).on('click', '.quick-apply-toggle', function() {
        const jobId = $(this).data('job-id');
        $('#jobs-module-overlay').fadeIn();
        $('#jobs-module-container').html('<p>Loading application form...</p>');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_load_quick_apply_form',
            job_id: jobId,
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                $('#jobs-module-container').html(response.data);
            } else {
                $('#jobs-module-container').html('<p style="color:red;">' + response.data + '</p>');
            }
        });
    });

    $(document).on('click', '.submit-quick-apply', function() {
        const form = $(this).closest('form');
        const container = form.closest('.quick-apply-modal-content');
        const data = form.serialize() + '&action=jobs_quick_apply';

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                container.html('<p style="color: green;">Application submitted successfully!</p>');
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});

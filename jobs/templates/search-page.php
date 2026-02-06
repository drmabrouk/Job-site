<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-search-page jobs-transparent-bg">
    <?php if ( is_front_page() || get_the_ID() == get_option('page_on_front') || (isset($is_job_homepage) && $is_job_homepage) ) : ?>
    <div class="jobs-google-header">
        <?php
        $logo_url = get_option( 'jobs_site_logo' );
        $logo_width = get_option( 'jobs_logo_width', '300' );
        $logo_height = get_option( 'jobs_logo_height', 'auto' );
        ?>
        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" class="jobs-main-logo" style="width:<?php echo esc_attr($logo_width); ?>px; height:<?php echo esc_attr($logo_height); ?>;">
    </div>
    <?php endif; ?>

    <div class="jobs-search-engine">
        <form id="jobs-search-form" action="" method="GET">
            <div class="search-row-top">
                <input type="text" name="job_search" id="jobs-input-search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" class="full-width">
            </div>
            <div class="search-row-bottom">
                <input type="text" name="specialization" id="jobs-input-specialization" placeholder="Specialization">

                <select name="country" id="jobs-input-country">
                    <option value="">Select Country</option>
                    <option value="uae">United Arab Emirates</option>
                    <option value="saudi-arabia">Saudi Arabia</option>
                    <option value="qatar">Qatar</option>
                    <option value="kuwait">Kuwait</option>
                    <option value="egypt">Egypt</option>
                    <option value="jordan">Jordan</option>
                    <option value="lebanon">Lebanon</option>
                    <option value="oman">Oman</option>
                    <option value="bahrain">Bahrain</option>
                </select>

                <select name="city" id="jobs-input-city">
                    <option value="">Select City</option>
                </select>

                <button type="submit">Search</button>
            </div>
        </form>
    </div>

    <div id="jobs-results-container" class="jobs-results-grid">
        <p class="jobs-loader" style="display:none;">Searching jobs...</p>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var userLocation = { country: '', city: '' };

    // Attempt to get user location via free API
    $.getJSON('https://ipapi.co/json/', function(data) {
        userLocation.country = data.country_name;
        userLocation.city = data.city;
        console.log('User location detected:', userLocation);
        if (!$('#jobs-input-country').val()) {
            // Optionally pre-set or just use for prioritization in backend
        }
    });

    var locationData = {
        "uae": ["Dubai", "Abu Dhabi", "Sharjah"],
        "saudi-arabia": ["Riyadh", "Jeddah", "Dammam"],
        "qatar": ["Doha"],
        "kuwait": ["Kuwait City"],
        "egypt": ["Cairo", "Alexandria"],
        "jordan": ["Amman"],
        "lebanon": ["Beirut"],
        "oman": ["Muscat"],
        "bahrain": ["Manama"]
    };

    $('#jobs-input-country').on('change', function() {
        var country = $(this).val();
        var citySelect = $('#jobs-input-city');
        citySelect.empty().append('<option value="">Select City</option>');

        if (country && locationData[country]) {
            locationData[country].forEach(function(city) {
                citySelect.append('<option value="' + city.toLowerCase().replace(' ', '-') + '">' + city + '</option>');
            });
        }
        filterJobs(1);
    });

    function filterJobs(page = 1) {
        var data = {
            action: 'jobs_filter',
            job_search: $('#jobs-input-search').val(),
            specialization: $('#jobs-input-specialization').val(),
            country: $('#jobs-input-country').val(),
            city: $('#jobs-input-city').val(),
            user_country: userLocation.country,
            user_city: userLocation.city,
            paged: page
        };

        $('.jobs-loader').show();

        $.get('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            $('#jobs-results-container').html(response);
            $('.jobs-loader').hide();
        });
    }

    $('#jobs-input-search, #jobs-input-specialization').on('keyup change', function() {
        filterJobs(1);
    });

    $('#jobs-input-city').on('change', function() {
        filterJobs(1);
    });

    $('#jobs-search-form').on('submit', function(e) {
        e.preventDefault();
        filterJobs(1);
    });

    $(document).on('click', '.jobs-pagination a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        filterJobs(page);
        $('html, body').animate({ scrollTop: $('#jobs-search-form').offset().top }, 500);
    });

    filterJobs();
});
</script>

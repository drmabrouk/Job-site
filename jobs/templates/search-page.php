<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-search-page jobs-transparent-bg">
    <?php if ( is_front_page() || get_the_ID() == get_option('page_on_front') || (isset($is_job_homepage) && $is_job_homepage) ) : ?>
    <div class="jobs-google-header">
        <?php
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id , 'full' )[0] : get_option( 'jobs_site_logo' );
        $logo_width = get_option( 'jobs_logo_width', '300' );
        $logo_height = get_option( 'jobs_logo_height', 'auto' );
        ?>
        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" class="jobs-main-logo" style="width:<?php echo esc_attr($logo_width); ?>px; height:<?php echo esc_attr($logo_height); ?>;">
    </div>
    <?php endif; ?>

    <div class="jobs-search-engine-centered">
        <form id="jobs-search-form" action="" method="GET">
            <div class="search-main-field">
                <input type="text" name="job_search" id="jobs-input-search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'What job are you looking for?' ) ); ?>" autocomplete="off">
                <div class="search-icon-inside">🔍</div>
            </div>
            <div class="search-secondary-field">
                <select name="specialization" id="jobs-input-specialization">
                    <option value="">All Specializations</option>
                    <?php
                    $specializations = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                    foreach ( $specializations as $term ) {
                        echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </form>
    </div>

    <div id="jobs-status-indicator" class="jobs-status-indicator" style="display:none;">
        <div class="indicator-spinner"></div>
        <p id="indicator-message">Finding the best jobs near you...</p>
    </div>

    <div id="jobs-search-results-wrapper">
        <div id="jobs-results-container">
            <!-- Results will appear here -->
        </div>
    </div>

    <?php
    $adsense_code = get_option( 'jobs_adsense_code' );
    if ( $adsense_code ) : ?>
    <div class="jobs-adsense-container" style="margin-top: 50px; text-align: center;">
        <?php echo $adsense_code; ?>
    </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    var userCoords = { lat: 0, lng: 0 };
    var searchTimeout;

    // Browser Geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userCoords.lat = position.coords.latitude;
            userCoords.lng = position.coords.longitude;
            console.log('User coords:', userCoords);
            filterJobs(1);
        }, function(error) {
            console.warn('Geolocation error:', error.message);
            filterJobs(1);
        });
    } else {
        filterJobs(1);
    }

    function filterJobs(page = 1) {
        var query = $('#jobs-input-search').val();
        var spec = $('#jobs-input-specialization').val();

        // Only search if 3+ chars (or if specialization changed)
        if (query.length > 0 && query.length < 3) {
            return;
        }

        var data = {
            action: 'jobs_filter',
            job_search: query,
            specialization: spec,
            lat: userCoords.lat,
            lng: userCoords.lng,
            paged: page
        };

        // Show professional loader
        $('#jobs-status-indicator').fadeIn();
        var messages = [
            "Finding the best jobs near you...",
            "Generating matching opportunities...",
            "Analyzing local job market...",
            "Matching your profile with nearby employers..."
        ];
        $('#indicator-message').text(messages[Math.floor(Math.random() * messages.length)]);

        $.get(jobs_vars.ajax_url, data, function(response) {
            $('#jobs-results-container').html(response);
            $('#jobs-status-indicator').fadeOut();
        });
    }

    $('#jobs-input-search').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterJobs(1);
        }, 500);
    });

    $('#jobs-input-specialization').on('change', function() {
        filterJobs(1);
    });

    $(document).on('click', '.jobs-pagination a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        filterJobs(page);
        $('html, body').animate({ scrollTop: $('#jobs-search-form').offset().top - 100 }, 500);
    });

    // Initial load if coords take too long or already have default
    setTimeout(function() {
        if ($('#jobs-results-container').is(':empty')) {
            filterJobs(1);
        }
    }, 2000);
});
</script>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-search-page jobs-transparent-bg">
    <?php if ( is_front_page() || get_the_ID() == get_option('page_on_front') || (isset($is_job_homepage) && $is_job_homepage) ) : ?>
    <div class="jobs-google-header">
        <img src="<?php echo esc_url( get_option( 'jobs_site_logo' ) ); ?>" alt="Site Logo" class="jobs-main-logo">
    </div>
    <?php endif; ?>

    <div class="jobs-search-engine">
        <form id="jobs-search-form" action="" method="GET">
            <input type="text" name="job_search" id="jobs-input-search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>">
            <input type="text" name="category" id="jobs-input-category" placeholder="Category">
            <input type="text" name="specialization" id="jobs-input-specialization" placeholder="Specialization">
            <input type="text" name="country" id="jobs-input-country" placeholder="Country">
            <input type="text" name="city" id="jobs-input-city" placeholder="State / City">
            <button type="submit">Search</button>
        </form>
    </div>

    <div id="jobs-results-container" class="jobs-results-grid">
        <!-- Results will be loaded here via AJAX -->
        <p class="jobs-loader" style="display:none;">Searching jobs...</p>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var currentPage = 1;

    function filterJobs(page = 1) {
        currentPage = page;
        var data = {
            action: 'jobs_filter',
            job_search: $('#jobs-input-search').val(),
            category: $('#jobs-input-category').val(),
            specialization: $('#jobs-input-specialization').val(),
            country: $('#jobs-input-country').val(),
            city: $('#jobs-input-city').val(),
            paged: page
        };

        $('.jobs-loader').show();

        $.get('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            $('#jobs-results-container').html(response);
            $('.jobs-loader').hide();
        });
    }

    $('#jobs-search-form input').on('keyup change', function() {
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

    // Initial load
    filterJobs();
});
</script>

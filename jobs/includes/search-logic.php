<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_ajax_filter_results() {
    try {
        $query = Jobs_Search_Service::filter_jobs( $_GET );

        if ( is_wp_error( $query ) ) {
            throw new Exception( $query->get_error_message() );
        }

        if ( $query->have_posts() ) {
            if ( ! isset($_GET['load_more']) ) {
                echo '<div class="jobs-results-grid">';
            }

            require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
            $count = 0;
            while ( $query->have_posts() ) {
                $query->the_post();
                include JOBS_PLUGIN_DIR . 'templates/job-card.php';

                $count++;
                if ( $count % 3 == 0 ) {
                    Jobs_Ads_Service::display_ad( 'search_results' );
                }
            }

            if ( ! isset($_GET['load_more']) ) {
                echo '</div>';
                // Hidden flag for infinite scroll
                echo '<div id="jobs-has-more" data-next-page="2" data-max-pages="' . $query->max_num_pages . '" style="display:none;"></div>';
            }
            wp_reset_postdata();
        } else {
            echo '<div class="jobs-no-results">';
            echo '<h3>No matching jobs found</h3>';
            echo '<p>We couldn\'t find any jobs matching your criteria right now. Try adjusting your search term or exploring a different specialization.</p>';
            echo '<div class="search-suggestions">';
            echo '<strong>Suggestions:</strong>';
            echo '<ul>';
            echo '<li>Check for typos in the job title.</li>';
            echo '<li>Try using more general keywords.</li>';
            echo '<li>Switch to "All Specializations" to see more local opportunities.</li>';
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }

    } catch ( Exception $e ) {
        Jobs_Activity_Service::log( get_current_user_id(), 'error', 'Search error: ' . $e->getMessage() );
        echo '<p style="color:red; text-align:center;">An error occurred during search. Please try again later.</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_jobs_filter', 'jobs_ajax_filter_results' );
add_action( 'wp_ajax_nopriv_jobs_filter', 'jobs_ajax_filter_results' );

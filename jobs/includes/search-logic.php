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
            $paged = max( 1, intval( $_GET['paged'] ?? 1 ) );
            echo '<div class="jobs-results-grid">';
            while ( $query->have_posts() ) {
                $query->the_post();
                include JOBS_PLUGIN_DIR . 'templates/job-card.php';
            }
            echo '</div>';

            // Intuitive Circular Pagination (Max 5 numbers)
            $total_pages = $query->max_num_pages;
            if ( $total_pages > 1 ) {
                echo '<div class="jobs-pagination">';

                $range = 2;
                $showitems = ($range * 2) + 1;

                if($paged > 1) echo '<a href="#" class="page-numbers prev" data-page="'.($paged - 1).'">&laquo;</a>';

                for ($i=1; $i <= $total_pages; $i++) {
                    if (1 != $total_pages && (!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $total_pages <= $showitems )) {
                        $active = ($paged == $i) ? 'active' : '';
                        echo '<a href="#" class="page-numbers '.$active.'" data-page="'.$i.'">'.$i.'</a>';
                    }
                }

                if($paged < $total_pages) echo '<a href="#" class="page-numbers next" data-page="'.($paged + 1).'">&raquo;</a>';

                echo '</div>';
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

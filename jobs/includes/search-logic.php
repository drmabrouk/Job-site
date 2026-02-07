<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_ajax_filter_results() {
    $search         = isset( $_GET['job_search'] ) ? sanitize_text_field( $_GET['job_search'] ) : '';
    $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( $_GET['specialization'] ) : '';
    $paged          = isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1;

    $user_lat       = isset( $_GET['lat'] ) ? floatval( $_GET['lat'] ) : 0;
    $user_lng       = isset( $_GET['lng'] ) ? floatval( $_GET['lng'] ) : 0;

    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => 12,
        'paged'          => $paged,
        's'              => $search,
        'post_status'    => 'publish',
        'tax_query'      => array( 'relation' => 'AND' ),
    );

    // Haversine sorting if lat/lng available
    if ( $user_lat && $user_lng ) {
        add_filter( 'posts_fields', 'jobs_search_proximity_fields' );
        add_filter( 'posts_join', 'jobs_search_proximity_join' );
        add_filter( 'posts_orderby', 'jobs_search_proximity_orderby' );
        set_query_var( 'jobs_user_lat', $user_lat );
        set_query_var( 'jobs_user_lng', $user_lng );
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    if ( $specialization ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'specialization',
            'field'    => 'slug',
            'terms'    => $specialization,
        );
    }

    $query = new WP_Query( $args );

    if ( $user_lat && $user_lng ) {
        remove_filter( 'posts_fields', 'jobs_search_proximity_fields' );
        remove_filter( 'posts_join', 'jobs_search_proximity_join' );
        remove_filter( 'posts_orderby', 'jobs_search_proximity_orderby' );
    }

    if ( $query->have_posts() ) {
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

    wp_die();
}
add_action( 'wp_ajax_jobs_filter', 'jobs_ajax_filter_results' );
add_action( 'wp_ajax_nopriv_jobs_filter', 'jobs_ajax_filter_results' );

function jobs_search_proximity_fields( $fields ) {
    global $wpdb;
    $lat = get_query_var( 'jobs_user_lat' );
    $lng = get_query_var( 'jobs_user_lng' );

    $fields .= ", ( 6371 * acos( cos( radians($lat) ) * cos( radians( mt_lat.meta_value ) ) * cos( radians( mt_lng.meta_value ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( mt_lat.meta_value ) ) ) ) AS distance";
    return $fields;
}

function jobs_search_proximity_join( $join ) {
    global $wpdb;
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt_lat ON ({$wpdb->posts}.ID = mt_lat.post_id AND mt_lat.meta_key = '_job_lat') ";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt_lng ON ({$wpdb->posts}.ID = mt_lng.post_id AND mt_lng.meta_key = '_job_lng') ";
    return $join;
}

function jobs_search_proximity_orderby( $orderby ) {
    return " distance ASC, " . $orderby;
}

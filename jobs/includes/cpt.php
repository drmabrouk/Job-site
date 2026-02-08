<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_register_cpt() {
    $labels = array(
        'name'               => 'Jobs',
        'singular_name'      => 'Job',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Job',
        'edit_item'          => 'Edit Job',
        'new_item'           => 'New Job',
        'view_item'          => 'View Job',
        'search_items'       => 'Search Jobs',
        'not_found'          => 'No jobs found',
        'not_found_in_trash' => 'No jobs found in Trash',
        'parent_item_colon'  => '',
        'menu_name'          => 'Jobs'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'job-listing' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' )
    );

    register_post_type( 'job', $args );

    // Application CPT
    register_post_type( 'application', array(
        'label'               => 'Applications',
        'public'              => false,
        'show_ui'             => true,
        'supports'            => array( 'title', 'editor', 'custom-fields' ),
        'capability_type'     => 'post',
    ) );

    // Taxonomies
    register_taxonomy( 'specialization', 'job', array(
        'label'        => 'Specialization',
        'rewrite'      => array( 'slug' => 'specialization' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'state', 'job', array(
        'label'        => 'State',
        'rewrite'      => array( 'slug' => 'state' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'country', 'job', array(
        'label'        => 'Country',
        'rewrite'      => array( 'slug' => 'country' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'city', 'job', array(
        'label'        => 'City',
        'rewrite'      => array( 'slug' => 'city' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'job_category', 'job', array(
        'label'        => 'Category',
        'rewrite'      => array( 'slug' => 'job-category' ),
        'hierarchical' => true,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'jobs_register_cpt' );

/**
 * Insert 50 default specializations
 */
function jobs_insert_default_specializations() {
    $specializations = array(
        'Software Development', 'Data Science', 'Artificial Intelligence', 'Cyber Security', 'Cloud Computing',
        'DevOps Engineering', 'Mobile App Development', 'Web Design', 'UI/UX Design', 'Graphic Design',
        'Digital Marketing', 'Social Media Management', 'Content Writing', 'Search Engine Optimization', 'Project Management',
        'Product Management', 'Business Analysis', 'Financial Accounting', 'Investment Banking', 'Human Resources',
        'Recruitment', 'Sales & Business Development', 'Customer Support', 'Data Entry', 'Quality Assurance',
        'Network Administration', 'Database Management', 'IT Support', 'Game Development', 'Embedded Systems',
        'Mechanical Engineering', 'Electrical Engineering', 'Civil Engineering', 'Architecture', 'Interior Design',
        'Legal Services', 'Healthcare & Medicine', 'Nursing', 'Pharmacy', 'Education & Teaching',
        'Logistics & Supply Chain', 'Manufacturing', 'Real Estate', 'Hospitality & Tourism', 'Media & Journalism',
        'Public Relations', 'Event Planning', 'Photography & Videography', 'Translation & Interpretation', 'Veterinary Science'
    );

    foreach ( $specializations as $spec ) {
        if ( ! term_exists( $spec, 'specialization' ) ) {
            wp_insert_term( $spec, 'specialization' );
        }
    }
}

// Database setup for internal messaging and notifications
function jobs_database_setup() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_messages = $wpdb->prefix . 'jobs_messages';
    $sql_messages = "CREATE TABLE $table_messages (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        sender_id bigint(20) NOT NULL,
        receiver_id bigint(20) NOT NULL,
        message text NOT NULL,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        is_read tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $table_notifications = $wpdb->prefix . 'jobs_notifications';
    $sql_notifications = "CREATE TABLE $table_notifications (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        content text NOT NULL,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        is_read tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $table_activity = $wpdb->prefix . 'jobs_activity_log';
    $sql_activity = "CREATE TABLE $table_activity (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        type varchar(50) NOT NULL,
        message text NOT NULL,
        time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_messages );
    dbDelta( $sql_notifications );
    dbDelta( $sql_activity );
}

// Automated Job Archiving
function jobs_schedule_archiving() {
    if ( ! wp_next_scheduled( 'jobs_daily_archiving' ) ) {
        wp_schedule_event( time(), 'daily', 'jobs_daily_archiving' );
    }
}
add_action( 'wp', 'jobs_schedule_archiving' );

function jobs_do_automated_archiving() {
    $archive_days = get_option( 'jobs_archive_days', 30 );
    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'date_query'     => array(
            array(
                'column' => 'post_date_gmt',
                'before' => $archive_days . ' days ago',
            ),
        ),
    );
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            wp_update_post( array(
                'ID'          => get_the_ID(),
                'post_status' => 'private' // Or custom 'archived' status
            ) );
        }
    }
    wp_reset_postdata();

    // Inactive User Cleanup (10 days)
    $inactive_days = 10;
    $threshold = time() - ( $inactive_days * DAY_IN_SECONDS );
    $users = get_users( array(
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => '_last_activity',
                'value'   => $threshold,
                'compare' => '<'
            ),
            array(
                'key'     => '_last_activity',
                'compare' => 'NOT EXISTS'
            )
        ),
        'date_query' => array(
            'before' => $inactive_days . ' days ago',
        ),
        'fields' => 'ID'
    ) );

    if ( ! empty( $users ) ) {
        require_once( ABSPATH . 'wp-admin/includes/user.php' );
        foreach ( $users as $user_id ) {
            $u = get_userdata( $user_id );
            if ( ! $u ) continue;
            // Prevent deleting admins
            if ( in_array( 'administrator', $u->roles ) || in_array( 'system_admin', $u->roles ) ) continue;
            wp_delete_user( $user_id );
        }
    }
}
add_action( 'jobs_daily_archiving', 'jobs_do_automated_archiving' );

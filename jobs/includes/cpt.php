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

    // Taxonomies
    register_taxonomy( 'specialization', 'job', array(
        'label'        => 'Specialization',
        'rewrite'      => array( 'slug' => 'specialization' ),
        'hierarchical' => true,
    ) );

    register_taxonomy( 'country', 'job', array(
        'label'        => 'Country',
        'rewrite'      => array( 'slug' => 'country' ),
        'hierarchical' => true,
    ) );

    register_taxonomy( 'city', 'job', array(
        'label'        => 'City',
        'rewrite'      => array( 'slug' => 'city' ),
        'hierarchical' => true,
    ) );

    register_taxonomy( 'job_category', 'job', array(
        'label'        => 'Category',
        'rewrite'      => array( 'slug' => 'job-category' ),
        'hierarchical' => true,
    ) );
}
add_action( 'init', 'jobs_register_cpt' );

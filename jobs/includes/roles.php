<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_create_roles() {
    add_role( 'job_seeker', 'Job Seeker', array(
        'read' => true,
        'apply_jobs' => true
    ) );
    add_role( 'employer', 'Employer', array(
        'read' => true,
        'post_jobs' => true,
        'view_applications' => true
    ) );
    add_role( 'reviewer', 'Reviewer', array(
        'read' => true,
        'review_jobs' => true,
        'post_jobs' => true
    ) );
    add_role( 'system_admin', 'System Administrator', array(
        'read' => true,
        'manage_options' => true,
        'post_jobs' => true,
        'review_jobs' => true,
        'manage_jobs_users' => true
    ) );

    // Ensure regular administrators do NOT have system_admin specific capabilities if desired
    // $admin = get_role( 'administrator' );
}

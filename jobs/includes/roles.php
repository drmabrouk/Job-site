<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_create_roles() {
    add_role( 'job_seeker', 'Job Seeker', array( 'read' => true ) );
    add_role( 'employer', 'Employer', array( 'read' => true ) );
    add_role( 'reviewer', 'Reviewer', array( 'read' => true ) );
    add_role( 'system_admin', 'System Administrator', array( 'read' => true, 'manage_options' => true ) );
}

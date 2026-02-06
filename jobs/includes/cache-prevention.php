<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_prevent_caching() {
    if ( ! is_admin() ) {
        header( "Cache-Control: no-cache, must-revalidate, max-age=0" );
        header( "Expires: Wed, 11 Jan 1984 05:00:00 GMT" );
        header( "Pragma: no-cache" );
    }
}
add_action( 'send_headers', 'jobs_prevent_caching' );

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-search-page jobs-transparent-bg">
    <div class="jobs-google-header">
        <img src="<?php echo esc_url( get_option( 'jobs_site_logo' ) ); ?>" alt="Site Logo" class="jobs-main-logo">
    </div>

    <div class="jobs-search-engine">
        <form action="" method="GET">
            <input type="text" name="job_search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>">
            <input type="text" name="specialization" placeholder="Specialization">
            <input type="text" name="country" placeholder="Country">
            <input type="text" name="city" placeholder="City">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

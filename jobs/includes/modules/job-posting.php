<?php
/**
 * Module: Job Posting
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-module-content" id="jobs-posting-module">
    <h3>Post a New Job</h3>
    <form id="jobs-post-job-form" method="POST">
        <?php wp_nonce_field( 'jobs_post_job', 'jobs_post_nonce' ); ?>

        <div class="form-group">
            <label>Job Title</label>
            <input type="text" name="job_title" required placeholder="e.g. Senior Software Engineer">
        </div>

        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" required placeholder="Your Company">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="job_description" required style="width:100%; height: 150px;"></textarea>
        </div>

        <div class="form-row" style="display:flex; gap: 10px;">
            <div class="form-group" style="flex:1;">
                <label>Specialization</label>
                <input type="text" name="specialization" placeholder="e.g. IT, Finance">
            </div>
            <div class="form-group" style="flex:1;">
                <label>Category</label>
                <input type="text" name="category" placeholder="e.g. Remote, Full-time">
            </div>
        </div>

        <div class="form-row" style="display:flex; gap: 10px;">
            <div class="form-group" style="flex:1;">
                <label>Country</label>
                <input type="text" name="country" placeholder="e.g. USA">
            </div>
            <div class="form-group" style="flex:1;">
                <label>City / State</label>
                <input type="text" name="city" placeholder="e.g. New York">
            </div>
        </div>

        <div class="form-actions" style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" name="jobs_submit_job" class="jobs-btn">Submit for Review</button>
            <button type="button" id="jobs-save-draft-btn" class="jobs-btn" style="background:#666;">Save as Draft</button>
        </div>
    </form>
    <div id="jobs-post-status"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#jobs-post-job-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize() + '&action=jobs_post_job_handler';

        $('#jobs-post-status').html('<p>Submitting...</p>');

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                $('#jobs-post-status').html('<p style="color: green;">' + response.data + '</p>');
                form[0].reset();
            } else {
                $('#jobs-post-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    $('#jobs-save-draft-btn').on('click', function() {
        var form = $('#jobs-post-job-form');
        var data = form.serialize() + '&action=jobs_post_job_handler&is_draft=1';

        $('#jobs-post-status').html('<p>Saving draft...</p>');

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                $('#jobs-post-status').html('<p style="color: blue;">Draft saved successfully.</p>');
            } else {
                $('#jobs-post-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });
});
</script>

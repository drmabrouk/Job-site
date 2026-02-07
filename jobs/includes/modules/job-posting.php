<?php
/**
 * Module: Job Posting
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-module-content" id="jobs-posting-module">
    <div style="margin-bottom: 25px;">
        <h3 style="margin: 0;">Create Opportunity</h3>
        <p style="font-size: 0.9em; color: #64748b;">Post a new listing and find the best talent.</p>
    </div>

    <form id="jobs-post-job-form" method="POST" style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
        <?php wp_nonce_field( 'jobs_post_job', 'jobs_post_nonce' ); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="grid-column: span 2;">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Job Title</label>
                <input type="text" name="job_title" required placeholder="e.g. Senior Software Engineer" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Company Name</label>
                <input type="text" name="company_name" required placeholder="Your Company" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Logo URL</label>
                <input type="text" name="company_logo" placeholder="https://example.com/logo.png" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Salary Range</label>
                <input type="text" name="job_salary" placeholder="e.g. 5000 - 7000" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Currency</label>
                <input type="text" name="job_currency" placeholder="e.g. USD" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Detailed Description</label>
            <textarea name="job_description" required style="width:100%; height: 180px; border-radius: 12px; border: 1px solid #cbd5e1; padding: 15px;"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Specialization</label>
                <select name="specialization" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px; height: 48px;">
                    <option value="">Select Specialization</option>
                    <?php
                    $specs = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                    foreach ($specs as $spec) {
                        echo '<option value="'.esc_attr($spec->slug).'">'.esc_html($spec->name).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Job Type / Category</label>
                <input type="text" name="category" placeholder="e.g. Full-time, Remote" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Country</label>
                <input type="text" name="country" placeholder="e.g. USA" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">City</label>
                <input type="text" name="city" placeholder="e.g. San Francisco" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>
        </div>

        <div class="form-actions" style="display:flex; gap:15px; margin-top:30px;">
            <button type="submit" name="jobs_submit_job" class="jobs-btn" style="flex: 2; padding: 15px;">Publish Job Listing</button>
            <button type="button" id="jobs-save-draft-btn" class="jobs-btn" style="flex: 1; background:#64748b; padding: 15px;">Save Draft</button>
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

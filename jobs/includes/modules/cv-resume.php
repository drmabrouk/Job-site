<?php
/**
 * Module: CV / Resume
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$cv_data = get_user_meta( $current_user_id, 'jobs_cv_data', true ) ?: array();
?>
<div class="jobs-module-content" id="jobs-cv-module">
    <h3>My Professional CV</h3>
    <form id="jobs-cv-form" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <div class="cv-section" style="margin-bottom: 20px;">
            <h4>Education</h4>
            <textarea name="cv_education" style="width:100%; height: 80px;"><?php echo esc_textarea($cv_data['education'] ?? ''); ?></textarea>
        </div>

        <div class="cv-section" style="margin-bottom: 20px;">
            <h4>Experience</h4>
            <textarea name="cv_experience" style="width:100%; height: 100px;"><?php echo esc_textarea($cv_data['experience'] ?? ''); ?></textarea>
        </div>

        <div class="cv-section" style="margin-bottom: 20px;">
            <h4>Skills</h4>
            <input type="text" name="cv_skills" value="<?php echo esc_attr($cv_data['skills'] ?? ''); ?>" style="width:100%;" placeholder="e.g. PHP, JavaScript, Management">
        </div>

        <button type="submit" name="jobs_submit_cv" class="jobs-btn">Save CV</button>
    </form>
    <div id="jobs-cv-status"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#jobs-cv-form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_cv_handler';

        $('#jobs-cv-status').html('<p>Saving...</p>');

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                $('#jobs-cv-status').html('<p style="color: green;">CV saved! Updates will reflect in all applications.</p>');
            } else {
                $('#jobs-cv-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });
});
</script>

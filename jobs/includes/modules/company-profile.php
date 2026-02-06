<?php
/**
 * Module: Company Profile
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$company_data = get_user_meta( $current_user_id, 'jobs_company_data', true ) ?: array();
$profile_link = jobs_get_profile_link( $current_user_id );
?>
<div class="jobs-module-content" id="jobs-company-module">
    <h3>Company Profile Management</h3>

    <div class="jobs-share-link-box" style="margin-bottom: 20px; padding: 15px; border: 1px dashed var(--jobs-primary-color); border-radius: 8px;">
        <strong>Company Public Profile Link:</strong><br>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank"><?php echo esc_html( $profile_link ); ?></a>
    </div>

    <form id="jobs-company-form" method="POST">
        <?php wp_nonce_field( 'jobs_save_company', 'jobs_company_nonce' ); ?>

        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" value="<?php echo esc_attr($company_data['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Company Logo URL</label>
            <input type="text" name="company_logo" value="<?php echo esc_attr($company_data['logo'] ?? ''); ?>" placeholder="https://example.com/logo.png">
        </div>

        <div class="form-group">
            <label>Business Details / About</label>
            <textarea name="company_details" style="width:100%; height: 120px;"><?php echo esc_textarea($company_data['details'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="company_address" value="<?php echo esc_attr($company_data['address'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Employee Count</label>
            <select name="company_employee_count">
                <option value="1-10" <?php selected($company_data['employee_count'] ?? '', '1-10'); ?>>1-10</option>
                <option value="11-50" <?php selected($company_data['employee_count'] ?? '', '11-50'); ?>>11-50</option>
                <option value="51-200" <?php selected($company_data['employee_count'] ?? '', '51-200'); ?>>51-200</option>
                <option value="201-500" <?php selected($company_data['employee_count'] ?? '', '201-500'); ?>>201-500</option>
                <option value="500+" <?php selected($company_data['employee_count'] ?? '', '500+'); ?>>500+</option>
            </select>
        </div>

        <button type="submit" name="jobs_submit_company" class="jobs-btn">Save Company Profile</button>
    </form>
    <div id="jobs-company-status" style="margin-top: 10px;"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#jobs-company-form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_company_handler';

        $('#jobs-company-status').html('<p>Saving company details...</p>');

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                $('#jobs-company-status').html('<p style="color: green;">' + response.data + '</p>');
            } else {
                $('#jobs-company-status').html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });
});
</script>

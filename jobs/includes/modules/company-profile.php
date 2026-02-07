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
    <div style="margin-bottom: 30px;">
        <h3 style="margin: 0;">Company Profile</h3>
        <p style="font-size: 0.9em; color: #64748b;">Manage your organizational identity and presence.</p>
    </div>

    <div class="jobs-share-link-box" style="margin-bottom: 30px; padding: 20px; border: 1px dashed var(--jobs-primary-color); border-radius: 12px; background: rgba(29, 52, 105, 0.02);">
        <strong style="font-size: 0.85em; color: #555;">Public Profile Link:</strong><br>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank" style="font-size: 0.9em; word-break: break-all;"><?php echo esc_html( $profile_link ); ?></a>
    </div>

    <form id="jobs-company-form" method="POST" style="background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
        <?php wp_nonce_field( 'jobs_save_company', 'jobs_company_nonce' ); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Company Name</label>
                <input type="text" name="company_name" value="<?php echo esc_attr($company_data['name'] ?? ''); ?>" required style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Logo URL</label>
                <input type="text" name="company_logo" value="<?php echo esc_attr($company_data['logo'] ?? ''); ?>" placeholder="https://example.com/logo.png" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Business Details / About</label>
            <textarea name="company_details" style="width:100%; height: 120px; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;"><?php echo esc_textarea($company_data['details'] ?? ''); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Office Address</label>
                <input type="text" name="company_address" value="<?php echo esc_attr($company_data['address'] ?? ''); ?>" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.9em; margin-bottom: 8px; display: block;">Employee Count</label>
                <select name="company_employee_count" style="width:100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; height: 42px;">
                <option value="1-10" <?php selected($company_data['employee_count'] ?? '', '1-10'); ?>>1-10</option>
                <option value="11-50" <?php selected($company_data['employee_count'] ?? '', '11-50'); ?>>11-50</option>
                <option value="51-200" <?php selected($company_data['employee_count'] ?? '', '51-200'); ?>>51-200</option>
                <option value="201-500" <?php selected($company_data['employee_count'] ?? '', '201-500'); ?>>201-500</option>
                <option value="500+" <?php selected($company_data['employee_count'] ?? '', '500+'); ?>>500+</option>
            </select>
        </div>

        <button type="submit" name="jobs_submit_company" class="jobs-btn" style="width: 100%;">Update Company Information</button>
    </form>
    <div id="jobs-company-status" style="margin-top: 10px;"></div>
</div>

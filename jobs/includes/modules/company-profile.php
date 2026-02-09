<?php
/**
 * Module: Company Profile (Premium Refinement)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$company = get_user_meta( $current_user_id, 'jobs_company_data', true ) ?: array();
$profile_link = jobs_get_profile_link( $current_user_id );
$specializations_data = Jobs_Data_Service::get_specializations();
$company_types = Jobs_Data_Service::get_company_types();
$company_sizes = Jobs_Data_Service::get_company_sizes();
$environments = Jobs_Data_Service::get_work_environments();
?>
<div class="jobs-module-content" id="jobs-company-module">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.8em; color: var(--jobs-primary-color);">Company Profile Management</h3>
        <a href="<?php echo esc_url($profile_link); ?>" target="_blank" class="jobs-btn-small" style="background: #10b981;">View Employer Portfolio</a>
    </div>

    <form id="jobs-company-form" method="POST" style="background: #f8fafc; padding: 40px; border-radius: 24px; border: 1px solid #e2e8f0;">
        <?php wp_nonce_field( 'jobs_save_company', 'jobs_company_nonce' ); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Display Name (Brand)</label>
                <input type="text" name="company_name" value="<?php echo esc_attr($company['name'] ?? ''); ?>" required placeholder="e.g. Google" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Legal Company Name</label>
                <input type="text" name="legal_name" value="<?php echo esc_attr($company['legal_name'] ?? ''); ?>" placeholder="e.g. Google LLC" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Logo URL</label>
                <input type="url" name="company_logo" value="<?php echo esc_url($company['logo'] ?? ''); ?>" placeholder="https://..." style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Official Website</label>
                <input type="url" name="website" value="<?php echo esc_url($company['website'] ?? ''); ?>" placeholder="https://..." style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Industry / Sector</label>
                <select name="industry" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
                    <option value="">Select Industry</option>
                    <?php foreach(array_keys($specializations_data) as $spec): ?>
                        <option value="<?php echo $spec; ?>" <?php selected($company['industry'] ?? '', $spec); ?>><?php echo $spec; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Type</label>
                <select name="company_type" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
                    <option value="">Select Type</option>
                    <?php foreach($company_types as $t): ?>
                        <option value="<?php echo $t; ?>" <?php selected($company['company_type'] ?? '', $t); ?>><?php echo $t; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Size</label>
                <select name="company_employee_count" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
                    <option value="">Select Size</option>
                    <?php foreach($company_sizes as $s): ?>
                        <option value="<?php echo $s; ?>" <?php selected($company['employee_count'] ?? '', $s); ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Founded Year</label>
                <input type="number" name="founded_year" value="<?php echo esc_attr($company['founded_year'] ?? ''); ?>" placeholder="e.g. 2010" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Headquarters Location</label>
                <input type="text" name="company_address" value="<?php echo esc_attr($company['address'] ?? ''); ?>" placeholder="City, Country" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Work Environment</label>
                <select name="work_environment" style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;">
                    <option value="">Select Environment</option>
                    <?php foreach($environments as $e): ?>
                        <option value="<?php echo $e; ?>" <?php selected($company['work_environment'] ?? '', $e); ?>><?php echo $e; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Overview / Mission</label>
                <textarea name="company_details" style="width: 100%; height: 100px; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($company['details'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Mission Statement</label>
                <textarea name="mission" style="width: 100%; height: 80px; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($company['mission'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Company Culture & Values</label>
                <textarea name="culture" style="width: 100%; height: 80px; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($company['culture'] ?? ''); ?></textarea>
            </div>

            <div class="form-group span-2">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Benefits & Perks Offered</label>
                <textarea name="benefits" placeholder="List key benefits..." style="width: 100%; height: 80px; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($company['benefits'] ?? ''); ?></textarea>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 10px; font-weight: 700; color: #1d3469; font-size: 0.9em;">Branch Locations</label>
                <textarea name="branches" placeholder="e.g. London, UK | Dubai, UAE" style="width: 100%; height: 60px; padding: 14px; border-radius: 12px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($company['branches'] ?? ''); ?></textarea>
            </div>
        </div>

        <button type="submit" class="jobs-btn" style="margin-top: 40px; width: 100%; padding: 20px; font-size: 1.1em;">Update Corporate Identity</button>
    </form>
    <div id="jobs-company-status" style="margin-top: 20px; text-align: center;"></div>
</div>

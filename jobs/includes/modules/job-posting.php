<?php
/**
 * Module: Job Posting
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$locations = Jobs_Data_Service::get_countries_with_regions();
$currencies = Jobs_Data_Service::get_currencies();
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
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Salary Range</label>
                <input type="text" name="job_salary" placeholder="e.g. 5000 - 7000" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Currency</label>
                <select name="job_currency" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px; height: 48px;">
                    <?php foreach($currencies as $code => $sym): ?>
                        <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($code); ?> (<?php echo esc_html($sym); ?>)</option>
                    <?php endforeach; ?>
                </select>
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
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Job Category</label>
                <select name="category" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px; height: 48px;">
                    <option value="">Select Category</option>
                    <?php
                    $cats = get_terms( array( 'taxonomy' => 'job_category', 'hide_empty' => false ) );
                    foreach ($cats as $cat) {
                        echo '<option value="'.esc_attr($cat->slug).'">'.esc_html($cat->name).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Country</label>
                <select name="country" id="posting-country" style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px; height: 48px;">
                    <option value="">Select Country</option>
                    <?php foreach(array_keys($locations) as $c): ?>
                        <option value="<?php echo esc_attr($c); ?>"><?php echo esc_html(ucwords(str_replace('-', ' ', $c))); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85em; margin-bottom: 8px; display: block; color: #475569;">Region / State</label>
                <select name="city" id="posting-city" disabled style="width:100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px; height: 48px;">
                    <option value="">Select Country First</option>
                </select>
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
    const locationData = <?php echo json_encode($locations); ?>;

    $('#posting-country').on('change', function() {
        const country = $(this).val();
        const $citySelect = $('#posting-city');
        $citySelect.empty().append('<option value="">Select Region / State</option>');

        if (country && locationData[country]) {
            locationData[country].forEach(region => {
                $citySelect.append(`<option value="${region}">${region}</option>`);
            });
            $citySelect.prop('disabled', false);
        } else {
            $citySelect.prop('disabled', true);
        }
    });
});
</script>

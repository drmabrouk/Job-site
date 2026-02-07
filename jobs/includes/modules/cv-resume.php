<?php
/**
 * Module: CV / Resume (Enhanced Sequential Setup)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$cv_data = get_user_meta( $current_user_id, 'jobs_cv_data', true ) ?: array();
$profile_link = jobs_get_profile_link( $current_user_id );
?>
<div class="jobs-module-content" id="jobs-cv-module">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.8em; color: var(--jobs-primary-color);">Professional CV Setup</h3>
        <a href="<?php echo esc_url($profile_link); ?>" target="_blank" class="jobs-btn-small" style="background: #10b981;">View Public Profile</a>
    </div>

    <div class="cv-progress-bar" style="display: flex; justify-content: space-between; margin-bottom: 40px; position: relative; padding: 0 10px;">
        <div style="position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1;"></div>
        <div id="cv-progress-line" style="position: absolute; top: 15px; left: 0; width: 0%; height: 2px; background: var(--jobs-primary-color); z-index: 2; transition: width 0.4s ease;"></div>

        <div class="cv-progress-step active" data-step="0" style="z-index: 3; text-align: center;">
            <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: white; border: 2px solid var(--jobs-primary-color); margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--jobs-primary-color);">1</div>
            <span style="font-size: 0.75em; font-weight: 600; color: #64748b;">Personal</span>
        </div>
        <div class="cv-progress-step" data-step="1" style="z-index: 3; text-align: center;">
            <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: white; border: 2px solid #e2e8f0; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #94a3b8;">2</div>
            <span style="font-size: 0.75em; font-weight: 600; color: #64748b;">Professional</span>
        </div>
        <div class="cv-progress-step" data-step="2" style="z-index: 3; text-align: center;">
            <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: white; border: 2px solid #e2e8f0; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #94a3b8;">3</div>
            <span style="font-size: 0.75em; font-weight: 600; color: #64748b;">Experience</span>
        </div>
        <div class="cv-progress-step" data-step="3" style="z-index: 3; text-align: center;">
            <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: white; border: 2px solid #e2e8f0; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #94a3b8;">4</div>
            <span style="font-size: 0.75em; font-weight: 600; color: #64748b;">Skills</span>
        </div>
    </div>

    <form id="jobs-cv-form" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <!-- Step 0: Personal Info -->
        <div class="cv-step-panel active" id="cv-step-0">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="cv_nationality" value="<?php echo esc_attr(get_user_meta($current_user_id, '_nationality', true)); ?>" placeholder="e.g. Canadian">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="cv_gender">
                        <option value="">Select Gender</option>
                        <option value="male" <?php selected(get_user_meta($current_user_id, '_gender', true), 'male'); ?>>Male</option>
                        <option value="female" <?php selected(get_user_meta($current_user_id, '_gender', true), 'female'); ?>>Female</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Professional Bio</label>
                    <textarea name="cv_bio" style="height: 100px;" placeholder="Tell us about yourself..."><?php echo esc_textarea(get_user_meta($current_user_id, 'description', true)); ?></textarea>
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="button" class="jobs-btn next-cv-step" data-next="1">Next: Professional Details</button>
            </div>
        </div>

        <!-- Step 1: Professional Details -->
        <div class="cv-step-panel" id="cv-step-1" style="display:none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Highest Qualification</label>
                    <select name="cv_qualification">
                        <option value="high-school" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'high-school'); ?>>High School</option>
                        <option value="bachelor" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'bachelor'); ?>>Bachelor's Degree</option>
                        <option value="master" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'master'); ?>>Master's Degree</option>
                        <option value="phd" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'phd'); ?>>PhD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <select name="cv_specialization">
                        <?php
                        $specs = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                        foreach ($specs as $spec) {
                            echo '<option value="'.esc_attr($spec->slug).'" '.selected(get_user_meta($current_user_id, '_specialization', true), $spec->slug, false).'>'.esc_html($spec->name).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>English Level</label>
                    <select name="cv_english_level">
                        <option value="basic" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'basic'); ?>>Basic</option>
                        <option value="intermediate" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'intermediate'); ?>>Intermediate</option>
                        <option value="fluent" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'fluent'); ?>>Fluent</option>
                        <option value="native" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'native'); ?>>Native</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" name="cv_experience_years" value="<?php echo esc_attr(get_user_meta($current_user_id, '_experience', true)); ?>" placeholder="e.g. 5">
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="0">Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="2">Next: Work Experience</button>
            </div>
        </div>

        <!-- Step 2: Experience & Education -->
        <div class="cv-step-panel" id="cv-step-2" style="display:none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Work Experience</label>
                <textarea name="cv_experience" style="height: 150px;" placeholder="List your previous roles..."><?php echo esc_textarea($cv_data['experience'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Education History</label>
                <textarea name="cv_education" style="height: 120px;" placeholder="List your degrees and schools..."><?php echo esc_textarea($cv_data['education'] ?? ''); ?></textarea>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="1">Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="3">Next: Skills & Certs</button>
            </div>
        </div>

        <!-- Step 3: Skills & Certifications -->
        <div class="cv-step-panel" id="cv-step-3" style="display:none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Key Skills (Comma separated)</label>
                <input type="text" name="cv_skills" value="<?php echo esc_attr($cv_data['skills'] ?? ''); ?>" placeholder="e.g. PHP, WordPress, React">
            </div>
            <div class="form-group">
                <label>Certifications & Awards</label>
                <textarea name="cv_certifications" style="height: 120px;" placeholder="List any relevant certifications..."><?php echo esc_textarea($cv_data['certifications'] ?? ''); ?></textarea>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="2">Back</button>
                <button type="submit" class="jobs-btn" style="padding: 15px 40px;">Finalize & Save CV</button>
            </div>
        </div>
    </form>
    <div id="jobs-cv-status" style="margin-top: 20px; text-align: center;"></div>
</div>

<script>
jQuery(document).ready(function($) {
    function updateProgress(step) {
        var totalSteps = 4;
        var progress = (step / (totalSteps - 1)) * 100;
        $('#cv-progress-line').css('width', progress + '%');

        $('.cv-progress-step').each(function() {
            var s = $(this).data('step');
            if (s <= step) {
                $(this).find('.step-circle').css({
                    'background': 'var(--jobs-primary-color)',
                    'color': 'white',
                    'border-color': 'var(--jobs-primary-color)'
                });
            } else {
                $(this).find('.step-circle').css({
                    'background': 'white',
                    'color': '#94a3b8',
                    'border-color': '#e2e8f0'
                });
            }
        });
    }

    $('.next-cv-step').on('click', function() {
        var next = $(this).data('next');
        $('.cv-step-panel').hide();
        $('#cv-step-' + next).fadeIn();
        updateProgress(next);
    });

    $('.prev-cv-step').on('click', function() {
        var prev = $(this).data('prev');
        $('.cv-step-panel').hide();
        $('#cv-step-' + prev).fadeIn();
        updateProgress(prev);
    });
});
</script>

<style>
.cv-step-panel label {
    display: block;
    font-weight: 600;
    font-size: 0.85em;
    color: #475569;
    margin-bottom: 8px;
}
.cv-step-panel input, .cv-step-panel select, .cv-step-panel textarea {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: white;
    font-family: inherit;
}
.cv-step-panel textarea {
    resize: vertical;
}
</style>

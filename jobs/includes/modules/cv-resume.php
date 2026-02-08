<?php
/**
 * Module: CV / Resume (Professional Multi-step Overhaul)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$cv = get_user_meta( $current_user_id, 'jobs_cv_data_v2', true ) ?: array();
$profile_link = jobs_get_profile_link( $current_user_id );

// Predefined Options
$degrees = array('High School', "Bachelor's Degree", "Master's Degree", 'PhD', 'Diploma', 'Certification');
$job_types = array('Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship');
$proficiency = array('Beginner', 'Intermediate', 'Advanced', 'Native', 'Fluent');
$work_settings = array('Remote', 'On-site', 'Hybrid');
?>
<div class="jobs-module-content" id="jobs-cv-module-v2">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.8em; color: var(--jobs-primary-color);">Professional CV Setup</h3>
        <a href="<?php echo esc_url($profile_link); ?>" target="_blank" class="jobs-btn-small" style="background: #10b981;">View Public Profile</a>
    </div>

    <div class="cv-progress-bar" style="display: flex; justify-content: space-between; margin-bottom: 40px; position: relative; padding: 0 10px;">
        <div style="position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1;"></div>
        <div id="cv-progress-line" style="position: absolute; top: 15px; left: 0; width: 0%; height: 2px; background: var(--jobs-primary-color); z-index: 2; transition: width 0.4s ease;"></div>

        <?php
        $steps = array('Personal', 'Academic', 'Experience', 'Skills', 'Languages', 'Preferences');
        foreach($steps as $i => $step): ?>
            <div class="cv-progress-step <?php echo $i==0?'active':''; ?>" data-step="<?php echo $i; ?>" style="z-index: 3; text-align: center;">
                <div class="step-circle" style="width: 28px; height: 28px; border-radius: 50%; background: <?php echo $i==0?'var(--jobs-primary-color)':'white'; ?>; border: 2px solid <?php echo $i==0?'var(--jobs-primary-color)':'#e2e8f0'; ?>; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: <?php echo $i==0?'white':'#94a3b8'; ?>; font-size: 12px;"><?php echo $i+1; ?></div>
                <span style="font-size: 0.7em; font-weight: 600; color: #64748b;"><?php echo $step; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="jobs-cv-form-v2" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <!-- Step 0: Personal Information -->
        <div class="cv-step-panel active" id="cv-step-0">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Personal Information</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="personal[full_name]" value="<?php echo esc_attr($cv['personal']['full_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="personal[email]" value="<?php echo esc_attr($cv['personal']['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="personal[phone]" value="<?php echo esc_attr($cv['personal']['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Alternative Phone</label>
                    <input type="text" name="personal[alt_phone]" value="<?php echo esc_attr($cv['personal']['alt_phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="personal[dob]" value="<?php echo esc_attr($cv['personal']['dob'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="personal[gender]">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php selected($cv['personal']['gender'] ?? '', 'Male'); ?>>Male</option>
                        <option value="Female" <?php selected($cv['personal']['gender'] ?? '', 'Female'); ?>>Female</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Residential Address</label>
                    <input type="text" name="personal[address]" value="<?php echo esc_attr($cv['personal']['address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="personal[city]" value="<?php echo esc_attr($cv['personal']['city'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>State / Province</label>
                    <input type="text" name="personal[state]" value="<?php echo esc_attr($cv['personal']['state'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="personal[country]" value="<?php echo esc_attr($cv['personal']['country'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="personal[postal]" value="<?php echo esc_attr($cv['personal']['postal'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Residency Status</label>
                    <select name="personal[residency]">
                        <option value="Citizen" <?php selected($cv['personal']['residency'] ?? '', 'Citizen'); ?>>Citizen</option>
                        <option value="Resident" <?php selected($cv['personal']['residency'] ?? '', 'Resident'); ?>>Resident</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>LinkedIn Link</label>
                    <input type="url" name="personal[linkedin]" value="<?php echo esc_attr($cv['personal']['linkedin'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Other Portfolio Link</label>
                    <input type="url" name="personal[portfolio]" value="<?php echo esc_attr($cv['personal']['portfolio'] ?? ''); ?>">
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="button" class="jobs-btn next-cv-step" data-next="1">Academic Qualifications →</button>
            </div>
        </div>

        <!-- Step 1: Academic Qualifications -->
        <div class="cv-step-panel" id="cv-step-1" style="display:none;">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Academic Qualifications</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Highest Degree</label>
                    <select name="academic[degree_1]">
                        <option value="">Select Degree</option>
                        <?php foreach($degrees as $d): ?><option value="<?php echo $d; ?>" <?php selected($cv['academic']['degree_1'] ?? '', $d); ?>><?php echo $d; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>University / Institute</label>
                    <input type="text" name="academic[uni_1]" value="<?php echo esc_attr($cv['academic']['uni_1'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Main Specialization</label>
                    <input type="text" name="academic[spec_main]" value="<?php echo esc_attr($cv['academic']['spec_main'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Sub Specialization</label>
                    <input type="text" name="academic[spec_sub]" value="<?php echo esc_attr($cv['academic']['spec_sub'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>GPA / Grade</label>
                    <input type="text" name="academic[gpa]" value="<?php echo esc_attr($cv['academic']['gpa'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Graduation Date</label>
                    <input type="date" name="academic[grad_date]" value="<?php echo esc_attr($cv['academic']['grad_date'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Graduation Project</label>
                    <input type="text" name="academic[grad_project]" value="<?php echo esc_attr($cv['academic']['grad_project'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Academic Achievements & Courses</label>
                    <textarea name="academic[achievements]" style="height: 80px;"><?php echo esc_textarea($cv['academic']['achievements'] ?? ''); ?></textarea>
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="0">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="2">Professional Experience →</button>
            </div>
        </div>

        <!-- Step 2: Professional Experience -->
        <div class="cv-step-panel" id="cv-step-2" style="display:none;">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Professional Experience</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Previous Company</label>
                    <input type="text" name="experience[company]" value="<?php echo esc_attr($cv['experience']['company'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="experience[title]" value="<?php echo esc_attr($cv['experience']['title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="experience[start]" value="<?php echo esc_attr($cv['experience']['start'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>End Date (Keep empty if current)</label>
                    <input type="date" name="experience[end]" value="<?php echo esc_attr($cv['experience']['end'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="experience[type]">
                        <?php foreach($job_types as $t): ?><option value="<?php echo $t; ?>" <?php selected($cv['experience']['type'] ?? '', $t); ?>><?php echo $t; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Company Sector</label>
                    <input type="text" name="experience[sector]" value="<?php echo esc_attr($cv['experience']['sector'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Tasks & Responsibilities</label>
                    <textarea name="experience[tasks]" style="height: 100px;"><?php echo esc_textarea($cv['experience']['tasks'] ?? ''); ?></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Key Achievements</label>
                    <textarea name="experience[achievements]" style="height: 80px;"><?php echo esc_textarea($cv['experience']['achievements'] ?? ''); ?></textarea>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Reason for Leaving</label>
                    <input type="text" name="experience[leaving_reason]" value="<?php echo esc_attr($cv['experience']['leaving_reason'] ?? ''); ?>">
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="1">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="3">Skills & Certifications →</button>
            </div>
        </div>

        <!-- Step 3: Skills & Certifications -->
        <div class="cv-step-panel" id="cv-step-3" style="display:none;">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Skills & Certifications</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label>Core Skills (Comma separated)</label>
                    <input type="text" name="skills[core]" value="<?php echo esc_attr($cv['skills']['core'] ?? ''); ?>" placeholder="e.g. PHP, Management, React">
                </div>
                <div class="form-group">
                    <label>Technical Skills</label>
                    <input type="text" name="skills[technical]" value="<?php echo esc_attr($cv['skills']['technical'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Managerial Skills</label>
                    <input type="text" name="skills[managerial]" value="<?php echo esc_attr($cv['skills']['managerial'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column: span 2; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                    <label>Professional Certification Name</label>
                    <input type="text" name="skills[cert_name]" value="<?php echo esc_attr($cv['skills']['cert_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Issuing Authority</label>
                    <input type="text" name="skills[cert_auth]" value="<?php echo esc_attr($cv['skills']['cert_auth'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Certification Date</label>
                    <input type="date" name="skills[cert_date]" value="<?php echo esc_attr($cv['skills']['cert_date'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Training Courses</label>
                    <textarea name="skills[courses]" style="height: 60px;"><?php echo esc_textarea($cv['skills']['courses'] ?? ''); ?></textarea>
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="2">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="4">Languages →</button>
            </div>
        </div>

        <!-- Step 4: Languages -->
        <div class="cv-step-panel" id="cv-step-4" style="display:none;">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Languages</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Native Language</label>
                    <input type="text" name="languages[native]" value="<?php echo esc_attr($cv['languages']['native'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Other Language</label>
                    <input type="text" name="languages[other]" value="<?php echo esc_attr($cv['languages']['other'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Speaking Proficiency</label>
                    <select name="languages[speak]">
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['speak'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Writing Proficiency</label>
                    <select name="languages[write]">
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['write'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reading Proficiency</label>
                    <select name="languages[read]">
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['read'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Language Certificate / Score</label>
                    <input type="text" name="languages[score]" value="<?php echo esc_attr($cv['languages']['score'] ?? ''); ?>" placeholder="e.g. IELTS 7.5">
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="3">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="5">Preferences →</button>
            </div>
        </div>

        <!-- Step 5: Additional Job Preferences -->
        <div class="cv-step-panel" id="cv-step-5" style="display:none;">
            <h4 style="margin-bottom:20px; color:#1d3469; border-left: 4px solid #1d3469; padding-left: 10px;">Additional Job Preferences</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Preferred Contract Type</label>
                    <select name="preferences[contract]">
                        <?php foreach($job_types as $t): ?><option value="<?php echo $t; ?>" <?php selected($cv['preferences']['contract'] ?? '', $t); ?>><?php echo $t; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Expected Monthly Salary</label>
                    <input type="text" name="preferences[salary]" value="<?php echo esc_attr($cv['preferences']['salary'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Availability Date</label>
                    <input type="date" name="preferences[availability]" value="<?php echo esc_attr($cv['preferences']['availability'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Work Location Flexibility</label>
                    <select name="preferences[flexibility]">
                        <?php foreach($work_settings as $s): ?><option value="<?php echo $s; ?>" <?php selected($cv['preferences']['flexibility'] ?? '', $s); ?>><?php echo $s; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Willing to Relocate?</label>
                    <select name="preferences[relocate]">
                        <option value="Yes" <?php selected($cv['preferences']['relocate'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['relocate'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Willing to Travel?</label>
                    <select name="preferences[travel]">
                        <option value="Yes" <?php selected($cv['preferences']['travel'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['travel'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Willing to Work Overtime?</label>
                    <select name="preferences[overtime]">
                        <option value="Yes" <?php selected($cv['preferences']['overtime'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['overtime'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="4">← Back</button>
                <button type="submit" class="jobs-btn" style="padding: 15px 40px;">Complete Profile Setup</button>
            </div>
        </div>
    </form>
    <div id="jobs-cv-status-v2" style="margin-top: 20px; text-align: center;"></div>
</div>

<script>
jQuery(document).ready(function($) {
    function updateProgress(step) {
        var totalSteps = 6;
        var progress = (step / (totalSteps - 1)) * 100;
        $('#cv-progress-line').css('width', progress + '%');

        $('.cv-progress-step').each(function() {
            var s = $(this).data('step');
            if (s <= step) {
                $(this).addClass('active').find('.step-circle').css({
                    'background': 'var(--jobs-primary-color)',
                    'color': 'white',
                    'border-color': 'var(--jobs-primary-color)'
                });
            } else {
                $(this).removeClass('active').find('.step-circle').css({
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

    $('#jobs-cv-form-v2').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_cv_handler_v2';
        var $status = $('#jobs-cv-status-v2');
        $status.html('<p style="color:#666;">Saving your professional profile...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $status.html('<p style="color:#16a34a; font-weight:600;">✓ Profile updated successfully! Redirecting to your public view...</p>');
                setTimeout(function() { window.location.href = "<?php echo $profile_link; ?>"; }, 2000);
            } else {
                $status.html('<p style="color:#ef4444;">Error: ' + response.data + '</p>');
            }
        });
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
    background: #fff;
    font-family: inherit;
    transition: border-color 0.2s;
}
.cv-step-panel input:focus, .cv-step-panel select:focus, .cv-step-panel textarea:focus {
    border-color: var(--jobs-primary-color);
    outline: none;
}
.cv-step-panel textarea { resize: vertical; }
</style>

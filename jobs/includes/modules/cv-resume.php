<?php
/**
 * Module: General Account Data Update (Professional Multi-entry Overhaul)
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

// Normalize multi-entry fields
$academic_list = !empty($cv['academic']) && is_array($cv['academic']) && isset($cv['academic'][0]) ? $cv['academic'] : array($cv['academic'] ?? array());
$experience_list = !empty($cv['experience']) && is_array($cv['experience']) && isset($cv['experience'][0]) ? $cv['experience'] : array($cv['experience'] ?? array());
$locations = Jobs_Data_Service::get_countries_with_regions();
$skills_list = Jobs_Data_Service::get_skills();
$currencies = Jobs_Data_Service::get_currencies();
$specializations_data = Jobs_Data_Service::get_specializations();
?>
<div class="jobs-module-content" id="jobs-cv-module-v3">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.8em; color: var(--jobs-primary-color);">General Account Data Update</h3>
        <a href="<?php echo esc_url($profile_link); ?>" target="_blank" class="jobs-btn-small" style="background: #10b981;">View Public Portfolio</a>
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

    <form id="jobs-cv-form-v3" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <!-- Step 0: Personal Information -->
        <div class="cv-step-panel active" id="cv-step-0">
            <h4 class="step-title">Personal Information</h4>
            <div class="grid-2">
                <div class="form-group">
                    <input type="text" name="personal[full_name]" value="<?php echo esc_attr($cv['personal']['full_name'] ?? ''); ?>" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="personal[email]" value="<?php echo esc_attr($cv['personal']['email'] ?? ''); ?>" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="personal[phone]" value="<?php echo esc_attr($cv['personal']['phone'] ?? ''); ?>" class="jobs-intl-phone" placeholder="Phone Number" style="width: 100%;">
                </div>
                <div class="form-group">
                    <input type="text" name="personal[alt_phone]" value="<?php echo esc_attr($cv['personal']['alt_phone'] ?? ''); ?>" placeholder="Alternative Phone">
                </div>
                <div class="form-group">
                    <input type="date" name="personal[dob]" value="<?php echo esc_attr($cv['personal']['dob'] ?? ''); ?>" placeholder="Date of Birth">
                </div>
                <div class="form-group">
                    <select name="personal[gender]">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php selected($cv['personal']['gender'] ?? '', 'Male'); ?>>Male</option>
                        <option value="Female" <?php selected($cv['personal']['gender'] ?? '', 'Female'); ?>>Female</option>
                    </select>
                </div>
                <div class="form-group span-2">
                    <input type="text" name="personal[address]" value="<?php echo esc_attr($cv['personal']['address'] ?? ''); ?>" placeholder="Residential Address">
                </div>
                <div class="form-group">
                    <select name="personal[country]" id="cv-country">
                        <option value="">Select Country</option>
                        <?php foreach(array_keys($locations) as $c): ?>
                            <option value="<?php echo esc_attr($c); ?>" <?php selected($cv['personal']['country'] ?? '', $c); ?>><?php echo esc_html(ucwords(str_replace('-', ' ', $c))); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="personal[state]" id="cv-region" <?php echo empty($cv['personal']['country']) ? 'disabled' : ''; ?>>
                        <option value="">Select Region / State</option>
                        <?php
                        if(!empty($cv['personal']['country']) && isset($locations[$cv['personal']['country']])) {
                            foreach($locations[$cv['personal']['country']] as $reg) {
                                echo '<option value="'.esc_attr($reg).'" '.selected($cv['personal']['state'] ?? '', $reg, false).'>'.esc_html($reg).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="personal[city]" value="<?php echo esc_attr($cv['personal']['city'] ?? ''); ?>" placeholder="Specific City / District">
                </div>
                <div class="form-group">
                    <input type="text" name="personal[postal]" value="<?php echo esc_attr($cv['personal']['postal'] ?? ''); ?>" placeholder="Postal Code">
                </div>
                <div class="form-group">
                    <select name="personal[residency]">
                        <option value="" disabled <?php echo empty($cv['personal']['residency']) ? 'selected':''; ?>>Residency Status</option>
                        <option value="Citizen" <?php selected($cv['personal']['residency'] ?? '', 'Citizen'); ?>>Citizen</option>
                        <option value="Resident" <?php selected($cv['personal']['residency'] ?? '', 'Resident'); ?>>Resident</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Specialization</label>
                    <select name="personal[specialization]" id="cv-specialization">
                        <option value="">Select Specialization</option>
                        <?php foreach(array_keys($specializations_data) as $spec): ?>
                            <option value="<?php echo esc_attr($spec); ?>" <?php selected(get_user_meta($current_user_id, '_specialization', true), $spec); ?>><?php echo esc_html($spec); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Specific Profession</label>
                    <select name="personal[profession]" id="cv-profession" <?php echo empty(get_user_meta($current_user_id, '_specialization', true)) ? 'disabled' : ''; ?>>
                        <option value="">Select Profession</option>
                        <?php
                        $current_spec = get_user_meta($current_user_id, '_specialization', true);
                        if($current_spec && isset($specializations_data[$current_spec])) {
                            foreach($specializations_data[$current_spec] as $p) {
                                echo '<option value="'.esc_attr($p).'" '.selected(get_user_meta($current_user_id, '_profession', true), $p, false).'>'.esc_html($p).'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="step-nav">
                <button type="button" class="jobs-btn next-cv-step" data-next="1">Academic Qualifications →</button>
            </div>
        </div>

        <!-- Step 1: Academic Qualifications (Multi) -->
        <div class="cv-step-panel" id="cv-step-1" style="display:none;">
            <h4 class="step-title">Academic Qualifications</h4>
            <div id="academic-repeater">
                <?php foreach($academic_list as $index => $item): ?>
                <div class="repeater-item academic-item">
                    <div class="grid-2">
                        <div class="form-group">
                            <select name="academic[<?php echo $index; ?>][degree]">
                                <option value="">Select Degree</option>
                                <?php foreach($degrees as $d): ?><option value="<?php echo $d; ?>" <?php selected($item['degree'] ?? '', $d); ?>><?php echo $d; ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="academic[<?php echo $index; ?>][uni]" value="<?php echo esc_attr($item['uni'] ?? ''); ?>" placeholder="University / Institute">
                        </div>
                        <div class="form-group">
                            <input type="text" name="academic[<?php echo $index; ?>][spec_main]" value="<?php echo esc_attr($item['spec_main'] ?? ''); ?>" placeholder="Main Specialization">
                        </div>
                        <div class="form-group">
                            <input type="text" name="academic[<?php echo $index; ?>][spec_sub]" value="<?php echo esc_attr($item['spec_sub'] ?? ''); ?>" placeholder="Sub Specialization">
                        </div>
                        <div class="form-group">
                            <input type="text" name="academic[<?php echo $index; ?>][gpa]" value="<?php echo esc_attr($item['gpa'] ?? ''); ?>" placeholder="GPA / Grade">
                        </div>
                        <div class="form-group">
                            <input type="date" name="academic[<?php echo $index; ?>][grad_date]" value="<?php echo esc_attr($item['grad_date'] ?? ''); ?>" placeholder="Graduation Date">
                        </div>
                        <div class="form-group span-2">
                            <input type="text" name="academic[<?php echo $index; ?>][grad_project]" value="<?php echo esc_attr($item['grad_project'] ?? ''); ?>" placeholder="Graduation Project Title">
                        </div>
                        <div class="form-group span-2">
                            <textarea name="academic[<?php echo $index; ?>][achievements]" placeholder="Academic Achievements & Courses"><?php echo esc_textarea($item['achievements'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <?php if($index > 0): ?><button type="button" class="remove-repeater">Remove</button><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jobs-btn-minimal add-repeater" data-type="academic">+ Add Another Qualification</button>
            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="0">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="2">Professional Experience →</button>
            </div>
        </div>

        <!-- Step 2: Professional Experience (Multi) -->
        <div class="cv-step-panel" id="cv-step-2" style="display:none;">
            <h4 class="step-title">Professional Experience</h4>
            <div id="experience-repeater">
                <?php foreach($experience_list as $index => $item): ?>
                <div class="repeater-item experience-item">
                    <div class="grid-2">
                        <div class="form-group" style="position: relative;">
                            <input type="text" name="experience[<?php echo $index; ?>][company]" value="<?php echo esc_attr($item['company'] ?? ''); ?>" class="employer-suggestion-cv" placeholder="Company Name" autocomplete="off">
                            <div class="suggestions-list employer-suggestions-cv-list"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="experience[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Job Title">
                        </div>
                        <div class="form-group">
                            <input type="date" name="experience[<?php echo $index; ?>][start]" value="<?php echo esc_attr($item['start'] ?? ''); ?>" placeholder="Start Date">
                        </div>
                        <div class="form-group">
                            <input type="date" name="experience[<?php echo $index; ?>][end]" value="<?php echo esc_attr($item['end'] ?? ''); ?>" placeholder="End Date (Blank if Current)">
                        </div>
                        <div class="form-group">
                            <select name="experience[<?php echo $index; ?>][type]">
                                <option value="">Job Type</option>
                                <?php foreach($job_types as $t): ?><option value="<?php echo $t; ?>" <?php selected($item['type'] ?? '', $t); ?>><?php echo $t; ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="experience[<?php echo $index; ?>][sector]" value="<?php echo esc_attr($item['sector'] ?? ''); ?>" placeholder="Company Sector">
                        </div>
                        <div class="form-group span-2">
                            <textarea name="experience[<?php echo $index; ?>][tasks]" placeholder="Tasks & Responsibilities"><?php echo esc_textarea($item['tasks'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group span-2">
                            <textarea name="experience[<?php echo $index; ?>][achievements]" placeholder="Key Achievements"><?php echo esc_textarea($item['achievements'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <?php if($index > 0): ?><button type="button" class="remove-repeater">Remove</button><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jobs-btn-minimal add-repeater" data-type="experience">+ Add Another Experience</button>
            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="1">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="3">Skills & Certifications →</button>
            </div>
        </div>

        <!-- Step 3: Skills & Certifications -->
        <div class="cv-step-panel" id="cv-step-3" style="display:none;">
            <h4 class="step-title">Skills & Certifications</h4>
            <div class="grid-2">
                <div class="form-group span-2" style="position: relative;">
                    <input type="text" id="cv-skills-autocomplete" name="skills[core]" value="<?php echo esc_attr($cv['skills']['core'] ?? ''); ?>" placeholder="Key Skills (Type to see suggestions)" autocomplete="off">
                    <div id="cv-skills-suggestions" class="suggestions-list"></div>
                </div>
                <div class="form-group">
                    <input type="text" name="skills[technical]" value="<?php echo esc_attr($cv['skills']['technical'] ?? ''); ?>" placeholder="Technical Skills">
                </div>
                <div class="form-group">
                    <input type="text" name="skills[managerial]" value="<?php echo esc_attr($cv['skills']['managerial'] ?? ''); ?>" placeholder="Managerial Skills">
                </div>
                <div class="form-group span-2" style="border-top: 1px solid #f1f5f9; padding-top: 15px;">
                    <input type="text" name="skills[cert_name]" value="<?php echo esc_attr($cv['skills']['cert_name'] ?? ''); ?>" placeholder="Professional Certification Name">
                </div>
                <div class="form-group">
                    <input type="text" name="skills[cert_auth]" value="<?php echo esc_attr($cv['skills']['cert_auth'] ?? ''); ?>" placeholder="Issuing Authority">
                </div>
                <div class="form-group">
                    <input type="date" name="skills[cert_date]" value="<?php echo esc_attr($cv['skills']['cert_date'] ?? ''); ?>" placeholder="Certification Date">
                </div>
                <div class="form-group span-2">
                    <textarea name="skills[courses]" placeholder="Training Courses"><?php echo esc_textarea($cv['skills']['courses'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="2">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="4">Languages →</button>
            </div>
        </div>

        <!-- Step 4: Languages -->
        <div class="cv-step-panel" id="cv-step-4" style="display:none;">
            <h4 class="step-title">Languages</h4>
            <div class="grid-2">
                <div class="form-group">
                    <input type="text" name="languages[native]" value="<?php echo esc_attr($cv['languages']['native'] ?? ''); ?>" placeholder="Native Language">
                </div>
                <div class="form-group">
                    <input type="text" name="languages[other]" value="<?php echo esc_attr($cv['languages']['other'] ?? ''); ?>" placeholder="Other Language">
                </div>
                <div class="form-group">
                    <select name="languages[speak]">
                        <option value="">Speaking Proficiency</option>
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['speak'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="languages[write]">
                        <option value="">Writing Proficiency</option>
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['write'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="languages[read]">
                        <option value="">Reading Proficiency</option>
                        <?php foreach($proficiency as $p): ?><option value="<?php echo $p; ?>" <?php selected($cv['languages']['read'] ?? '', $p); ?>><?php echo $p; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="languages[score]" value="<?php echo esc_attr($cv['languages']['score'] ?? ''); ?>" placeholder="Language Certificate / Score (e.g. IELTS 7.5)">
                </div>
            </div>
            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="3">← Back</button>
                <button type="button" class="jobs-btn next-cv-step" data-next="5">Preferences →</button>
            </div>
        </div>

        <!-- Step 5: Additional Job Preferences -->
        <div class="cv-step-panel" id="cv-step-5" style="display:none;">
            <h4 class="step-title">Additional Job Preferences</h4>
            <div class="grid-2">
                <div class="form-group">
                    <select name="preferences[contract]">
                        <option value="">Preferred Contract Type</option>
                        <?php foreach($job_types as $t): ?><option value="<?php echo $t; ?>" <?php selected($cv['preferences']['contract'] ?? '', $t); ?>><?php echo $t; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <div style="display: flex; gap: 10px;">
                        <select name="preferences[currency]" style="width: 100px; flex-shrink: 0;">
                            <?php foreach($currencies as $code => $sym): ?>
                                <option value="<?php echo esc_attr($code); ?>" <?php selected($cv['preferences']['currency'] ?? 'USD', $code); ?>><?php echo esc_html($code); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="preferences[salary]" value="<?php echo esc_attr($cv['preferences']['salary'] ?? ''); ?>" placeholder="Expected Monthly Salary" style="flex: 1;">
                    </div>
                </div>
                <div class="form-group">
                    <input type="date" name="preferences[availability]" value="<?php echo esc_attr($cv['preferences']['availability'] ?? ''); ?>" placeholder="Availability Date">
                </div>
                <div class="form-group">
                    <select name="preferences[flexibility]">
                        <option value="">Work Location Flexibility</option>
                        <?php foreach($work_settings as $s): ?><option value="<?php echo $s; ?>" <?php selected($cv['preferences']['flexibility'] ?? '', $s); ?>><?php echo $s; ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="preferences[relocate]">
                        <option value="">Willing to Relocate?</option>
                        <option value="Yes" <?php selected($cv['preferences']['relocate'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['relocate'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="preferences[travel]">
                        <option value="">Willing to Travel?</option>
                        <option value="Yes" <?php selected($cv['preferences']['travel'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['travel'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="preferences[overtime]">
                        <option value="">Willing to Work Overtime?</option>
                        <option value="Yes" <?php selected($cv['preferences']['overtime'] ?? '', 'Yes'); ?>>Yes</option>
                        <option value="No" <?php selected($cv['preferences']['overtime'] ?? '', 'No'); ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="4">← Back</button>
                <button type="submit" class="jobs-btn" style="padding: 15px 40px;">Publish My Portfolio</button>
            </div>
        </div>
    </form>
    <div id="jobs-cv-status-v3" style="margin-top: 20px; text-align: center;"></div>
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

    // Repeater Logic
    $('.add-repeater').on('click', function() {
        var type = $(this).data('type');
        var $container = $('#' + type + '-repeater');
        var index = $container.find('.repeater-item').length;
        var $clone = $container.find('.repeater-item').first().clone();

        // Clear inputs and update names
        $clone.find('input, select, textarea').each(function() {
            var name = $(this).attr('name');
            var newName = name.replace(/\[\d+\]/, '[' + index + ']');
            $(this).attr('name', newName).val('');
        });

        // Add remove button if not present
        if($clone.find('.remove-repeater').length === 0) {
            $clone.append('<button type="button" class="remove-repeater">Remove</button>');
        }

        $clone.hide().appendTo($container).fadeIn();
    });

    $(document).on('click', '.remove-repeater', function() {
        $(this).closest('.repeater-item').fadeOut(function() { $(this).remove(); });
    });

    // Unified Phone Initialization
    $('.jobs-intl-phone').each(function() {
        window.intlTelInput(this, {
            preferredCountries: ['eg', 'ae', 'sa', 'jo', 'us', 'gb'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            separateDialCode: true,
        });
    });

    // Skills Suggestions Logic
    const skillsList = <?php echo json_encode($skills_list); ?>;
    $('#cv-skills-autocomplete').on('input', function() {
        const val = $(this).val();
        const parts = val.split(',');
        const query = parts[parts.length - 1].trim().toLowerCase();

        if (query.length < 1) {
            $('#cv-skills-suggestions').hide();
            return;
        }

        const matches = skillsList.filter(s => s.toLowerCase().includes(query));
        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 10).forEach(m => {
                html += `<div class="suggestion-item skill-suggestion-cv" data-val="${m}">${m}</div>`;
            });
            $('#cv-skills-suggestions').html(html).show();
        } else {
            $('#cv-skills-suggestions').hide();
        }
    });

    $(document).on('click', '.skill-suggestion-cv', function() {
        const skill = $(this).data('val');
        const $input = $('#cv-skills-autocomplete');
        const parts = $input.val().split(',');
        parts[parts.length - 1] = ' ' + skill;
        $input.val(parts.join(',').trim() + ', ');
        $('#cv-skills-suggestions').hide();
        $input.focus();
    });

    // Employer Suggestions Logic
    let cvSuggestionTimeout;
    $(document).on('input', '.employer-suggestion-cv', function() {
        const $input = $(this);
        const $list = $input.siblings('.employer-suggestions-cv-list');
        const query = $input.val();

        clearTimeout(cvSuggestionTimeout);
        if (query.length < 2) {
            $list.hide();
            return;
        }

        cvSuggestionTimeout = setTimeout(() => {
            $.post(jobs_vars.ajax_url, {
                action: 'jobs_suggest_employers',
                nonce: '<?php echo wp_create_nonce("jobs_main_nonce"); ?>',
                q: query
            }, function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(item => {
                        html += `<div class="suggestion-item cv-emp-suggestion" data-val="${item}">${item}</div>`;
                    });
                    $list.html(html).show();
                } else {
                    $list.hide();
                }
            });
        }, 300);
    });

    $(document).on('click', '.cv-emp-suggestion', function() {
        $(this).closest('.form-group').find('input').val($(this).data('val'));
        $('.employer-suggestions-cv-list').hide();
    });

    // Dynamic Professions Logic
    const specializationsData = <?php echo json_encode($specializations_data); ?>;
    $('#cv-specialization').on('change', function() {
        const spec = $(this).val();
        const $profSelect = $('#cv-profession');
        $profSelect.empty().append('<option value="">Select Profession</option>');

        if (spec && specializationsData[spec]) {
            specializationsData[spec].forEach(prof => {
                $profSelect.append(`<option value="${prof}">${prof}</option>`);
            });
            $profSelect.prop('disabled', false);
        } else {
            $profSelect.prop('disabled', true);
        }
    });

    // Dynamic Regions Logic
    $('#cv-country').on('change', function() {
        const country = $(this).val();
        const $regionSelect = $('#cv-region');
        const locationData = <?php echo json_encode($locations); ?>;

        $regionSelect.empty().append('<option value="">Select Region / State</option>');

        if (country && locationData[country]) {
            locationData[country].forEach(region => {
                $regionSelect.append(`<option value="${region}">${region}</option>`);
            });
            $regionSelect.prop('disabled', false);
        } else {
            $regionSelect.prop('disabled', true);
        }
    });

    $('#jobs-cv-form-v3').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize() + '&action=jobs_save_cv_handler_v3';
        var $status = $('#jobs-cv-status-v3');
        $status.html('<p style="color:#666; font-weight:600;">Updating your account data and profile...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if(response.success) {
                $status.html('<div style="background:#dcfce7; color:#166534; padding:20px; border-radius:12px; font-weight:600;">✓ Account data updated successfully! Your public profile has been updated instantly. Redirecting...</div>');
                setTimeout(function() { window.location.href = "<?php echo $profile_link; ?>"; }, 2500);
            } else {
                $status.html('<p style="color:#ef4444; font-weight:600;">Error: ' + response.data + '</p>');
            }
        });
    });
});
</script>

<style>
.suggestions-list {
    position: absolute;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 100;
    display: none;
    width: 100%;
}
.suggestion-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background 0.2s;
    color: #1e293b;
    font-size: 0.9em;
    text-align: left;
}
.suggestion-item:hover { background: #f8fafc; color: #1d3469; }
.iti { width: 100%; }

.cv-step-panel .step-title {
    margin-bottom: 25px;
    color: #1d3469;
    border-left: 4px solid #1d3469;
    padding-left: 15px;
    font-size: 1.3em;
}
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.span-2 { grid-column: span 2; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 14px 18px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #fff;
    font-family: inherit;
    transition: all 0.2s;
    font-size: 0.95em;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: var(--jobs-primary-color);
    box-shadow: 0 0 0 3px rgba(29, 52, 105, 0.1);
    outline: none;
}
.form-group textarea { resize: vertical; min-height: 80px; }
.repeater-item {
    background: #f8fafc;
    padding: 25px;
    border-radius: 16px;
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
    position: relative;
}
.remove-repeater {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fee2e2;
    color: #b91c1c;
    border: none;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.75em;
    font-weight: 600;
    cursor: pointer;
}
.step-nav { margin-top: 35px; display: flex; justify-content: space-between; align-items: center; }
.add-repeater { margin-bottom: 30px; font-weight: 600; color: var(--jobs-primary-color); }
</style>

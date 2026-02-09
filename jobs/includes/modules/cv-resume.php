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
    <div class="module-v4-header">
        <h3 class="module-v4-title">Data Editing</h3>
        <a href="<?php echo esc_url($profile_link); ?>" target="_blank" class="v4-btn-view-profile">View Public Portfolio</a>
    </div>

    <div class="cv-progress-bar">
        <div style="position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1;"></div>
        <div id="cv-progress-line" style="position: absolute; top: 15px; left: 0; width: 0%; height: 2px; background: var(--jobs-primary-color); z-index: 2; transition: width 0.4s ease;"></div>

        <?php
        $steps = array('Personal', 'Academic', 'Experience', 'Proficiency', 'Languages', 'Settings');
        foreach($steps as $i => $step): ?>
            <div class="cv-progress-step <?php echo $i==0?'active':''; ?>" data-step="<?php echo $i; ?>" style="z-index: 3; text-align: center; flex: 1;">
                <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: <?php echo $i==0?'var(--jobs-primary-color)':'white'; ?>; border: 2px solid <?php echo $i==0?'var(--jobs-primary-color)':'#e2e8f0'; ?>; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: <?php echo $i==0?'white':'#94a3b8'; ?>; font-size: 13px; transition: all 0.3s ease;"><?php echo $i+1; ?></div>
                <span style="font-size: 11px; font-weight: 700; color: <?php echo $i==0?'#1d3469':'#94a3b8'; ?>; text-transform: uppercase; letter-spacing: 0.05em;"><?php echo $step; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="jobs-cv-form-v3" method="POST" class="v4-professional-form">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <!-- Step 0: Personal Information -->
        <div class="cv-step-panel active" id="cv-step-0">
            <div class="v4-identity-upload-box">
                <div class="cv-photo-upload-container">
                    <?php
                    $photo_url = get_user_meta($current_user_id, '_jobs_profile_photo', true) ?: get_avatar_url($current_user_id, array('size' => 120));
                    ?>
                    <img src="<?php echo esc_url($photo_url); ?>" id="cv-photo-preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <label for="cv-photo-input" style="position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; background: var(--jobs-primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; transition: transform 0.2s;">
                        <span class="dashicons dashicons-camera" style="font-size: 16px;"></span>
                        <input type="file" id="cv-photo-input" name="profile_photo" accept="image/*" style="display: none;">
                    </label>
                </div>
                <div>
                    <h4 style="margin: 0 0 8px; color: #1d3469; font-size: 1.4em; font-weight: 800;">Identity & Photo</h4>
                    <p style="margin: 0; font-size: 0.9em; color: #64748b; line-height: 1.5;">Upload a professional photo to strengthen your identity. A clear, well-lit headshot increases your profile visibility.</p>
                </div>
            </div>

            <h4 class="step-title">Personal Details</h4>
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
                    <select name="personal[nationality]">
                        <option value="">Select Nationality</option>
                        <?php foreach(array_keys($locations) as $c): ?>
                            <option value="<?php echo esc_attr($c); ?>" <?php selected($cv['personal']['nationality'] ?? '', $c); ?>><?php echo esc_html(ucwords(str_replace('-', ' ', $c))); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="personal[country]" id="cv-country">
                        <option value="">Country of Residence</option>
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
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Primary Specialization</label>
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
                <div class="form-group span-2">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Secondary Specializations (Hold Ctrl to select multiple)</label>
                    <select name="personal[secondary_specs][]" multiple style="height: 100px;">
                        <?php
                        $sec_specs = get_user_meta($current_user_id, '_secondary_specs', true) ?: array();
                        foreach(array_keys($specializations_data) as $spec): ?>
                            <option value="<?php echo esc_attr($spec); ?>" <?php echo in_array($spec, $sec_specs) ? 'selected' : ''; ?>><?php echo esc_html($spec); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group span-2">
                    <input type="url" name="personal[portfolio_url]" value="<?php echo esc_url($cv['personal']['portfolio_url'] ?? ''); ?>" placeholder="Portfolio / Personal Website URL">
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

        <!-- Step 3: Skills, Certs & Portfolio -->
        <div class="cv-step-panel" id="cv-step-3" style="display:none;">
            <h4 class="step-title">Skills & Certifications</h4>
            <div class="grid-2">
                <div class="form-group span-2" style="position: relative;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Key Skills (Comma separated)</label>
                    <input type="text" id="cv-skills-autocomplete" name="skills[core]" value="<?php echo esc_attr($cv['skills']['core'] ?? ''); ?>" placeholder="e.g. React, Project Management, Figma" autocomplete="off">
                    <div id="cv-skills-suggestions" class="suggestions-list"></div>
                </div>
            </div>

            <h5 style="margin: 30px 0 15px; color: #1d3469;">Professional Certifications</h5>
            <div id="certs-repeater">
                <?php
                $certs_list = !empty($cv['certs']) ? $cv['certs'] : array(array());
                foreach($certs_list as $index => $item): ?>
                <div class="repeater-item">
                    <div class="grid-2">
                        <div class="form-group"><input type="text" name="certs[<?php echo $index; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Certification Name"></div>
                        <div class="form-group"><input type="text" name="certs[<?php echo $index; ?>][auth]" value="<?php echo esc_attr($item['auth'] ?? ''); ?>" placeholder="Issuing Authority"></div>
                        <div class="form-group"><input type="date" name="certs[<?php echo $index; ?>][date]" value="<?php echo esc_attr($item['date'] ?? ''); ?>"></div>
                        <div class="form-group"><input type="text" name="certs[<?php echo $index; ?>][license]" value="<?php echo esc_attr($item['license'] ?? ''); ?>" placeholder="License Number (if any)"></div>
                    </div>
                    <?php if($index > 0): ?><button type="button" class="remove-repeater">Remove</button><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jobs-btn-minimal add-repeater" data-type="certs">+ Add Certification</button>

            <h5 style="margin: 30px 0 15px; color: #1d3469;">Portfolio / Work Samples</h5>
            <div id="portfolio-repeater">
                <?php
                $port_list = !empty($cv['portfolio']) ? $cv['portfolio'] : array(array());
                foreach($port_list as $index => $item): ?>
                <div class="repeater-item">
                    <div class="grid-2">
                        <div class="form-group"><input type="text" name="portfolio[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Project Title"></div>
                        <div class="form-group"><input type="url" name="portfolio[<?php echo $index; ?>][url]" value="<?php echo esc_attr($item['url'] ?? ''); ?>" placeholder="Project Link"></div>
                        <div class="form-group span-2"><textarea name="portfolio[<?php echo $index; ?>][desc]" placeholder="Short Description"><?php echo esc_textarea($item['desc'] ?? ''); ?></textarea></div>
                    </div>
                    <?php if($index > 0): ?><button type="button" class="remove-repeater">Remove</button><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jobs-btn-minimal add-repeater" data-type="portfolio">+ Add Work Sample</button>

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

        <!-- Step 5: Preferences & References -->
        <div class="cv-step-panel" id="cv-step-5" style="display:none;">
            <h4 class="step-title">Job Preferences & References</h4>
            <div class="grid-2">
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Availability Status</label>
                    <select name="preferences[availability_status]">
                        <?php foreach(Jobs_Data_Service::get_availability_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php selected($cv['preferences']['availability_status'] ?? '', $s); ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Preferred Employment Type</label>
                    <select name="preferences[contract]">
                        <?php foreach(Jobs_Data_Service::get_employment_types() as $t): ?>
                            <option value="<?php echo $t; ?>" <?php selected($cv['preferences']['contract'] ?? '', $t); ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Expected Monthly Salary</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="preferences[currency]" style="width: 100px; flex-shrink: 0;">
                            <?php foreach($currencies as $code => $sym): ?>
                                <option value="<?php echo esc_attr($code); ?>" <?php selected($cv['preferences']['currency'] ?? 'USD', $code); ?>><?php echo esc_html($code); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="preferences[salary]" value="<?php echo esc_attr($cv['preferences']['salary'] ?? ''); ?>" placeholder="e.g. 5000" style="flex: 1;">
                    </div>
                </div>
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.8em; color: #64748b;">Work Setting Flexibility</label>
                    <select name="preferences[flexibility]">
                        <?php foreach(Jobs_Data_Service::get_work_environments() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php selected($cv['preferences']['flexibility'] ?? '', $s); ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
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
                <div class="form-group">
                    <label style="display: block; font-weight: 700; margin-bottom: 15px; font-size: 0.85em; color: #1d3469; text-transform: uppercase; letter-spacing: 0.05em;">Public Profile Visibility</label>
                    <div style="display: flex; align-items: center; gap: 15px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <?php $v_status = get_user_meta($current_user_id, 'profile_visibility', true) ?: 'public'; ?>
                        <label class="v4-toggle-switch">
                            <input type="checkbox" name="profile_visibility" value="public" <?php checked($v_status, 'public'); ?>>
                            <span class="v4-toggle-slider"></span>
                        </label>
                        <span style="font-size: 0.95em; font-weight: 600; color: #475569;">Enable Public Search & View</span>
                    </div>
                </div>
            </div>

            <h5 style="margin: 30px 0 15px; color: #1d3469;">Professional References (Optional)</h5>
            <div id="references-repeater">
                <?php
                $ref_list = !empty($cv['references']) ? $cv['references'] : array(array());
                foreach($ref_list as $index => $item): ?>
                <div class="repeater-item">
                    <div class="grid-2">
                        <div class="form-group"><input type="text" name="references[<?php echo $index; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Reference Name"></div>
                        <div class="form-group"><input type="text" name="references[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Job Title / Relationship"></div>
                        <div class="form-group"><input type="text" name="references[<?php echo $index; ?>][company]" value="<?php echo esc_attr($item['company'] ?? ''); ?>" placeholder="Company"></div>
                        <div class="form-group"><input type="text" name="references[<?php echo $index; ?>][contact]" value="<?php echo esc_attr($item['contact'] ?? ''); ?>" placeholder="Email or Phone"></div>
                    </div>
                    <?php if($index > 0): ?><button type="button" class="remove-repeater">Remove</button><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="jobs-btn-minimal add-repeater" data-type="references">+ Add Reference</button>

            <div class="step-nav">
                <button type="button" class="jobs-btn-minimal prev-cv-step" data-prev="4">← Back</button>
                <button type="submit" class="jobs-btn" style="padding: 15px 40px;">Publish My Portfolio</button>
            </div>
        </div>
    </form>
    <div id="jobs-cv-status-v3" style="margin-top: 20px; text-align: center;"></div>
</div>

<script>
    // Module Data for JS
    var skillsList = <?php echo json_encode($skills_list); ?>;
    var specializationsData = <?php echo json_encode($specializations_data); ?>;
    var locationData = <?php echo json_encode($locations); ?>;
    var profileLink = "<?php echo $profile_link; ?>";

    jQuery(document).ready(function($) {
        // Dynamic Professions Logic (Keeping small reactive parts here for instant feedback)
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

        $('#cv-country').on('change', function() {
            const country = $(this).val();
            const $regionSelect = $('#cv-region');
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

        // Phone Initialization
        $('.jobs-intl-phone').each(function() {
            if (window.intlTelInput) {
                window.intlTelInput(this, {
                    preferredCountries: ['eg', 'ae', 'sa', 'jo', 'us', 'gb'],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
                    separateDialCode: true,
                });
            }
        });
    });
</script>

<style>
.module-v4-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 20px;
}
.module-v4-title {
    margin: 0;
    font-size: 1.8em;
    color: var(--jobs-primary-color);
    font-weight: 800;
}
.v4-btn-view-profile {
    background: #10b981;
    color: white;
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}
.v4-btn-view-profile:hover {
    background: #059669;
    transform: translateY(-1px);
}

.cv-progress-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
    position: relative;
    padding: 0 10px;
}
.cv-progress-bar::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e2e8f0;
    z-index: 1;
}

.v4-professional-form {
    background: #ffffff;
    padding: 40px;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
}

.v4-identity-upload-box {
    display: flex;
    gap: 30px;
    align-items: center;
    margin-bottom: 30px;
    background: #f8fafc;
    padding: 30px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

.cv-photo-upload-container {
    position: relative;
    width: 120px;
    height: 120px;
    flex-shrink: 0;
}

.v4-toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
    flex-shrink: 0;
}
.v4-toggle-switch input { opacity: 0; width: 0; height: 0; }
.v4-toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: .4s;
    border-radius: 34px;
}
.v4-toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .v4-toggle-slider { background-color: #10b981; }
input:checked + .v4-toggle-slider:before { transform: translateX(24px); }

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
    margin-bottom: 30px;
    color: #1d3469;
    border-left: 5px solid #1d3469;
    padding-left: 20px;
    font-size: 1.5em;
    font-weight: 800;
    letter-spacing: -0.02em;
}
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.span-2 { grid-column: span 2; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 14px 18px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    font-family: inherit;
    transition: all 0.3s ease;
    font-size: 0.95em;
    color: #1e293b;
}

.form-group input:hover, .form-group select:hover, .form-group textarea:hover {
    border-color: #cbd5e1;
    background: #ffffff;
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

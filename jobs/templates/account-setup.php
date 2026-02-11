<?php
/**
 * Template: Advanced Guided Professional Onboarding (V2)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_user_logged_in() ) {
    wp_safe_redirect( home_url( '/login/' ) );
    exit;
}

$user_id = get_current_user_id();
$user = get_userdata( $user_id );
$role = $user->roles[0] ?? 'job_seeker';
$first_name = get_user_meta($user_id, 'first_name', true) ?: $user->display_name;

$specializations = Jobs_Data_Service::get_specializations();
$locations_raw = Jobs_Data_Service::get_location_data();
$locations = Jobs_Data_Service::get_countries_with_regions();
$skills_list = Jobs_Data_Service::get_skills();

// Map countries for the select with flags
$countries_with_flags = array();
foreach($locations_raw as $group) {
    foreach($group['countries'] as $slug => $c) {
        $countries_with_flags[$slug] = array(
            'name' => $c['name'],
            'flag' => "https://flagcdn.com/w40/{$c['code']}.png"
        );
    }
}

$steps_seeker = array(
    1 => 'Identity Asset',
    2 => 'Nationality',
    3 => 'Residence',
    4 => 'Communication',
    5 => 'Professional Focus',
    6 => 'Executive Summary',
    7 => 'Academic History',
    8 => 'Recent Career',
    9 => 'Skill Set',
    10 => 'Language Assets',
    11 => 'Final Protocols'
);

$steps_employer = array(
    1 => 'Corporate Identity',
    2 => 'Industry Sector',
    3 => 'Organization Scale',
    4 => 'Global HQ',
    5 => 'Digital Presence',
    6 => 'Corporate Benefits',
    7 => 'Culture & Mission',
    8 => 'Final Protocols'
);

$active_steps = ($role === 'employer') ? $steps_employer : $steps_seeker;
$total_steps = count($active_steps);
?>

<div class="jobs-premium-setup-v2">
    <div class="setup-container">

        <!-- Welcome Header -->
        <header class="setup-v2-header">
            <div class="setup-brand">
                <?php echo do_shortcode('[jobedia_logo]'); ?>
            </div>
            <div class="setup-welcome">
                <h1>Welcome, <span style="color: #1d3469;"><?php echo esc_html($first_name); ?></span></h1>
                <p>Let's elevate your professional presence. Complete these steps to reach 100% profile strength.</p>
            </div>

            <!-- Progress Tracker -->
            <div class="setup-v2-progress">
                <div class="progress-info">
                    <span id="step-counter">Step 1 of <?php echo $total_steps; ?></span>
                    <span id="strength-label">Profile Strength: <strong id="strength-pct">0%</strong></span>
                </div>
                <div class="progress-bar-wrap">
                    <div id="setup-progress-bar" style="width: <?php echo (1/$total_steps)*100; ?>%;"></div>
                </div>
            </div>
        </header>

        <main class="setup-v2-main">
            <form id="onboarding-form-v2">
                <?php wp_nonce_field( 'jobs_setup_account', 'jobs_setup_nonce' ); ?>
                <input type="hidden" name="user_role" value="<?php echo esc_attr($role); ?>">
                <input type="hidden" name="display_name" value="<?php echo esc_attr($user->display_name); ?>">

                <?php if ($role !== 'employer') : ?>
                    <!-- JOB SEEKER STEPS -->

                    <!-- Step 1: Profile Photo -->
                    <div class="setup-v2-panel active" data-step="1">
                        <div class="panel-header">
                            <h2>Profile Identity Asset</h2>
                            <p>Upload a high-quality professional photograph. A clean, white background is highly recommended.</p>
                        </div>
                        <div class="photo-upload-zone">
                            <div class="photo-preview-wrap">
                                <?php $current_photo = get_user_meta($user_id, '_jobs_profile_photo', true) ?: get_avatar_url($user_id, array('size' => 150)); ?>
                                <img src="<?php echo esc_url($current_photo); ?>" id="setup-photo-preview">
                                <label for="photo-input" class="photo-edit-btn"><span class="dashicons dashicons-camera"></span></label>
                                <input type="file" id="photo-input" accept="image/*" style="display: none;">
                            </div>
                            <div class="photo-notes">
                                <ul>
                                    <li><span class="dashicons dashicons-yes"></span> Appears in all job applications</li>
                                    <li><span class="dashicons dashicons-yes"></span> Visible in professional messages</li>
                                    <li><span class="dashicons dashicons-yes"></span> Embedded in your generated ATS CV</li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-next-btn" data-next="2">Continue <span class="dashicons dashicons-arrow-right-alt2"></span></button>
                        </div>
                    </div>

                    <!-- Step 2: Nationality -->
                    <div class="setup-v2-panel" data-step="2">
                        <div class="panel-header">
                            <h2>Nationality & Origin</h2>
                            <p>Please specify your citizenship for legal and relocation assessments.</p>
                        </div>
                        <div class="form-row-v2">
                            <div class="form-group-v2">
                                <label>Nationality Country</label>
                                <div class="country-select-with-flag" style="display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; padding: 4px 18px;">
                                    <img src="https://flagcdn.com/w40/un.png" class="selected-flag-preview" id="nat-flag-preview" style="width: 24px; height: auto; border-radius: 2px;">
                                    <select name="nationality" id="nat-country-select" class="country-picker" style="border: none; background: transparent; padding: 10px 0; flex: 1;">
                                        <option value="" data-flag="https://flagcdn.com/w40/un.png">Select Country</option>
                                        <?php foreach($countries_with_flags as $slug => $data): ?>
                                            <option value="<?php echo $slug; ?>" data-flag="<?php echo $data['flag']; ?>"><?php echo $data['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group-v2">
                                <label>City / State</label>
                                <select name="nat_city" id="nat-city-select" disabled>
                                    <option value="">Select Country First</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="1">Back</button>
                            <button type="button" class="v2-next-btn" data-next="3">Continue</button>
                        </div>
                    </div>

                    <!-- Step 3: Residence -->
                    <div class="setup-v2-panel" data-step="3">
                        <div class="panel-header">
                            <h2>Country of Residence</h2>
                            <p>Current location is critical for timezone alignment and local opportunities.</p>
                        </div>
                        <div class="form-row-v2">
                            <div class="form-group-v2">
                                <label>Current Country</label>
                                <div class="country-select-with-flag" style="display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; padding: 4px 18px;">
                                    <img src="https://flagcdn.com/w40/un.png" class="selected-flag-preview" id="res-flag-preview" style="width: 24px; height: auto; border-radius: 2px;">
                                    <select name="residence" id="res-country-select" class="country-picker" style="border: none; background: transparent; padding: 10px 0; flex: 1;">
                                        <option value="" data-flag="https://flagcdn.com/w40/un.png">Select Country</option>
                                        <?php foreach($countries_with_flags as $slug => $data): ?>
                                            <option value="<?php echo $slug; ?>" data-flag="<?php echo $data['flag']; ?>"><?php echo $data['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group-v2">
                                <label>City / State</label>
                                <select name="res_city" id="res-city-select" disabled>
                                    <option value="">Select Country First</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="2">Back</button>
                            <button type="button" class="v2-next-btn" data-next="4">Continue</button>
                        </div>
                    </div>

                    <!-- Step 4: Phone -->
                    <div class="setup-v2-panel" data-step="4">
                        <div class="panel-header">
                            <h2>Professional Communication</h2>
                            <p>Direct contact lines for verified recruitment inquiries.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Primary Phone Number</label>
                            <input type="tel" name="phone" id="primary-phone" class="v2-phone-input">
                            <div style="margin-top: 10px;">
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9em; cursor: pointer;">
                                    <input type="checkbox" name="whatsapp_linked" value="1" style="width: auto;"> This number is linked to <strong>WhatsApp</strong>
                                </label>
                            </div>
                        </div>
                        <div class="form-group-v2" style="margin-top: 30px;">
                            <label>Additional Phone (Optional)</label>
                            <input type="tel" name="phone_extra" id="extra-phone" class="v2-phone-input">
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="3">Back</button>
                            <button type="button" class="v2-next-btn" data-next="5">Continue</button>
                        </div>
                    </div>

                    <!-- Step 5: Specialization -->
                    <div class="setup-v2-panel" data-step="5">
                        <div class="panel-header">
                            <h2>Field of Expertise</h2>
                            <p>Identify your primary professional domain and specific role.</p>
                        </div>
                        <div class="form-row-v2">
                            <div class="form-group-v2">
                                <label>Industry / Specialization</label>
                                <select name="specialization" id="v2-spec-select">
                                    <option value="">Select Field</option>
                                    <?php foreach(array_keys($specializations) as $s): ?>
                                        <option value="<?php echo esc_attr($s); ?>"><?php echo esc_html($s); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group-v2">
                                <label>Job Profession / Role</label>
                                <select name="profession" id="v2-prof-select" disabled>
                                    <option value="">Select Field First</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="4">Back</button>
                            <button type="button" class="v2-next-btn" data-next="6">Continue</button>
                        </div>
                    </div>

                    <!-- Step 6: Summary -->
                    <div class="setup-v2-panel" data-step="6">
                        <div class="panel-header">
                            <h2>Executive Summary</h2>
                            <p>Draft a compelling narrative of your professional journey (200 - 500 characters).</p>
                        </div>
                        <div class="form-group-v2">
                            <textarea name="summary" id="v2-summary" placeholder="e.g. Dedicated Software Architect with over 8 years of experience in building scalable distributed systems..." style="height: 180px;"></textarea>
                            <div class="char-counter"><span id="char-count">0</span> / 500 characters</div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="5">Back</button>
                            <button type="button" class="v2-next-btn" data-next="7" id="summary-next">Continue</button>
                        </div>
                    </div>

                    <!-- Step 7: Academic -->
                    <div class="setup-v2-panel" data-step="7">
                        <div class="panel-header">
                            <h2>Academic Qualification</h2>
                            <p>Your latest verified educational milestone.</p>
                        </div>
                        <div class="form-grid-v2">
                            <div class="form-group-v2">
                                <label>Highest Degree</label>
                                <input type="text" name="academic[0][degree]" placeholder="e.g. Master of Computer Science">
                            </div>
                            <div class="form-group-v2">
                                <label>University / Institution</label>
                                <input type="text" name="academic[0][uni]" placeholder="e.g. Stanford University">
                            </div>
                            <div class="form-group-v2">
                                <label>Faculty / Department</label>
                                <input type="text" name="academic[0][faculty]" placeholder="e.g. School of Engineering">
                            </div>
                            <div class="form-group-v2">
                                <label>Major Specialization</label>
                                <input type="text" name="academic[0][spec]" placeholder="e.g. Artificial Intelligence">
                            </div>
                            <div class="form-group-v2 span-2">
                                <label>Graduation Project Summary</label>
                                <input type="text" name="academic[0][project]" placeholder="Brief title or description">
                            </div>
                            <div class="form-group-v2">
                                <label>Enrollment Year</label>
                                <input type="number" name="academic[0][enroll_year]" placeholder="YYYY">
                            </div>
                            <div class="form-group-v2">
                                <label>Graduation Year</label>
                                <input type="number" name="academic[0][grad_year]" placeholder="YYYY">
                            </div>
                            <div class="form-group-v2">
                                <label>Country of Study</label>
                                <select name="academic[0][country]">
                                    <?php foreach($countries_with_flags as $slug => $data): ?>
                                        <option value="<?php echo $slug; ?>"><?php echo $data['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group-v2">
                                <label>Type of Study</label>
                                <select name="academic[0][type]">
                                    <option value="On-campus">On-campus</option>
                                    <option value="Online">Online</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                            <div class="form-group-v2">
                                <label>Qualification Type</label>
                                <select name="academic[0][qual_type]">
                                    <option value="Academic">Academic</option>
                                    <option value="Professional">Professional</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="6">Back</button>
                            <button type="button" class="v2-next-btn" data-next="8">Continue</button>
                        </div>
                    </div>

                    <!-- Step 8: Experience -->
                    <div class="setup-v2-panel" data-step="8">
                        <div class="panel-header">
                            <h2>Professional Experience</h2>
                            <p>Details of your most recent or current professional engagement.</p>
                        </div>
                        <div class="form-grid-v2">
                            <div class="form-group-v2">
                                <label>Field of Work</label>
                                <input type="text" name="experience[0][field]" placeholder="e.g. Technology">
                            </div>
                            <div class="form-group-v2">
                                <label>Job Title</label>
                                <input type="text" name="experience[0][title]" placeholder="e.g. Senior Project Manager">
                            </div>
                            <div class="form-group-v2">
                                <label>Employment Type</label>
                                <select name="experience[0][type]">
                                    <?php foreach(Jobs_Data_Service::get_employment_types() as $t): ?>
                                        <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group-v2">
                                <label>Country of Employment</label>
                                <select name="experience[0][country]">
                                    <?php foreach($countries_with_flags as $slug => $data): ?>
                                        <option value="<?php echo $slug; ?>"><?php echo $data['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group-v2">
                                <label>Contract Start Date</label>
                                <input type="date" name="experience[0][start]">
                            </div>
                            <div class="form-group-v2">
                                <label>Contract End Date</label>
                                <input type="date" name="experience[0][end]" id="exp-end-date">
                            </div>
                            <div class="form-group-v2 span-2">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600;">
                                    <input type="checkbox" name="experience[0][is_current]" value="1" id="is-current-work" style="width: auto;"> I am currently working in this role
                                </label>
                            </div>
                            <div class="form-group-v2" id="availability-wrap">
                                <label>Confirmation of Availability Date</label>
                                <input type="date" name="availability_date">
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="7">Back</button>
                            <button type="button" class="v2-next-btn" data-next="9">Continue</button>
                        </div>
                    </div>

                    <!-- Step 9: Skills -->
                    <div class="setup-v2-panel" data-step="9">
                        <div class="panel-header">
                            <h2>Expert Skill Set</h2>
                            <p>List up to 10 core competencies. Use smart suggestions for standardized indexing.</p>
                        </div>
                        <div class="form-group-v2">
                            <div class="skills-input-wrap">
                                <input type="text" id="v2-skills-input" placeholder="Start typing a skill...">
                                <div id="v2-skills-suggestions" class="v2-suggestions"></div>
                            </div>
                            <div id="selected-skills-container"></div>
                            <input type="hidden" name="skills_list" id="final-skills-val">
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="8">Back</button>
                            <button type="button" class="v2-next-btn" data-next="10">Continue</button>
                        </div>
                    </div>

                    <!-- Step 10: Languages -->
                    <div class="setup-v2-panel" data-step="10">
                        <div class="panel-header">
                            <h2>Language Assets</h2>
                            <p>Global communication proficiency (Up to 3 languages).</p>
                        </div>
                        <div id="languages-container">
                            <div class="language-entry">
                                <input type="text" name="languages[0][name]" placeholder="e.g. English" value="English">
                                <select name="languages[0][level]">
                                    <option value="Native">Native</option>
                                    <option value="Fluent">Fluent</option>
                                    <option value="Professional">Professional</option>
                                    <option value="Intermediate">Intermediate</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="add-language" class="v2-btn-minimal">+ Add Another Language</button>

                        <div style="margin-top: 30px; padding: 20px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
                            <label style="display: block; font-weight: 700; color: #0369a1; margin-bottom: 10px;">English Proficiency Exams</label>
                            <div style="display: flex; gap: 20px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="radio" name="english_exam" value="IELTS" style="width:auto;"> IELTS</label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="radio" name="english_exam" value="TOEFL" style="width:auto;"> TOEFL</label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;"><input type="radio" name="english_exam" value="None" checked style="width:auto;"> None</label>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="9">Back</button>
                            <button type="button" class="v2-next-btn" data-next="11">Continue</button>
                        </div>
                    </div>

                    <!-- Step 11: Privacy -->
                    <div class="setup-v2-panel" data-step="11">
                        <div class="panel-header">
                            <h2>Privacy & Final Protocols</h2>
                            <p>Finalize your professional profile and data accuracy confirmation.</p>
                        </div>
                        <div class="agreement-box">
                            <label><input type="checkbox" required> I agree to the <a href="<?php echo home_url('/policies'); ?>" target="_blank">Terms of Use</a> and <a href="<?php echo home_url('/policies'); ?>" target="_blank">Privacy Policy</a>.</label>
                            <label><input type="checkbox" required> I confirm that all provided information is accurate and authentic.</label>
                            <label><input type="checkbox" checked disabled> I understand that I can add more details to my public profile later.</label>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="10">Back</button>
                            <button type="submit" class="v2-finish-btn">Finish Account Setup</button>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- EMPLOYER STEPS -->
                    <!-- Adapted from seeker steps but for company -->
                    <div class="setup-v2-panel active" data-step="1">
                        <div class="panel-header">
                            <h2>Corporate Identity Asset</h2>
                            <p>Upload your official company logo. High resolution PNG/JPG preferred.</p>
                        </div>
                        <div class="photo-upload-zone">
                            <div class="photo-preview-wrap company-logo">
                                <?php $current_logo = get_user_meta($user_id, '_jobs_profile_photo', true) ?: get_avatar_url($user_id, array('size' => 150)); ?>
                                <img src="<?php echo esc_url($current_logo); ?>" id="setup-photo-preview">
                                <label for="photo-input" class="photo-edit-btn"><span class="dashicons dashicons-camera"></span></label>
                                <input type="file" id="photo-input" accept="image/*" style="display: none;">
                            </div>
                        </div>
                        <div class="form-group-v2" style="margin-top: 30px;">
                            <label>Company Registered Name</label>
                            <input type="text" name="company_name" placeholder="Brand Name" required>
                        </div>
                        <div class="form-group-v2">
                            <label>Legal Registered Entity Name</label>
                            <input type="text" name="legal_name" placeholder="Official Corporate Name">
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-next-btn" data-next="2">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="2">
                        <div class="panel-header">
                            <h2>Industry Sector</h2>
                            <p>Select your primary business vertical.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Primary Industry</label>
                            <select name="company_industry">
                                <?php foreach(array_keys($specializations) as $s): ?>
                                    <option value="<?php echo esc_attr($s); ?>"><?php echo esc_html($s); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group-v2">
                            <label>Company Type</label>
                            <select name="company_type">
                                <?php foreach(Jobs_Data_Service::get_company_types() as $t): ?>
                                    <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="1">Back</button>
                            <button type="button" class="v2-next-btn" data-next="3">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="3">
                        <div class="panel-header">
                            <h2>Organization Scale</h2>
                            <p>Help candidates understand your company's growth stage.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Employee Count</label>
                            <select name="company_employee_count">
                                <?php foreach(Jobs_Data_Service::get_company_sizes() as $s): ?>
                                    <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group-v2">
                            <label>Founded Year</label>
                            <input type="number" name="founded_year" placeholder="YYYY">
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="2">Back</button>
                            <button type="button" class="v2-next-btn" data-next="4">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="4">
                        <div class="panel-header">
                            <h2>Global HQ Location</h2>
                            <p>Specify the location of your main headquarters.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Headquarters Country</label>
                            <div class="country-select-with-flag" style="display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; padding: 4px 18px;">
                                <img src="https://flagcdn.com/w40/un.png" class="selected-flag-preview" id="hq-flag-preview" style="width: 24px; height: auto; border-radius: 2px;">
                                <select name="company_address" id="hq-country-select" class="country-picker" style="border: none; background: transparent; padding: 10px 0; flex: 1;">
                                    <option value="" data-flag="https://flagcdn.com/w40/un.png">Select Country</option>
                                    <?php foreach($countries_with_flags as $slug => $data): ?>
                                        <option value="<?php echo $data['name']; ?>" data-flag="<?php echo $data['flag']; ?>"><?php echo $data['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="3">Back</button>
                            <button type="button" class="v2-next-btn" data-next="5">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="5">
                        <div class="panel-header">
                            <h2>Digital Presence & Mission</h2>
                            <p>Your official web entry and core organizational goals.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Corporate Website</label>
                            <input type="url" name="company_website" placeholder="https://www.example.com">
                        </div>
                        <div class="form-group-v2">
                            <label>Our Mission</label>
                            <textarea name="mission" placeholder="What drives your company?" style="height: 120px;"></textarea>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="4">Back</button>
                            <button type="button" class="v2-next-btn" data-next="6">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="6">
                        <div class="panel-header">
                            <h2>Organization Details & Benefits</h2>
                            <p>Deep dive into what you do and what you offer to talent.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>About the Organization</label>
                            <textarea name="company_description" placeholder="Full company overview..." style="height: 120px;"></textarea>
                        </div>
                        <div class="form-group-v2">
                            <label>Employee Benefits & Perks</label>
                            <textarea name="benefits" placeholder="Why should people work with you?" style="height: 120px;"></textarea>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="5">Back</button>
                            <button type="button" class="v2-next-btn" data-next="7">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="7">
                        <div class="panel-header">
                            <h2>Culture & Values</h2>
                            <p>Describe the work environment and the values you celebrate.</p>
                        </div>
                        <div class="form-group-v2">
                            <label>Work Culture</label>
                            <textarea name="culture" placeholder="Describe the atmosphere..." style="height: 120px;"></textarea>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="6">Back</button>
                            <button type="button" class="v2-next-btn" data-next="8">Continue</button>
                        </div>
                    </div>

                    <div class="setup-v2-panel" data-step="8">
                        <div class="panel-header">
                            <h2>Privacy & Final Protocols</h2>
                        </div>
                        <div class="agreement-box">
                            <label><input type="checkbox" required> I agree to the <a href="<?php echo home_url('/policies'); ?>" target="_blank">Terms of Use</a>.</label>
                            <label><input type="checkbox" required> I confirm the organizational data is legitimate.</label>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="v2-prev-btn" data-prev="7">Back</button>
                            <button type="submit" class="v2-finish-btn">Establish Company Account</button>
                        </div>
                    </div>

                <?php endif; ?>
            </form>
        </main>
    </div>
</div>

<style>
.jobs-premium-setup-v2 { background: #f8fafc; min-height: 100vh; padding: 60px 20px; font-family: 'Rubik', sans-serif; color: #1e293b; }
.setup-container { max-width: 720px; margin: 0 auto; background: white; border-radius: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); overflow: hidden; }
.setup-v2-header { padding: 48px; border-bottom: 1px solid #f1f5f9; background: #ffffff; }
.setup-welcome h1 { font-size: 2.4em; font-weight: 800; margin: 24px 0 8px; color: #0f172a; }
.setup-welcome p { color: #64748b; font-size: 1.1em; margin: 0; }
.setup-v2-progress { margin-top: 40px; }
.progress-info { display: flex; justify-content: space-between; font-size: 0.85em; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
.progress-bar-wrap { height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
#setup-progress-bar { height: 100%; background: #1d3469; transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); }
.setup-v2-main { padding: 48px; }
.setup-v2-panel { display: none; }
.setup-v2-panel.active { display: block; animation: panelFadeIn 0.5s ease forwards; }
@keyframes panelFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.panel-header h2 { font-size: 1.8em; font-weight: 700; color: #1d3469; margin: 0 0 10px; }
.panel-header p { color: #64748b; margin-bottom: 40px; line-height: 1.6; }
.form-group-v2 { margin-bottom: 24px; }
.form-group-v2 label { display: block; font-size: 0.9em; font-weight: 700; color: #1d3469; margin-bottom: 10px; }
.form-row-v2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.form-grid-v2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.span-2 { grid-column: span 2; }
input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="url"], input[type="date"], select, textarea {
    width: 100%; padding: 14px 18px; border-radius: 14px; border: 2px solid #e2e8f0; background: #f8fafc; font-size: 1em; color: #1e293b; transition: all 0.3s ease; outline: none;
}
input:focus, select:focus, textarea:focus { border-color: #1d3469; background: white; box-shadow: 0 0 0 4px rgba(29, 52, 105, 0.05); }
.photo-upload-zone { display: flex; align-items: center; gap: 32px; background: #f8fafc; padding: 24px; border-radius: 20px; border: 2px dashed #e2e8f0; }
.photo-preview-wrap { position: relative; width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 4px solid white; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.photo-preview-wrap.company-logo { border-radius: 20px; }
.photo-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }
.photo-edit-btn { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; opacity: 0; transition: 0.3s; }
.photo-preview-wrap:hover .photo-edit-btn { opacity: 1; }
.photo-notes ul { margin: 0; padding: 0; list-style: none; }
.photo-notes li { display: flex; align-items: center; gap: 10px; color: #475569; font-size: 0.9em; margin-bottom: 8px; }
.photo-notes .dashicons { color: #10b981; font-size: 20px; width: 20px; height: 20px; }
.char-counter { font-size: 0.8em; color: #94a3b8; text-align: right; margin-top: 8px; }
.agreement-box { background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; }
.agreement-box label { display: block; margin-bottom: 12px; font-size: 0.9em; color: #475569; cursor: pointer; }
.agreement-box input { width: auto; margin-right: 12px; }
.panel-footer { margin-top: 50px; display: flex; justify-content: space-between; gap: 16px; }
.v2-next-btn, .v2-finish-btn { background: #1d3469; color: white; border: none; padding: 16px 40px; border-radius: 16px; font-weight: 800; cursor: pointer; transition: 0.3s; font-size: 1.1em; flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px; }
.v2-next-btn:hover, .v2-finish-btn:hover { background: #2a4a8c; transform: translateY(-2px); box-shadow: 0 12px 24px rgba(29, 52, 105, 0.2); }
.v2-prev-btn { background: #f1f5f9; color: #64748b; border: none; padding: 16px 30px; border-radius: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; }
.v2-prev-btn:hover { background: #e2e8f0; }
.iti { width: 100%; }
#selected-skills-container { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px; }
.skill-pill { background: #eff6ff; color: #1d3469; padding: 6px 14px; border-radius: 50px; font-size: 0.85em; font-weight: 700; display: flex; align-items: center; gap: 8px; border: 1px solid #dbeafe; }
.skill-pill .remove-skill { cursor: pointer; font-size: 14px; color: #94a3b8; }
.skill-pill .remove-skill:hover { color: #ef4444; }
.v2-suggestions { position: absolute; background: white; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); width: 100%; z-index: 10; display: none; max-height: 200px; overflow-y: auto; }
.suggestion-item { padding: 12px 18px; cursor: pointer; font-size: 0.9em; }
.suggestion-item:hover { background: #f8fafc; color: #1d3469; }
.language-entry { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 12px; }
.v2-btn-minimal { background: none; border: none; color: #1d3469; font-weight: 700; font-size: 0.9em; cursor: pointer; padding: 0; margin-top: 10px; }
</style>

<script>
jQuery(document).ready(function($) {
    const locations = <?php echo json_encode($locations); ?>;
    const skillsList = <?php echo json_encode($skills_list); ?>;
    const specializations = <?php echo json_encode($specializations); ?>;
    const totalSteps = <?php echo $total_steps; ?>;
    let selectedSkills = [];

    // Photo Upload Handler
    $('#photo-input').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { $('#setup-photo-preview').attr('src', e.target.result); }
            reader.readAsDataURL(file);

            // AJAX Upload
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('action', 'jobs_upload_photo');
            formData.append('nonce', '<?php echo wp_create_nonce("jobs_main_nonce"); ?>');

            $.ajax({
                url: jobs_vars.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) { if (!res.success) alert('Upload failed: ' + res.data); }
            });
        }
    });

    // Dynamic Regions & Flag Updates
    $('.country-picker').on('change', function() {
        const country = $(this).val();
        const id = $(this).attr('id');
        let flagId = '';
        let targetId = '';

        if (id.includes('nat')) {
            flagId = '#nat-flag-preview';
            targetId = '#nat-city-select';
        } else if (id.includes('res')) {
            flagId = '#res-flag-preview';
            targetId = '#res-city-select';
        } else if (id.includes('hq')) {
            flagId = '#hq-flag-preview';
        }
        const $citySelect = $(targetId);

        // Update Flag
        const flagUrl = $(this).find(':selected').data('flag');
        if (flagUrl) $(flagId).attr('src', flagUrl);

        $citySelect.empty().append('<option value="">Select City / State</option>');
        if (country && locations[country]) {
            locations[country].forEach(city => { $citySelect.append(`<option value="${city}">${city}</option>`); });
            $citySelect.prop('disabled', false);
        } else {
            $citySelect.prop('disabled', true);
        }
    });

    // Dynamic Professions
    $('#v2-spec-select').on('change', function() {
        const spec = $(this).val();
        const $profSelect = $('#v2-prof-select');
        $profSelect.empty().append('<option value="">Select Role</option>');
        if (spec && specializations[spec]) {
            specializations[spec].forEach(p => { $profSelect.append(`<option value="${p}">${p}</option>`); });
            $profSelect.prop('disabled', false);
        } else {
            $profSelect.prop('disabled', true);
        }
    });

    // Summary Counter & Validation
    $('#v2-summary').on('input', function() {
        const count = $(this).val().length;
        $('#char-count').text(count);
        if (count >= 200 && count <= 500) {
            $('#char-count').css('color', '#10b981');
            $('#summary-next').prop('disabled', false).css('opacity', 1);
        } else {
            $('#char-count').css('color', '#ef4444');
            $('#summary-next').prop('disabled', true).css('opacity', 0.5);
        }
    });
    // Trigger initially
    $('#v2-summary').trigger('input');

    // Current Work Logic
    $('#is-current-work').on('change', function() {
        const isCurrent = $(this).is(':checked');
        $('#exp-end-date').prop('disabled', isCurrent).parent().css('opacity', isCurrent ? 0.5 : 1);
        if (isCurrent) $('#exp-end-date').val('');
    });
    $('#is-current-work').trigger('change');

    // Skills Logic
    $('#v2-skills-input').on('input', function() {
        const val = $(this).val().toLowerCase();
        if (val.length < 1) { $('#v2-skills-suggestions').hide(); return; }
        const matches = skillsList.filter(s => s.toLowerCase().includes(val) && !selectedSkills.includes(s));
        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 8).forEach(m => { html += `<div class="suggestion-item skill-choice" data-skill="${m}">${m}</div>`; });
            $('#v2-skills-suggestions').html(html).show();
        } else { $('#v2-skills-suggestions').hide(); }
    });

    $(document).on('click', '.skill-choice', function() {
        const skill = $(this).data('skill');
        if (selectedSkills.length < 10) {
            selectedSkills.push(skill);
            updateSkillsUI();
        }
        $('#v2-skills-input').val('').focus();
        $('#v2-skills-suggestions').hide();
    });

    $(document).on('click', '.remove-skill', function() {
        const skill = $(this).parent().data('skill');
        selectedSkills = selectedSkills.filter(s => s !== skill);
        updateSkillsUI();
    });

    function updateSkillsUI() {
        let html = '';
        selectedSkills.forEach(s => {
            html += `<div class="skill-pill" data-skill="${s}">${s} <span class="remove-skill">&times;</span></div>`;
        });
        $('#selected-skills-container').html(html);
        $('#final-skills-val').val(selectedSkills.join(', '));
    }

    // Languages Logic
    let langIndex = 1;
    $('#add-language').on('click', function() {
        if (langIndex >= 3) return;
        const html = `
            <div class="language-entry">
                <input type="text" name="languages[${langIndex}][name]" placeholder="Language">
                <select name="languages[${langIndex}][level]">
                    <option value="Native">Native</option>
                    <option value="Fluent">Fluent</option>
                    <option value="Professional">Professional</option>
                    <option value="Intermediate">Intermediate</option>
                </select>
            </div>`;
        $('#languages-container').append(html);
        langIndex++;
        if (langIndex >= 3) $(this).hide();
    });

    // Stepper Navigation
    $('.v2-next-btn').on('click', function() {
        const next = $(this).data('next');
        goToStep(next);
    });
    $('.v2-prev-btn').on('click', function() {
        const prev = $(this).data('prev');
        goToStep(prev);
    });

    function goToStep(step) {
        $('.setup-v2-panel').removeClass('active');
        $(`.setup-v2-panel[data-step="${step}"]`).addClass('active');

        $('#setup-progress-bar').css('width', (step / totalSteps * 100) + '%');
        $('#step-counter').text(`Step ${step} of ${totalSteps}`);

        const strength = Math.round((step / totalSteps) * 100);
        $('#strength-pct').text(strength + '%');

        window.scrollTo(0, 0);
    }

    // Final Submission
    $('#onboarding-form-v2').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('.v2-finish-btn');
        $btn.prop('disabled', true).text('Finalizing Assets...');

        $.post(jobs_vars.ajax_url, $(this).serialize() + '&action=jobs_complete_setup_v2', function(res) {
            if (res.success) {
                $('.setup-v2-main').html(`
                    <div style="text-align: center; padding: 60px 0;">
                        <div style="font-size: 80px; margin-bottom: 30px;">🎊</div>
                        <h1 style="color: #1d3469; font-size: 2.5em; font-weight: 800;">Congratulations!</h1>
                        <p style="color: #64748b; font-size: 1.2em; max-width: 500px; margin: 0 auto 40px;">Your professional profile is now 100% complete and verified. Redirecting you to the premium platform...</p>
                        <div class="progress-bar-wrap" style="max-width: 300px; margin: 0 auto;">
                            <div id="final-loader" style="width: 0%; height: 100%; background: #10b981;"></div>
                        </div>
                    </div>
                `);
                $('#final-loader').animate({width: '100%'}, 3000, function() {
                    window.location.href = res.data.redirect;
                });
            } else {
                alert(res.data);
                $btn.prop('disabled', false).text('Finish Account Setup');
            }
        });
    });

    // Initialize Global Scripts
    if (window.initJobsPhoneFields) window.initJobsPhoneFields();
});
</script>

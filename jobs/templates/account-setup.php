<?php
/**
 * Template: Highly Professional Step-by-Step Account Setup
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

$specializations = Jobs_Data_Service::get_specializations();
$locations = Jobs_Data_Service::get_countries_with_regions();
$skills_list = Jobs_Data_Service::get_skills();
$currencies = Jobs_Data_Service::get_currencies();
?>
<div class="jobs-setup-wrapper" style="background: white; min-height: 100vh; font-family: 'Rubik', sans-serif; color: #1e293b;">
    <div class="setup-main-container" style="max-width: 900px; margin: 0 auto; padding: 60px 20px;">

        <!-- Header -->
        <div class="setup-brand-header" style="text-align: center; margin-bottom: 50px;">
            <?php echo do_shortcode('[jobedia_logo]'); ?>
            <h1 style="margin-top: 30px; font-size: 2.2em; color: #1d3469; font-weight: 700;">Complete Your Profile</h1>
            <p style="color: #64748b; font-size: 1.1em;">Let's customize your experience on Jobedia.</p>
        </div>

        <!-- Progress Stepper -->
        <div class="setup-stepper" style="display: flex; justify-content: space-between; margin-bottom: 60px; position: relative;">
            <div class="stepper-line" style="position: absolute; top: 20px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1;"></div>
            <div id="stepper-progress" style="position: absolute; top: 20px; left: 0; width: 0%; height: 2px; background: #1d3469; z-index: 2; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);"></div>

            <?php
            $steps = ($role === 'employer') ? array('Company Info', 'Branding', 'Finish') : array('Identity', 'Professional', 'Academic', 'Experience', 'Preferences', 'Finish');
            foreach($steps as $i => $step_name): ?>
            <div class="step-item <?php echo $i === 0 ? 'active' : ''; ?>" data-step="<?php echo $i + 1; ?>" style="position: relative; z-index: 3; text-align: center; flex: 1;">
                <div class="step-number" style="width: 40px; height: 40px; border-radius: 50%; background: <?php echo $i===0?'#1d3469':'white'; ?>; border: 2px solid <?php echo $i===0?'#1d3469':'#e2e8f0'; ?>; color: <?php echo $i===0?'white':'#94a3b8'; ?>; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 700; transition: all 0.3s;"><?php echo $i + 1; ?></div>
                <span class="step-label" style="font-size: 0.85em; font-weight: 600; color: <?php echo $i===0?'#1d3469':'#94a3b8'; ?>;"><?php echo $step_name; ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="setup-content-card">
            <form id="jobs-setup-form-v2">
                <?php wp_nonce_field( 'jobs_setup_account', 'jobs_setup_nonce' ); ?>
                <input type="hidden" name="user_role" value="<?php echo esc_attr( $role ); ?>">

                <!-- JOB SEEKER STEPS -->
                <?php if ($role !== 'employer') : ?>
                    <!-- Step 1: Identity -->
                    <div class="setup-panel active" id="setup-panel-1">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Personal Identity</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Display Name</label>
                                <input type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" placeholder="e.g. John Doe" required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Contact Phone</label>
                                <input type="tel" id="setup-phone" name="phone" class="jobs-intl-phone" style="width: 100%;">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Gender</label>
                                <select name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nationality</label>
                                <input type="text" name="nationality" placeholder="e.g. Egyptian">
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; text-align: right;">
                            <button type="button" class="next-step-btn" data-next="2">Continue to Professional Details</button>
                        </div>
                    </div>

                    <!-- Step 2: Professional -->
                    <div class="setup-panel" id="setup-panel-2" style="display: none;">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Professional Focus</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Core Specialization</label>
                                <select name="specialization" id="setup-specialization" required>
                                    <option value="">Select Specialization</option>
                                    <?php foreach(array_keys($specializations) as $spec): ?>
                                        <option value="<?php echo esc_attr($spec); ?>"><?php echo esc_html($spec); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Specific Profession / Role</label>
                                <select name="profession" id="setup-profession" disabled>
                                    <option value="">Select Specialization First</option>
                                </select>
                            </div>
                            <div class="form-group span-2" style="grid-column: span 2;">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Professional Bio</label>
                                <textarea name="bio" placeholder="Briefly describe your professional background and career goals..." style="height: 120px;"></textarea>
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; display: flex; justify-content: space-between;">
                            <button type="button" class="prev-step-btn" data-prev="1">Back</button>
                            <button type="button" class="next-step-btn" data-next="3">Academic History</button>
                        </div>
                    </div>

                    <!-- Step 3: Academic -->
                    <div class="setup-panel" id="setup-panel-3" style="display: none;">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Academic Background</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Highest Degree</label>
                                <select name="academic[0][degree]">
                                    <option value="Bachelor's">Bachelor's Degree</option>
                                    <option value="Master's">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                    <option value="Diploma">Diploma</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">University / Institution</label>
                                <input type="text" name="academic[0][uni]" placeholder="University Name">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Graduation Year</label>
                                <input type="number" name="academic[0][grad_date]" placeholder="YYYY">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">GPA / Grade</label>
                                <input type="text" name="academic[0][gpa]" placeholder="e.g. 3.8/4.0">
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; display: flex; justify-content: space-between;">
                            <button type="button" class="prev-step-btn" data-prev="2">Back</button>
                            <button type="button" class="next-step-btn" data-next="4">Work Experience</button>
                        </div>
                    </div>

                    <!-- Step 4: Experience -->
                    <div class="setup-panel" id="setup-panel-4" style="display: none;">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Work Experience</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Latest Job Title</label>
                                <input type="text" name="experience[0][title]" placeholder="e.g. Senior Developer">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Previous Employer</label>
                                <input type="text" id="setup-employer-suggestion" name="experience[0][company]" placeholder="Type company name..." autocomplete="off">
                                <div id="employer-suggestions-list" class="suggestions-list"></div>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Years of Experience</label>
                                <input type="number" name="experience_years" placeholder="Total years">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Availability Status</label>
                                <select name="availability">
                                    <?php foreach(Jobs_Data_Service::get_availability_statuses() as $s): ?>
                                        <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; display: flex; justify-content: space-between;">
                            <button type="button" class="prev-step-btn" data-prev="3">Back</button>
                            <button type="button" class="next-step-btn" data-next="5">Preferences & Skills</button>
                        </div>
                    </div>

                    <!-- Step 5: Preferences -->
                    <div class="setup-panel" id="setup-panel-5" style="display: none;">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Skills & Preferences</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group span-2" style="grid-column: span 2; position: relative;">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Key Skills (Type to see suggestions)</label>
                                <input type="text" id="setup-skills-autocomplete" name="skills[core]" placeholder="e.g. React, PHP, SEO" autocomplete="off">
                                <div id="skills-suggestions-list" class="suggestions-list"></div>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Preferred Country</label>
                                <select name="country" id="setup-country">
                                    <option value="">Select Country</option>
                                    <?php foreach(array_keys($locations) as $c): ?>
                                        <option value="<?php echo esc_attr($c); ?>"><?php echo esc_html(ucwords(str_replace('-', ' ', $c))); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Preferred Region / State</label>
                                <select name="region" id="setup-region" disabled>
                                    <option value="">Select Country First</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Expected Salary</label>
                                <div style="display: flex; gap: 10px;">
                                    <select name="preferences[currency]" style="width: 100px; flex-shrink: 0;">
                                        <?php foreach($currencies as $code => $sym): ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php selected($code, 'USD'); ?>><?php echo esc_html($code); ?> (<?php echo esc_html($sym); ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="preferences[salary]" placeholder="Monthly expectation" style="flex: 1;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Work Setting</label>
                                <select name="preferences[flexibility]">
                                    <option value="On-site">On-site</option>
                                    <option value="Remote">Remote</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; display: flex; justify-content: space-between;">
                            <button type="button" class="prev-step-btn" data-prev="4">Back</button>
                            <button type="button" class="next-step-btn" data-next="6">Finalize</button>
                        </div>
                    </div>

                    <!-- Step 6: Finish -->
                    <div class="setup-panel" id="setup-panel-6" style="display: none;">
                        <div style="text-align: center; padding: 40px 0;">
                            <div style="font-size: 60px; margin-bottom: 20px;">🚀</div>
                            <h2 style="color: #1d3469;">Ready to start your journey?</h2>
                            <p style="color: #64748b; max-width: 500px; margin: 0 auto 40px;">Your professional profile is ready to be published. You can now access all Jobedia features.</p>

                            <div style="display: flex; justify-content: center; gap: 20px;">
                                <button type="button" class="prev-step-btn" data-prev="5">Review Again</button>
                                <button type="submit" class="complete-setup-btn">Publish My Profile</button>
                            </div>
                        </div>
                    </div>

                <!-- EMPLOYER STEPS -->
                <?php else : ?>
                    <!-- Step 1: Company Info -->
                    <div class="setup-panel active" id="setup-panel-1">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Company Information</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Company Display Name</label>
                                <input type="text" name="company_name" placeholder="Brand name" required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Legal Name</label>
                                <input type="text" name="legal_name" placeholder="Official registered name">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Industry</label>
                                <select name="company_industry">
                                    <?php foreach(array_keys(Jobs_Data_Service::get_specializations()) as $spec): ?>
                                        <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Company Type</label>
                                <select name="company_type">
                                    <?php foreach(Jobs_Data_Service::get_company_types() as $t): ?>
                                        <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Company Size</label>
                                <select name="company_employee_count">
                                    <?php foreach(Jobs_Data_Service::get_company_sizes() as $s): ?>
                                        <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Founded Year</label>
                                <input type="number" name="founded_year" placeholder="e.g. 2010">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Headquarters Country</label>
                                <select name="country" id="setup-country">
                                    <option value="">Select Country</option>
                                    <?php foreach(array_keys($locations) as $c): ?>
                                        <option value="<?php echo esc_attr($c); ?>"><?php echo esc_html(ucwords(str_replace('-', ' ', $c))); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; text-align: right;">
                            <button type="button" class="next-step-btn" data-next="2">Continue to Branding</button>
                        </div>
                    </div>

                    <!-- Step 2: Branding -->
                    <div class="setup-panel" id="setup-panel-2" style="display: none;">
                        <h2 style="color: #1d3469; margin-bottom: 30px;">Company Branding</h2>
                        <div class="form-grid" style="display: grid; grid-template-columns: 1fr; gap: 25px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Company Logo URL</label>
                                <input type="text" name="company_logo" placeholder="https://path-to-your-logo.png">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Company Website</label>
                                <input type="text" name="company_website" placeholder="https://www.yourcompany.com">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px;">About the Company</label>
                                <textarea name="company_description" placeholder="Describe your organization's mission and values..." style="height: 150px;"></textarea>
                            </div>
                        </div>
                        <div class="step-actions" style="margin-top: 40px; display: flex; justify-content: space-between;">
                            <button type="button" class="prev-step-btn" data-prev="1">Back</button>
                            <button type="button" class="next-step-btn" data-next="3">Finalize</button>
                        </div>
                    </div>

                    <!-- Step 3: Finish -->
                    <div class="setup-panel" id="setup-panel-3" style="display: none;">
                        <div style="text-align: center; padding: 40px 0;">
                            <div style="font-size: 60px; margin-bottom: 20px;">🏢</div>
                            <h2 style="color: #1d3469;">Ready to build your team?</h2>
                            <p style="color: #64748b; max-width: 500px; margin: 0 auto 40px;">Your company profile is ready. You can now start posting jobs and viewing applications.</p>

                            <div style="display: flex; justify-content: center; gap: 20px;">
                                <button type="button" class="prev-step-btn" data-prev="2">Review Again</button>
                                <button type="submit" class="complete-setup-btn">Establish Company</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </form>
        </div>
    </div>
</div>

<style>
.form-group label { transition: color 0.2s; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.95em;
    transition: all 0.2s;
    outline: none;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: #1d3469;
    background: white;
    box-shadow: 0 0 0 4px rgba(29, 52, 105, 0.05);
}
.next-step-btn, .complete-setup-btn {
    background: #1d3469;
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.next-step-btn:hover, .complete-setup-btn:hover {
    background: #2a4a8c;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(29, 52, 105, 0.2);
}
.prev-step-btn {
    background: #f1f5f9;
    color: #64748b;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
}
.step-item.active .step-number { box-shadow: 0 0 0 6px rgba(29, 52, 105, 0.1); }
.iti { width: 100%; }

/* Suggestion List Styling */
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
    width: calc(100% - 32px);
}
.suggestion-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background 0.2s;
}
.suggestion-item:hover { background: #f8fafc; color: #1d3469; }
</style>

<script>
jQuery(document).ready(function($) {
    const specializationsData = <?php echo json_encode($specializations); ?>;
    const locationData = <?php echo json_encode($locations); ?>;
    let currentStep = 1;
    const totalSteps = <?php echo count($steps); ?>;

    // Initialize Phone Input for all unified phone fields
    $('.jobs-intl-phone').each(function() {
        window.intlTelInput(this, {
            preferredCountries: ['eg', 'ae', 'sa', 'jo', 'us', 'gb'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            separateDialCode: true,
        });
    });

    // Dynamic Professions Logic
    $('#setup-specialization').on('change', function() {
        const spec = $(this).val();
        const $profSelect = $('#setup-profession');
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
    $('#setup-country').on('change', function() {
        const country = $(this).val();
        const $regionSelect = $('#setup-region');
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

    // Stepper Navigation
    $('.next-step-btn').on('click', function() {
        const next = $(this).data('next');
        goToStep(next);
    });

    $('.prev-step-btn').on('click', function() {
        const prev = $(this).data('prev');
        goToStep(prev);
    });

    function goToStep(step) {
        $('.setup-panel').hide();
        $(`#setup-panel-${step}`).fadeIn();

        // Update visual stepper
        $('.step-item').removeClass('active');
        $(`.step-item[data-step="${step}"]`).addClass('active');

        // Update line progress
        const progress = ((step - 1) / (totalSteps - 1)) * 100;
        $('#stepper-progress').css('width', progress + '%');

        // Style completed steps
        $('.step-item').each(function() {
            const s = $(this).data('step');
            const $num = $(this).find('.step-number');
            const $label = $(this).find('.step-label');
            if (s < step) {
                $num.css({'background': '#10b981', 'border-color': '#10b981', 'color': 'white'});
                $label.css('color', '#10b981');
            } else if (s === step) {
                $num.css({'background': '#1d3469', 'border-color': '#1d3469', 'color': 'white'});
                $label.css('color', '#1d3469');
            } else {
                $num.css({'background': 'white', 'border-color': '#e2e8f0', 'color': '#94a3b8'});
                $label.css('color', '#94a3b8');
            }
        });

        currentStep = step;
        window.scrollTo(0, 0);
    }

    // Skills Suggestions Logic
    const skillsList = <?php echo json_encode($skills_list); ?>;
    $('#setup-skills-autocomplete').on('input', function() {
        const val = $(this).val();
        const parts = val.split(',');
        const query = parts[parts.length - 1].trim().toLowerCase();

        if (query.length < 1) {
            $('#skills-suggestions-list').hide();
            return;
        }

        const matches = skillsList.filter(s => s.toLowerCase().includes(query));
        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 10).forEach(m => {
                html += `<div class="suggestion-item skill-suggestion" data-val="${m}">${m}</div>`;
            });
            $('#skills-suggestions-list').html(html).show();
        } else {
            $('#skills-suggestions-list').hide();
        }
    });

    $(document).on('click', '.skill-suggestion', function() {
        const skill = $(this).data('val');
        const $input = $('#setup-skills-autocomplete');
        const parts = $input.val().split(',');
        parts[parts.length - 1] = ' ' + skill;
        $input.val(parts.join(',').trim() + ', ');
        $('#skills-suggestions-list').hide();
        $input.focus();
    });

    // Employer Suggestions Logic
    let suggestionTimeout;
    $('#setup-employer-suggestion').on('input', function() {
        const query = $(this).val();
        clearTimeout(suggestionTimeout);
        if (query.length < 2) {
            $('#employer-suggestions-list').hide();
            return;
        }

        suggestionTimeout = setTimeout(() => {
            $.post(jobs_vars.ajax_url, {
                action: 'jobs_suggest_employers',
                nonce: '<?php echo wp_create_nonce("jobs_main_nonce"); ?>',
                q: query
            }, function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(item => {
                        html += `<div class="suggestion-item" data-name="${item}">${item}</div>`;
                    });
                    $('#employer-suggestions-list').html(html).show();
                } else {
                    $('#employer-suggestions-list').hide();
                }
            });
        }, 300);
    });

    $(document).on('click', '.suggestion-item', function() {
        $('#setup-employer-suggestion').val($(this).data('name'));
        $('#employer-suggestions-list').hide();
    });

    // Form Submission
    $('#jobs-setup-form-v2').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('.complete-setup-btn');
        $btn.prop('disabled', true).text('Saving Profile...');

        $.post(jobs_vars.ajax_url, $(this).serialize() + '&action=jobs_complete_setup_v2', function(response) {
            if (response.success) {
                window.location.href = response.data.redirect;
            } else {
                alert(response.data);
                $btn.prop('disabled', false).text('Try Again');
            }
        });
    });
});
</script>

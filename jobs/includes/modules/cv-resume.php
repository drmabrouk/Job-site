<?php
/**
 * Module: CV / Resume (Enhanced with Multi-step UI)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$cv_data = get_user_meta( $current_user_id, 'jobs_cv_data', true ) ?: array();
$profile_link = jobs_get_profile_link( $current_user_id );
?>
<div class="jobs-module-content" id="jobs-cv-module">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h3 style="margin: 0;">Professional CV Setup</h3>
        <div class="jobs-visibility-badge" style="font-size: 0.75em; padding: 5px 12px; border-radius: 20px; background: <?php echo (get_user_meta($current_user_id, 'profile_visibility', true) === 'private' ? '#fee2e2' : '#dcfce7'); ?>; color: <?php echo (get_user_meta($current_user_id, 'profile_visibility', true) === 'private' ? '#991b1b' : '#166534'); ?>;">
            <span class="dashicons <?php echo (get_user_meta($current_user_id, 'profile_visibility', true) === 'private' ? 'dashicons-hidden' : 'dashicons-visibility'); ?>" style="font-size: 14px; width: 14px; height: 14px; vertical-align: middle;"></span>
            <?php echo (get_user_meta($current_user_id, 'profile_visibility', true) === 'private' ? 'Private Profile' : 'Public Profile'); ?>
        </div>
    </div>

    <div class="jobs-share-link-box" style="margin-bottom: 30px; padding: 15px; border: 1px dashed var(--jobs-primary-color); border-radius: 12px; background: rgba(29, 52, 105, 0.02);">
        <strong style="font-size: 0.85em; color: #555;">Shareable Profile Link:</strong><br>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank" style="font-size: 0.9em; word-break: break-all;"><?php echo esc_html( $profile_link ); ?></a>
    </div>

    <form id="jobs-cv-form" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <div class="cv-steps-container">
            <!-- Step 0: Professional Profile -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(0)" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display:flex; align-items: center; gap: 15px;">
                        <span class="step-num" style="width: 24px; height: 24px; border-radius: 50%; background: var(--jobs-primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">1</span>
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600;">Personal Information</h4>
                    </div>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-0" style="padding: 10px 20px 20px 20px;">
                    <div class="form-row" style="display:flex; gap:10px; margin-bottom: 15px;">
                        <div class="form-group" style="flex:1;">
                            <label>Nationality</label>
                            <input type="text" name="cv_nationality" value="<?php echo esc_attr(get_user_meta($current_user_id, '_nationality', true)); ?>" placeholder="e.g. American">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Gender</label>
                            <select name="cv_gender">
                                <option value="">Select Gender</option>
                                <option value="male" <?php selected(get_user_meta($current_user_id, '_gender', true), 'male'); ?>>Male</option>
                                <option value="female" <?php selected(get_user_meta($current_user_id, '_gender', true), 'female'); ?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row" style="display:flex; gap:10px; margin-bottom: 15px;">
                        <div class="form-group" style="flex:1;">
                            <label>Qualification</label>
                            <select name="cv_qualification">
                                <option value="">Select Qualification</option>
                                <option value="high-school" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'high-school'); ?>>High School</option>
                                <option value="bachelor" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'bachelor'); ?>>Bachelor's Degree</option>
                                <option value="master" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'master'); ?>>Master's Degree</option>
                                <option value="phd" <?php selected(get_user_meta($current_user_id, '_qualification', true), 'phd'); ?>>PhD</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>English Level</label>
                            <select name="cv_english_level">
                                <option value="">Select English Level</option>
                                <option value="basic" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'basic'); ?>>Basic</option>
                                <option value="intermediate" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'intermediate'); ?>>Intermediate</option>
                                <option value="fluent" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'fluent'); ?>>Fluent</option>
                                <option value="native" <?php selected(get_user_meta($current_user_id, '_english_level', true), 'native'); ?>>Native</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Total Experience (Years)</label>
                            <input type="number" name="cv_experience_years" value="<?php echo esc_attr(get_user_meta($current_user_id, '_experience', true)); ?>" placeholder="e.g. 5">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Specialization</label>
                            <select name="cv_specialization">
                                <option value="">Select Specialization</option>
                                <?php
                                $specs = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                                $current_spec = get_user_meta($current_user_id, '_specialization', true);
                                foreach ($specs as $spec) {
                                    echo '<option value="'.esc_attr($spec->slug).'" '.selected($current_spec, $spec->slug, false).'>'.esc_html($spec->name).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1: Education -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(1)" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display:flex; align-items: center; gap: 15px;">
                        <span class="step-num" style="width: 24px; height: 24px; border-radius: 50%; background: var(--jobs-primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">2</span>
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600;">Education History</h4>
                    </div>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-1" style="display:none; padding: 10px 20px 20px 20px;">
                    <textarea name="cv_education" placeholder="Describe your educational background..."><?php echo esc_textarea($cv_data['education'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Step 2: Experience -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(2)" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display:flex; align-items: center; gap: 15px;">
                        <span class="step-num" style="width: 24px; height: 24px; border-radius: 50%; background: var(--jobs-primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">3</span>
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600;">Professional Experience</h4>
                    </div>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-2" style="display:none; padding: 10px 20px 20px 20px;">
                    <textarea name="cv_experience" placeholder="Detail your previous roles and responsibilities..."><?php echo esc_textarea($cv_data['experience'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Step 3: Skills -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(3)" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display:flex; align-items: center; gap: 15px;">
                        <span class="step-num" style="width: 24px; height: 24px; border-radius: 50%; background: var(--jobs-primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">4</span>
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600;">Skills & Expertise</h4>
                    </div>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-3" style="display:none; padding: 10px 20px 20px 20px;">
                    <input type="text" name="cv_skills" value="<?php echo esc_attr($cv_data['skills'] ?? ''); ?>" style="width:100%;" placeholder="e.g. PHP, JavaScript, Project Management">
                </div>
            </div>

            <!-- Step 4: Certifications -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(4)" style="cursor: pointer; display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px; border: 1px solid #e2e8f0; transition: all 0.3s;">
                    <div style="display:flex; align-items: center; gap: 15px;">
                        <span class="step-num" style="width: 24px; height: 24px; border-radius: 50%; background: var(--jobs-primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">5</span>
                        <h4 style="margin: 0; font-size: 1em; font-weight: 600;">Certifications & Awards</h4>
                    </div>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-4" style="display:none; padding: 10px 20px 20px 20px;">
                    <textarea name="cv_certifications" placeholder="List any certifications, courses, or licenses..."><?php echo esc_textarea($cv_data['certifications'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" name="jobs_submit_cv" class="jobs-btn" style="margin-top: 20px;">Save & Update Profile</button>
        <button type="button" id="jobs-generate-pdf" class="jobs-btn" style="margin-top: 20px; background-color: #555;">Generate PDF Resume</button>
    </form>
    <div id="jobs-cv-status" style="margin-top: 10px;"></div>
</div>

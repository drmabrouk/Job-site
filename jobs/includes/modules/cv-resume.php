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
    <h3>Professional CV Setup</h3>

    <div class="jobs-share-link-box" style="margin-bottom: 20px; padding: 15px; border: 1px dashed var(--jobs-primary-color); border-radius: 8px;">
        <strong>Your Shareable Profile Link:</strong><br>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank"><?php echo esc_html( $profile_link ); ?></a>
    </div>

    <form id="jobs-cv-form" method="POST">
        <?php wp_nonce_field( 'jobs_save_cv', 'jobs_cv_nonce' ); ?>

        <div class="cv-steps-container">
            <!-- Step 0: Professional Profile -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(0)">
                    <span class="step-num">0</span>
                    <h4>Professional Profile</h4>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-0">
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
                <div class="cv-step-header" onclick="toggleCvStep(1)">
                    <span class="step-num">1</span>
                    <h4>Education</h4>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-1">
                    <textarea name="cv_education" placeholder="Describe your educational background..."><?php echo esc_textarea($cv_data['education'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Step 2: Experience -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(2)">
                    <span class="step-num">2</span>
                    <h4>Work Experience</h4>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-2" style="display:none;">
                    <textarea name="cv_experience" placeholder="Detail your previous roles and responsibilities..."><?php echo esc_textarea($cv_data['experience'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Step 3: Skills -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(3)">
                    <span class="step-num">3</span>
                    <h4>Skills</h4>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-3" style="display:none;">
                    <input type="text" name="cv_skills" value="<?php echo esc_attr($cv_data['skills'] ?? ''); ?>" style="width:100%;" placeholder="e.g. PHP, JavaScript, Project Management">
                </div>
            </div>

            <!-- Step 4: Certifications -->
            <div class="cv-step-item">
                <div class="cv-step-header" onclick="toggleCvStep(4)">
                    <span class="step-num">4</span>
                    <h4>Certifications & Courses</h4>
                    <span class="step-toggle-icon">▼</span>
                </div>
                <div class="cv-step-content" id="cv-step-4" style="display:none;">
                    <textarea name="cv_certifications" placeholder="List any certifications, courses, or licenses..."><?php echo esc_textarea($cv_data['certifications'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" name="jobs_submit_cv" class="jobs-btn" style="margin-top: 20px;">Save & Update Profile</button>
        <button type="button" id="jobs-generate-pdf" class="jobs-btn" style="margin-top: 20px; background-color: #555;">Generate PDF Resume</button>
    </form>
    <div id="jobs-cv-status" style="margin-top: 10px;"></div>
</div>

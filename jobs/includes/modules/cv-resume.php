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

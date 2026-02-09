<?php
/**
 * Template: High-Performance Professional Portfolio (Public Profile)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$profile_slug = get_query_var('profile_user');
if ( ! $profile_slug ) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $parts = explode('/', $path);
    if (count($parts) >= 2 && $parts[0] === 'profile') {
        $profile_slug = $parts[1];
    }
}

$user = $profile_slug ? get_user_by('slug', $profile_slug) : null;
if (!$user) wp_die('Profile not found.');

$user_id = $user->ID;
$role = $user->roles[0] ?? 'job_seeker';
$display_name = $user->display_name;

get_header();
?>
<div class="jobs-portfolio-v2" style="background: #f8fafc; min-height: 100vh; padding: 120px 20px 80px; font-family: 'Rubik', sans-serif;">
    <div class="portfolio-container" style="max-width: 1100px; margin: 0 auto;">

        <?php if ($role === 'employer') :
            $company = get_user_meta($user_id, 'jobs_company_data', true) ?: array();
            $logo = !empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 200));
            ?>
            <!-- EMPLOYER PROFILE VIEW -->
            <div class="employer-header-card" style="background: white; border-radius: 30px; padding: 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
                <div class="company-logo-wrap" style="width: 180px; height: 180px; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff; display: flex; align-items: center; justify-content: center; padding: 15px;">
                    <img src="<?php echo esc_url($logo); ?>" alt="Company Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div class="company-intro" style="flex: 1; min-width: 300px;">
                    <h1 style="font-size: 2.8em; color: #1d3469; margin: 0; font-weight: 700;"><?php echo esc_html($company['name'] ?? $display_name); ?></h1>
                    <div style="display: flex; gap: 20px; margin-top: 15px; color: #64748b; font-weight: 500;">
                        <span>🏢 <?php echo esc_html($company['industry'] ?? 'Industry not specified'); ?></span>
                        <span>👥 <?php echo esc_html($company['employee_count'] ?? 'Size not specified'); ?> employees</span>
                        <?php if(!empty($company['website'])): ?>
                            <a href="<?php echo esc_url($company['website']); ?>" target="_blank" style="color: #1d3469; text-decoration: none;">🔗 Official Website</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="company-actions">
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #1d3469; padding: 15px 35px; border-radius: 12px; font-weight: 600;">Contact Organization</button>
                </div>
            </div>

            <div class="employer-content-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 40px;">
                <div class="main-content">
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                        <h3 style="margin-top: 0; color: #1d3469; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 25px;">About the Organization</h3>
                        <div style="line-height: 1.8; color: #475569; white-space: pre-wrap;"><?php echo esc_html($company['details'] ?? 'No description available.'); ?></div>
                    </div>
                </div>
                <div class="sidebar">
                    <div class="section-card" style="background: #1d3469; border-radius: 24px; padding: 35px; color: white; text-align: center;">
                        <h4 style="margin: 0 0 15px; font-size: 1.3em;">Verified Employer</h4>
                        <p style="font-size: 0.9em; opacity: 0.9; line-height: 1.6;">This organization is a registered and verified employer on Jobedia.</p>
                        <div style="margin-top: 25px; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px;">
                            <div style="font-size: 0.8em; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.7;">Headquarters</div>
                            <div style="font-weight: 600; margin-top: 5px;"><?php echo esc_html(get_user_meta($user_id, '_country', true) ?: 'Global'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else :
            $cv = get_user_meta($user_id, 'jobs_cv_data_v2', true) ?: array();
            $spec = get_user_meta($user_id, '_specialization', true) ?: 'Professional';
            $prof = get_user_meta($user_id, '_profession', true);
            $exp_years = get_user_meta($user_id, '_experience', true);
            $academic = !empty($cv['academic']) ? $cv['academic'] : array();
            $experience = !empty($cv['experience']) ? $cv['experience'] : array();
            $skills = $cv['skills'] ?? array();
            $prefs = $cv['preferences'] ?? array();
            ?>
            <!-- JOB SEEKER PORTFOLIO VIEW -->
            <div class="portfolio-header" style="background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 15px 50px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div class="portfolio-banner" style="height: 200px; background: linear-gradient(135deg, #1d3469 0%, #2a4a8c 100%);"></div>
                <div class="portfolio-profile-bar" style="padding: 0 50px 40px; margin-top: -70px; display: flex; align-items: flex-end; gap: 35px; flex-wrap: wrap;">
                    <div class="avatar-wrap">
                        <img src="<?php echo get_avatar_url($user_id, array('size' => 160)); ?>" style="width: 160px; height: 160px; border-radius: 40px; border: 8px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: white;">
                    </div>
                    <div style="flex: 1; min-width: 300px;">
                        <h1 style="margin: 0; font-size: 2.6em; color: #1d3469; font-weight: 700;"><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?></h1>
                        <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <span style="background: #eff6ff; color: #1d3469; padding: 6px 16px; border-radius: 10px; font-weight: 600; font-size: 1.1em; border: 1px solid #dbeafe;"><?php echo esc_html($spec); ?></span>
                            <?php if($prof): ?>
                                <span style="color: #64748b; font-weight: 500;">Focus: <?php echo esc_html($prof); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="padding-bottom: 10px;">
                        <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #10b981; padding: 15px 35px; border-radius: 12px; font-weight: 600; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);">Send Job Offer</button>
                    </div>
                </div>
            </div>

            <div class="portfolio-content-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 40px;">
                <div class="main-content">

                    <!-- Bio -->
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px;">
                        <h3 style="margin-top: 0; color: #1d3469; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5em;">📄</span> Professional Summary
                        </h3>
                        <p style="line-height: 1.8; color: #475569; margin: 0;"><?php echo nl2br(esc_html(get_user_meta($user_id, '_bio', true) ?: 'No bio provided.')); ?></p>
                    </div>

                    <!-- Experience -->
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px;">
                        <h3 style="margin-top: 0; color: #1d3469; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5em;">💼</span> Professional Experience
                        </h3>
                        <?php if(!empty($experience)): foreach($experience as $exp): ?>
                            <div class="timeline-item" style="border-left: 2px solid #e2e8f0; padding-left: 25px; position: relative; margin-bottom: 30px;">
                                <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #1d3469; border: 3px solid white; box-shadow: 0 0 0 4px #eff6ff;"></div>
                                <h4 style="margin: 0; font-size: 1.2em; color: #1e293b;"><?php echo esc_html($exp['title']); ?></h4>
                                <div style="color: #4f46e5; font-weight: 600; margin: 5px 0;"><?php echo esc_html($exp['company']); ?></div>
                                <div style="font-size: 0.85em; color: #94a3b8; margin-bottom: 15px;"><?php echo esc_html($exp['start'] ?? ''); ?> - <?php echo esc_html($exp['end'] ?? 'Present'); ?></div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8;">No experience listed.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Academic -->
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px;">
                        <h3 style="margin-top: 0; color: #1d3469; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5em;">🎓</span> Academic Background
                        </h3>
                        <?php if(!empty($academic)): foreach($academic as $edu): ?>
                            <div style="display: flex; gap: 20px; margin-bottom: 25px;">
                                <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🏛️</div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.15em; color: #1e293b;"><?php echo esc_html($edu['degree']); ?></h4>
                                    <div style="color: #64748b; font-weight: 500;"><?php echo esc_html($edu['uni']); ?></div>
                                    <div style="font-size: 0.85em; color: #94a3b8; margin-top: 4px;">Class of <?php echo esc_html($edu['grad_date'] ?? 'N/A'); ?> • GPA: <?php echo esc_html($edu['gpa'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8;">No academic records listed.</p>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="sidebar">
                    <!-- Stats Card -->
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px; border: 1px solid #f1f5f9;">
                        <h4 style="margin: 0 0 20px; color: #1e293b; font-size: 1.1em; border-bottom: 2px solid #f8fafc; padding-bottom: 10px;">Quick Facts</h4>
                        <div class="fact-row" style="margin-bottom: 15px;">
                            <div style="font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Total Experience</div>
                            <div style="font-weight: 600; color: #1d3469;"><?php echo esc_html($exp_years ?: '0'); ?> Years</div>
                        </div>
                        <div class="fact-row" style="margin-bottom: 15px;">
                            <div style="font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Gender</div>
                            <div style="font-weight: 600; color: #1d3469;"><?php echo esc_html(get_user_meta($user_id, '_gender', true) ?: 'N/A'); ?></div>
                        </div>
                        <div class="fact-row">
                            <div style="font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Location</div>
                            <div style="font-weight: 600; color: #1d3469;"><?php echo esc_html(get_user_meta($user_id, '_region', true) ?: ''); ?>, <?php echo esc_html(get_user_meta($user_id, '_country', true) ?: 'Global'); ?></div>
                        </div>
                    </div>

                    <!-- Skills Card -->
                    <div class="section-card" style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px; border: 1px solid #f1f5f9;">
                        <h4 style="margin: 0 0 20px; color: #1e293b; font-size: 1.1em;">Key Skills</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                            $skill_list = explode(',', $skills['core'] ?? '');
                            foreach($skill_list as $s): if(trim($s)): ?>
                                <span style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; padding: 5px 12px; border-radius: 8px; font-size: 0.85em; font-weight: 600;"><?php echo trim($s); ?></span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Messaging Modal -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.4); backdrop-filter: blur(8px); z-index:9999; align-items:center; justify-content:center; padding: 20px;">
    <div class="modal-content" style="background:white; padding:40px; border-radius:30px; width:100%; max-width:500px; box-shadow:0 30px 60px rgba(0,0,0,0.12);">
        <h3 style="margin-top:0; color: #1d3469;">Message <?php echo esc_html($display_name); ?></h3>
        <textarea id="message-text" placeholder="Write your professional message here..." style="width:100%; height:150px; padding:15px; border-radius:15px; border:1px solid #e2e8f0; margin: 20px 0; font-family: inherit;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="jobs-btn-minimal close-modal" style="padding: 12px 25px;">Cancel</button>
            <button class="jobs-btn" id="confirm-send-message" style="background: #1d3469; padding: 12px 30px; border-radius: 10px;">Send Now</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.open-message-modal').on('click', function() {
        $('#message-modal').css('display', 'flex').hide().fadeIn(200);
    });
    $('.close-modal').on('click', function() {
        $('#message-modal').fadeOut(200);
    });
    $('#confirm-send-message').on('click', function() {
        var msg = $('#message-text').val();
        if(!msg) return;
        var btn = $(this);
        btn.prop('disabled', true).text('Sending...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_send_message',
            receiver_id: <?php echo $user_id; ?>,
            message: msg,
            nonce: '<?php echo wp_create_nonce("jobs_messaging_nonce"); ?>'
        }, function(res) {
            if(res.success) {
                $('#message-modal .modal-content').html('<div style="text-align:center; padding: 20px;"><h2>✅ Sent!</h2><p>Your message has been delivered.</p><button class="jobs-btn close-modal" style="margin-top: 20px; background: #1d3469;">Close</button></div>');
                $('.close-modal').on('click', function() { $('#message-modal').fadeOut(200); });
            } else {
                alert('Failed to send message.');
                btn.prop('disabled', false).text('Send Now');
            }
        });
    });
});
</script>
<?php get_footer(); ?>

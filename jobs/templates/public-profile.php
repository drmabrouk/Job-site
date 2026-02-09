<?php
/**
 * Template: Premium World-Class Professional Portfolio (Public Profile)
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
<div class="jobs-premium-portfolio-v3" style="background: #f8fafc; min-height: 100vh; padding: 140px 20px 100px; font-family: 'Rubik', sans-serif;">
    <div class="portfolio-container" style="max-width: 1100px; margin: 0 auto;">

        <?php if ($role === 'employer') :
            $company = get_user_meta($user_id, 'jobs_company_data', true) ?: array();
            $logo = !empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 200));
            $country = get_user_meta($user_id, '_country', true);
            $region = get_user_meta($user_id, '_region', true);
            ?>
            <!-- PREMIUM EMPLOYER PROFILE - NO COVER -->
            <div class="employer-header-premium" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 20px 50px rgba(0,0,0,0.04); display: flex; gap: 50px; align-items: center; flex-wrap: wrap; border: 1px solid #edf2f7; margin-bottom: 50px;">
                <div class="company-logo-premium" style="width: 160px; height: 160px; border-radius: 30px; overflow: hidden; border: 1px solid #f1f5f9; background: #fff; display: flex; align-items: center; justify-content: center; padding: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <img src="<?php echo esc_url($logo); ?>" alt="Company Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div class="company-main-info" style="flex: 1; min-width: 300px;">
                    <div style="display: inline-block; background: #dcfce7; color: #166534; padding: 5px 16px; border-radius: 50px; font-size: 0.8em; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.05em;">Verified Hiring Entity</div>
                    <h1 style="font-size: 3.2em; color: #1d3469; margin: 0; font-weight: 850; letter-spacing: -0.04em; line-height: 1.1;"><?php echo esc_html($company['name'] ?? $display_name); ?></h1>
                    <div style="display: flex; gap: 25px; margin-top: 20px; color: #64748b; font-weight: 600; font-size: 1.1em;">
                        <span>🏢 <?php echo esc_html($company['industry'] ?? 'Industry specified'); ?></span>
                        <span>👥 <?php echo esc_html($company['employee_count'] ?? '11-50'); ?> members</span>
                        <?php if($country): ?>
                            <span>📍 <?php echo esc_html($region ? $region.', '.$country : $country); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="company-cta-wrap">
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #1d3469; color: white; padding: 20px 45px; border-radius: 18px; font-weight: 700; font-size: 1.15em; border: none; cursor: pointer; box-shadow: 0 15px 30px rgba(29, 52, 105, 0.25);">Connect Directly</button>
                </div>
            </div>

            <div class="employer-body-grid" style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 50px;">
                <div class="main-content">
                    <div class="card" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; font-weight: 850; margin-bottom: 30px; border-left: 8px solid #1d3469; padding-left: 20px;">About the Organization</h3>
                        <div style="line-height: 2.1; color: #475569; font-size: 1.2em; white-space: pre-wrap;"><?php echo esc_html($company['details'] ?? 'Dedicated organization focused on growth and professional excellence.'); ?></div>

                        <?php if(!empty($company['website'])): ?>
                            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #f8fafc;">
                                <a href="<?php echo esc_url($company['website']); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; color: #1d3469; text-decoration: none; font-weight: 700; font-size: 1.1em; background: #f0f4f8; padding: 12px 25px; border-radius: 12px;">Visit Website <span class="dashicons dashicons-external"></span></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sidebar">
                    <div class="card" style="background: #1d3469; border-radius: 35px; padding: 50px; color: white; box-shadow: 0 25px 50px rgba(29, 52, 105, 0.25); text-align: center;">
                        <div style="font-size: 50px; margin-bottom: 20px;">🛡️</div>
                        <h4 style="margin: 0 0 15px; font-size: 1.6em; font-weight: 800;">Trusted Partner</h4>
                        <p style="font-size: 1.05em; opacity: 0.9; line-height: 1.8;">This organization is a fully registered member of our professional network since <?php echo date('Y', strtotime($user->user_registered)); ?>.</p>
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
            $country = get_user_meta($user_id, '_country', true);
            $region = get_user_meta($user_id, '_region', true);
            ?>
            <!-- PREMIUM SEEKER PORTFOLIO - NO COVER -->
            <div class="seeker-header-premium" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 20px 50px rgba(0,0,0,0.04); display: flex; gap: 50px; align-items: center; flex-wrap: wrap; border: 1px solid #edf2f7; margin-bottom: 50px;">
                <div class="p-avatar-premium">
                    <img src="<?php echo get_avatar_url($user_id, array('size' => 180)); ?>" style="width: 180px; height: 180px; border-radius: 40px; border: 2px solid #f1f5f9; background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.08); object-fit: cover;">
                </div>
                <div class="p-identity-premium" style="flex: 1; min-width: 350px;">
                    <div style="background: #eff6ff; color: #1d3469; padding: 6px 20px; border-radius: 50px; font-weight: 800; font-size: 0.9em; border: 1px solid #dbeafe; display: inline-block; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.05em;"><?php echo esc_html($spec); ?></div>
                    <h1 style="margin: 0; font-size: 3.5em; color: #1d3469; font-weight: 850; letter-spacing: -0.05em; line-height: 1.1;"><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?></h1>
                    <p style="margin: 15px 0 0; font-size: 1.6em; color: #64748b; font-weight: 500;"><?php echo esc_html($prof ?: 'Verified Expert'); ?></p>
                    <div style="margin-top: 25px; display: flex; gap: 20px; color: #94a3b8; font-weight: 600; font-size: 1em;">
                        <?php if($country): ?>
                            <span>📍 <?php echo esc_html($region ? $region.', '.$country : $country); ?></span>
                        <?php endif; ?>
                        <span>💼 <?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years</span>
                    </div>
                </div>
                <div class="p-cta-premium">
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #10b981; color: white; padding: 22px 55px; border-radius: 18px; font-weight: 700; font-size: 1.25em; border: none; box-shadow: 0 15px 30px rgba(16, 185, 129, 0.25); cursor: pointer;">Send Job Offer</button>
                </div>
            </div>

            <div class="seeker-body-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 50px;">
                <div class="main-col">

                    <div class="card" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; font-weight: 850; margin-bottom: 30px; border-left: 8px solid #1d3469; padding-left: 20px;">Professional Summary</h3>
                        <p style="line-height: 2.1; color: #475569; font-size: 1.25em; margin: 0;"><?php echo nl2br(esc_html(get_user_meta($user_id, '_bio', true) ?: 'Experienced professional dedicated to operational excellence and strategic field development.')); ?></p>
                    </div>

                    <div class="card" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; font-weight: 850; margin-bottom: 45px; border-left: 8px solid #1d3469; padding-left: 20px;">Work Experience</h3>
                        <?php if(!empty($experience)): foreach($experience as $exp): ?>
                            <div class="exp-premium-row" style="display: flex; gap: 35px; margin-bottom: 50px; position: relative;">
                                <div style="flex-shrink: 0; width: 75px; height: 75px; background: #f0f4f8; border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 32px; border: 1px solid #e2e8f0; color: #1d3469;">💼</div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; font-size: 1.6em; color: #1e293b; font-weight: 800;"><?php echo esc_html($exp['title']); ?></h4>
                                    <div style="color: #4f46e5; font-weight: 700; font-size: 1.2em; margin: 10px 0;"><?php echo esc_html($exp['company']); ?></div>
                                    <div style="font-size: 0.95em; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo esc_html($exp['start'] ?? ''); ?> — <?php echo esc_html($exp['end'] ?? 'Present'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8; font-size: 1.1em;">Career history not provided.</p>
                        <?php endif; ?>
                    </div>

                    <div class="card" style="background: white; border-radius: 35px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; font-weight: 850; margin-bottom: 45px; border-left: 8px solid #1d3469; padding-left: 20px;">Academic Background</h3>
                        <?php if(!empty($academic)): foreach($academic as $edu): ?>
                            <div class="edu-premium-row" style="display: flex; gap: 35px; margin-bottom: 40px;">
                                <div style="flex-shrink: 0; width: 75px; height: 75px; background: #eff6ff; border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 32px; border: 1px solid #dbeafe; color: #1d3469;">🎓</div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.5em; color: #1e293b; font-weight: 800;"><?php echo esc_html($edu['degree']); ?></h4>
                                    <div style="color: #64748b; font-weight: 600; font-size: 1.2em; margin-top: 8px;"><?php echo esc_html($edu['uni']); ?></div>
                                    <div style="font-size: 1em; color: #94a3b8; margin-top: 10px; font-weight: 500;">Class of <?php echo esc_html($edu['grad_date'] ?? 'N/A'); ?> <span style="margin: 0 10px; opacity: 0.3;">|</span> Achievement Index: <?php echo esc_html($edu['gpa'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8; font-size: 1.1em;">Education details not provided.</p>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="side-col">
                    <div class="card" style="background: white; border-radius: 35px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #edf2f7; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 30px; color: #1e293b; font-size: 1.4em; font-weight: 800; border-bottom: 3px solid #f8fafc; padding-bottom: 15px;">Expertise Stack</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                            <?php
                            $skill_list = explode(',', $skills['core'] ?? '');
                            foreach($skill_list as $s): if(trim($s)): ?>
                                <span style="background: #f1f5f9; color: #1d3469; padding: 12px 22px; border-radius: 18px; font-size: 1em; font-weight: 800; border: 1px solid rgba(29, 52, 105, 0.05);"><?php echo trim($s); ?></span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="card" style="background: #1d3469; border-radius: 35px; padding: 50px; color: white; box-shadow: 0 25px 60px rgba(29, 52, 105, 0.28); text-align: center;">
                        <div style="font-size: 60px; margin-bottom: 25px;">🚀</div>
                        <h4 style="margin: 0 0 15px; font-size: 1.7em; font-weight: 850;">Open for Opportunities</h4>
                        <p style="font-size: 1.1em; opacity: 0.9; line-height: 1.8; margin-bottom: 35px;">This professional is currently vetted and available for high-impact roles.</p>
                        <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: white; color: #1d3469; width: 100%; border: none; font-weight: 850; padding: 22px; border-radius: 20px; font-size: 1.2em; cursor: pointer; transition: transform 0.2s;">Direct Offer</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Premium Global Modal -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.5); backdrop-filter: blur(15px); z-index:9999; align-items:center; justify-content:center; padding: 20px;">
    <div class="modal-content" style="background:white; padding:65px; border-radius:45px; width:100%; max-width:650px; box-shadow:0 50px 120px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.4);">
        <h3 style="margin-top:0; color: #1d3469; font-size: 2.2em; font-weight: 900; letter-spacing: -0.02em;">Contact <?php echo esc_html($display_name); ?></h3>
        <p style="color: #64748b; margin-top: 15px; font-size: 1.2em; font-weight: 500;">Initiate a professional conversation or present a direct career opportunity.</p>
        <textarea id="message-text" placeholder="Detail your inquiry or job offer here..." style="width:100%; height:220px; padding:30px; border-radius:25px; border:2px solid #edf2f7; margin: 40px 0; font-family: inherit; font-size: 1.15em; background: #f8fafc; outline: none; transition: border-color 0.3s;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:25px;">
            <button class="jobs-btn-minimal close-modal" style="padding: 20px 40px; font-weight: 800; color: #94a3b8; border: none; background: none; cursor: pointer; font-size: 1.1em;">Discard</button>
            <button class="jobs-btn" id="confirm-send-message" style="background: #1d3469; color: white; padding: 20px 55px; border-radius: 20px; font-weight: 850; border: none; cursor: pointer; font-size: 1.1em; box-shadow: 0 15px 30px rgba(29, 52, 105, 0.2);">Deliver Message</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.open-message-modal').on('click', function() {
        $('#message-modal').css('display', 'flex').hide().fadeIn(400);
    });
    $('.close-modal').on('click', function() {
        $('#message-modal').fadeOut(300);
    });
    $('#confirm-send-message').on('click', function() {
        var msg = $('#message-text').val();
        if(!msg) return;
        var btn = $(this);
        btn.prop('disabled', true).text('Delivering...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_send_message',
            receiver_id: <?php echo $user_id; ?>,
            message: msg,
            nonce: '<?php echo wp_create_nonce("jobs_messaging_nonce"); ?>'
        }, function(res) {
            if(res.success) {
                $('#message-modal .modal-content').html('<div style="text-align:center; padding: 60px;"><div style="font-size: 100px; margin-bottom: 40px;">✨</div><h2 style="color: #1d3469; font-weight: 900; font-size: 2.2em;">Inquiry Delivered!</h2><p style="color: #64748b; font-size: 1.25em; font-weight: 500;">Your professional communication has been successfully transmitted.</p><button class="jobs-btn close-modal" style="margin-top: 50px; background: #1d3469; color: white; padding: 18px 50px; border-radius: 15px; font-weight: 800; border: none;">Return to Profile</button></div>');
                $('.close-modal').on('click', function() { $('#message-modal').fadeOut(300); });
            } else {
                alert('Communication failed. Please try again.');
                btn.prop('disabled', false).text('Deliver Message');
            }
        });
    });
});
</script>

<style>
@media (max-width: 900px) {
    .seeker-body-grid, .employer-body-grid { grid-template-columns: 1fr; }
    .seeker-header-premium, .employer-header-premium { padding: 40px; text-align: center; justify-content: center; }
    .p-identity-premium, .company-main-info { text-align: center; }
    .p-cta-premium, .company-cta-wrap { width: 100%; }
}
</style>

<?php get_footer(); ?>

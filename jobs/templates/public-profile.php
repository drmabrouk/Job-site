<?php
/**
 * Template: Highly Professional Premium Portfolio (Public Profile)
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
<div class="jobs-premium-portfolio" style="background: #f0f4f8; min-height: 100vh; padding: 120px 20px 80px; font-family: 'Rubik', sans-serif; color: #1e293b;">
    <div class="portfolio-wrapper" style="max-width: 1200px; margin: 0 auto;">

        <?php if ($role === 'employer') :
            $company = get_user_meta($user_id, 'jobs_company_data', true) ?: array();
            $logo = !empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 200));
            ?>
            <!-- PREMIUM EMPLOYER PROFILE -->
            <div class="employer-hero" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 20px 60px rgba(0,0,0,0.05); border: 1px solid rgba(29, 52, 105, 0.05); margin-bottom: 50px; display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
                <div class="employer-logo-frame" style="width: 200px; height: 200px; border-radius: 35px; background: #fff; border: 1px solid #edf2f7; display: flex; align-items: center; justify-content: center; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <img src="<?php echo esc_url($logo); ?>" alt="Organization Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div class="employer-meta" style="flex: 1; min-width: 350px;">
                    <div style="display: inline-block; background: #dcfce7; color: #166534; padding: 6px 18px; border-radius: 50px; font-size: 0.85em; font-weight: 700; text-transform: uppercase; margin-bottom: 20px;">Verified Organization</div>
                    <h1 style="font-size: 3.2em; color: #1d3469; margin: 0; font-weight: 800; letter-spacing: -0.03em;"><?php echo esc_html($company['name'] ?? $display_name); ?></h1>
                    <div style="display: flex; gap: 30px; margin-top: 20px; color: #64748b; font-size: 1.1em; font-weight: 500;">
                        <span>🏢 <?php echo esc_html($company['industry'] ?? 'General Industry'); ?></span>
                        <span>👥 <?php echo esc_html($company['employee_count'] ?? '11-50'); ?> employees</span>
                        <?php if(!empty($company['website'])): ?>
                            <a href="<?php echo esc_url($company['website']); ?>" target="_blank" style="color: #1d3469; text-decoration: none; border-bottom: 2px solid rgba(29, 52, 105, 0.1);">Official Website</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="employer-cta">
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #1d3469; color: white; padding: 20px 45px; border-radius: 16px; font-weight: 700; font-size: 1.15em; border: none; box-shadow: 0 15px 30px rgba(29, 52, 105, 0.2);">Connect With Us</button>
                </div>
            </div>

            <div class="employer-layout" style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 50px;">
                <div class="employer-main">
                    <div class="card" style="background: white; border-radius: 30px; padding: 50px; box-shadow: 0 4px 30px rgba(0,0,0,0.02);">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; margin-bottom: 30px; display: flex; align-items: center; gap: 15px;">
                            <span style="font-size: 1.3em;">📄</span> Organizational Profile
                        </h3>
                        <div style="line-height: 2; color: #475569; font-size: 1.15em; white-space: pre-wrap;"><?php echo esc_html($company['details'] ?? 'We are dedicated to building great teams and providing professional opportunities.'); ?></div>
                    </div>
                </div>
                <div class="employer-side">
                    <div class="card" style="background: #1d3469; border-radius: 30px; padding: 45px; color: white; box-shadow: 0 25px 50px rgba(29, 52, 105, 0.2);">
                        <h4 style="margin: 0 0 20px; font-size: 1.5em; font-weight: 700;">Hiring Location</h4>
                        <div style="background: rgba(255,255,255,0.08); padding: 25px; border-radius: 20px; margin-bottom: 30px;">
                            <div style="font-size: 0.85em; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.1em;">Primary Region</div>
                            <div style="font-size: 1.3em; font-weight: 600; margin-top: 8px;"><?php echo esc_html(get_user_meta($user_id, '_region', true) ?: ''); ?>, <?php echo esc_html(get_user_meta($user_id, '_country', true) ?: 'Global'); ?></div>
                        </div>
                        <p style="font-size: 1em; opacity: 0.85; line-height: 1.7;">Active on Jobedia since <?php echo date('F Y', strtotime($user->user_registered)); ?>.</p>
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
            ?>
            <!-- PREMIUM SEEKER PORTFOLIO -->
            <div class="seeker-hero-card" style="background: white; border-radius: 40px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.06); margin-bottom: 50px; border: 1px solid #e2e8f0; position: relative;">
                <div class="hero-banner" style="height: 250px; background: linear-gradient(135deg, #1d3469 0%, #315096 100%);"></div>
                <div class="hero-profile-wrap" style="padding: 0 60px 60px; margin-top: -100px; display: flex; align-items: flex-end; gap: 45px; flex-wrap: wrap;">
                    <div class="p-avatar">
                        <img src="<?php echo get_avatar_url($user_id, array('size' => 200)); ?>" style="width: 200px; height: 200px; border-radius: 50px; border: 10px solid white; background: white; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    </div>
                    <div class="p-identity" style="flex: 1; min-width: 350px;">
                        <div style="background: #eff6ff; color: #1d3469; padding: 6px 20px; border-radius: 50px; font-weight: 700; font-size: 1em; border: 1px solid #dbeafe; display: inline-block; margin-bottom: 15px;"><?php echo esc_html($spec); ?></div>
                        <h1 style="margin: 0; font-size: 3.5em; color: #1d3469; font-weight: 800; letter-spacing: -0.04em;"><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?></h1>
                        <p style="margin: 12px 0 0; font-size: 1.4em; color: #64748b; font-weight: 500;"><?php echo esc_html($prof ?: 'Verified Professional'); ?></p>
                    </div>
                    <div class="p-cta" style="padding-bottom: 20px;">
                        <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #10b981; color: white; padding: 20px 50px; border-radius: 18px; font-weight: 700; font-size: 1.15em; border: none; box-shadow: 0 15px 30px rgba(16, 185, 129, 0.2);">Hire This Candidate</button>
                    </div>
                </div>
            </div>

            <div class="seeker-layout" style="display: grid; grid-template-columns: 1fr 380px; gap: 50px;">
                <div class="seeker-main">

                    <!-- Bio Section -->
                    <div class="card" style="background: white; border-radius: 35px; padding: 50px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); margin-bottom: 40px; border: 1px solid #f1f5f9;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; margin-bottom: 25px; border-left: 6px solid #1d3469; padding-left: 20px;">Executive Summary</h3>
                        <p style="line-height: 2; color: #475569; font-size: 1.2em; margin: 0;"><?php echo nl2br(esc_html(get_user_meta($user_id, '_bio', true) ?: 'Strategic professional with a focus on excellence and delivering high-quality results in my field.')); ?></p>
                    </div>

                    <!-- Work History -->
                    <div class="card" style="background: white; border-radius: 35px; padding: 50px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); margin-bottom: 40px; border: 1px solid #f1f5f9;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; margin-bottom: 40px; border-left: 6px solid #1d3469; padding-left: 20px;">Work Experience</h3>
                        <?php if(!empty($experience)): foreach($experience as $exp): ?>
                            <div class="exp-row" style="display: flex; gap: 30px; margin-bottom: 45px; position: relative;">
                                <div style="flex-shrink: 0; width: 70px; height: 70px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; border: 1px solid #edf2f7;">💼</div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; font-size: 1.5em; color: #1e293b; font-weight: 700;"><?php echo esc_html($exp['title']); ?></h4>
                                    <div style="color: #4f46e5; font-weight: 700; font-size: 1.1em; margin: 8px 0;"><?php echo esc_html($exp['company']); ?></div>
                                    <div style="font-size: 0.95em; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;"><?php echo esc_html($exp['start'] ?? ''); ?> - <?php echo esc_html($exp['end'] ?? 'Present'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8;">Career history not provided.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Academic -->
                    <div class="card" style="background: white; border-radius: 35px; padding: 50px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 1.8em; margin-bottom: 40px; border-left: 6px solid #1d3469; padding-left: 20px;">Educational Credentials</h3>
                        <?php if(!empty($academic)): foreach($academic as $edu): ?>
                            <div class="edu-row" style="display: flex; gap: 30px; margin-bottom: 35px;">
                                <div style="flex-shrink: 0; width: 70px; height: 70px; background: #eff6ff; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; border: 1px solid #dbeafe;">🎓</div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.4em; color: #1e293b; font-weight: 700;"><?php echo esc_html($edu['degree']); ?></h4>
                                    <div style="color: #64748b; font-weight: 600; font-size: 1.1em; margin-top: 5px;"><?php echo esc_html($edu['uni']); ?></div>
                                    <div style="font-size: 0.95em; color: #94a3b8; margin-top: 8px; font-weight: 500;">Graduated: <?php echo esc_html($edu['grad_date'] ?? 'N/A'); ?> • Achievement Index: <?php echo esc_html($edu['gpa'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8;">Education details not provided.</p>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="seeker-side">
                    <!-- Fact Card -->
                    <div class="card" style="background: white; border-radius: 30px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); margin-bottom: 40px; border: 1px solid #f1f5f9;">
                        <h4 style="margin: 0 0 30px; color: #1e293b; font-size: 1.3em; font-weight: 700; border-bottom: 2px solid #f8fafc; padding-bottom: 15px;">Professional Snapshot</h4>
                        <div style="display: grid; gap: 25px;">
                            <div class="snapshot-item">
                                <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Experience</div>
                                <div style="font-size: 1.2em; font-weight: 700; color: #1d3469;"><?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years</div>
                            </div>
                            <div class="snapshot-item">
                                <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Current Residence</div>
                                <div style="font-size: 1.2em; font-weight: 700; color: #1d3469;"><?php echo esc_html(get_user_meta($user_id, '_region', true) ?: ''); ?>, <?php echo esc_html(get_user_meta($user_id, '_country', true) ?: 'Global'); ?></div>
                            </div>
                            <div class="snapshot-item">
                                <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Nationality</div>
                                <div style="font-size: 1.2em; font-weight: 700; color: #1d3469;"><?php echo esc_html(get_user_meta($user_id, '_nationality', true) ?: 'International'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Card -->
                    <div class="card" style="background: white; border-radius: 30px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.3em; font-weight: 700;">Expertise Portfolio</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                            <?php
                            $skill_list = explode(',', $skills['core'] ?? '');
                            foreach($skill_list as $s): if(trim($s)): ?>
                                <span style="background: #f1f5f9; color: #1d3469; padding: 10px 20px; border-radius: 15px; font-size: 0.95em; font-weight: 700; border: 1px solid rgba(29, 52, 105, 0.05);"><?php echo trim($s); ?></span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Premium Messaging Modal -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.4); backdrop-filter: blur(12px); z-index:9999; align-items:center; justify-content:center; padding: 20px;">
    <div class="modal-content" style="background:white; padding:60px; border-radius:40px; width:100%; max-width:600px; box-shadow:0 40px 100px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.3);">
        <h3 style="margin-top:0; color: #1d3469; font-size: 2em; font-weight: 800;">Contact <?php echo esc_html($display_name); ?></h3>
        <p style="color: #64748b; margin-top: 10px; font-size: 1.1em;">Send a professional message or a direct job invitation.</p>
        <textarea id="message-text" placeholder="Write your professional message here..." style="width:100%; height:200px; padding:25px; border-radius:20px; border:1px solid #e2e8f0; margin: 30px 0; font-family: inherit; font-size: 1.1em; background: #f8fafc; outline: none; transition: border-color 0.2s;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:20px;">
            <button class="jobs-btn-minimal close-modal" style="padding: 18px 35px; font-weight: 700; color: #64748b; border: none; background: none; cursor: pointer;">Cancel</button>
            <button class="jobs-btn" id="confirm-send-message" style="background: #1d3469; color: white; padding: 18px 45px; border-radius: 14px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(29, 52, 105, 0.15);">Send Message Now</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.open-message-modal').on('click', function() {
        $('#message-modal').css('display', 'flex').hide().fadeIn(300);
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
                $('#message-modal .modal-content').html('<div style="text-align:center; padding: 40px;"><div style="font-size: 80px; margin-bottom: 30px;">✅</div><h2 style="color: #1d3469; font-weight: 800;">Message Delivered!</h2><p style="color: #64748b; font-size: 1.1em;">Your professional inquiry has been sent successfully.</p><button class="jobs-btn close-modal" style="margin-top: 40px; background: #1d3469; color: white; padding: 15px 40px; border-radius: 12px;">Close</button></div>');
                $('.close-modal').on('click', function() { $('#message-modal').fadeOut(300); });
            } else {
                alert('Failed to send message.');
                btn.prop('disabled', false).text('Send Message Now');
            }
        });
    });
});
</script>
<?php get_footer(); ?>

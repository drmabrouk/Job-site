<?php
/**
 * Template for Public User Profile (Professional Portfolio View)
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

if ( $profile_slug ) {
    $user = get_user_by('slug', $profile_slug);
} else {
    // Fallback if accessed via direct ID query (legacy/internal)
    $user_id = get_query_var('job_user_id');
    if ($user_id) $user = get_userdata($user_id);
}

if ( empty($user) ) {
    wp_die('User not found.');
}
$user_id = $user->ID;

$cv = get_user_meta( $user_id, 'jobs_cv_data_v2', true ) ?: array();
$specialization = get_user_meta($user_id, '_specialization', true) ?: 'Professional';
$is_verified = get_user_meta($user_id, '_is_verified', true);

// Normalize multi-entry fields
$academic_list = !empty($cv['academic']) && is_array($cv['academic']) && isset($cv['academic'][0]) ? $cv['academic'] : ( !empty($cv['academic']['uni']) ? array($cv['academic']) : array() );
$experience_list = !empty($cv['experience']) && is_array($cv['experience']) && isset($cv['experience'][0]) ? $cv['experience'] : ( !empty($cv['experience']['company']) ? array($cv['experience']) : array() );

get_header();
?>

<div class="jobs-container portfolio-view" style="padding-top: 100px; padding-bottom: 80px; background: #f0f4f8; min-height: 100vh;">

    <!-- Professional Header Banner -->
    <div class="portfolio-header-card" style="background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.06); margin-bottom: 40px; position: relative; border: 1px solid rgba(29, 52, 105, 0.05);">
        <div class="portfolio-banner" style="height: 220px; background: linear-gradient(135deg, #1d3469 0%, #2e59a8 100%);"></div>
        <div class="portfolio-info-bar" style="padding: 0 50px 40px; margin-top: -80px; display: flex; align-items: flex-end; gap: 35px; flex-wrap: wrap;">
            <div class="portfolio-avatar-wrap" style="position: relative;">
                <?php echo get_avatar($user_id, 160, '', '', array('class' => 'portfolio-avatar', 'style' => 'border: 8px solid white; border-radius: 40px; background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.1);')); ?>
                <?php if($is_verified): ?>
                    <div style="position: absolute; bottom: 15px; right: 15px; background: #3b82f6; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid white; font-size: 14px;" title="Verified Expert">✓</div>
                <?php endif; ?>
            </div>
            <div style="flex: 1; min-width: 300px; padding-bottom: 10px;">
                <h1 style="margin: 0; font-size: 2.6em; color: #1d3469; font-weight: 700; letter-spacing: -0.02em;"><?php echo esc_html($cv['personal']['full_name'] ?? $user->display_name); ?></h1>
                <p style="margin: 8px 0 0; color: #64748b; font-size: 1.25em; font-weight: 500;">
                    <?php echo esc_html($specialization); ?>
                </p>
                <div style="display: flex; gap: 15px; margin-top: 15px; color: #94a3b8; font-size: 0.9em; font-weight: 500;">
                    <span><span class="dashicons dashicons-location" style="font-size: 18px; width: 18px; height: 18px;"></span> <?php echo esc_html($cv['personal']['city'] ?? ''); ?>, <?php echo esc_html($cv['personal']['country'] ?? ''); ?></span>
                    <span><span class="dashicons dashicons-calendar-alt" style="font-size: 18px; width: 18px; height: 18px;"></span> Member since <?php echo date('Y', strtotime($user->user_registered)); ?></span>
                </div>
            </div>
            <div style="padding-bottom: 20px;">
                <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: var(--jobs-primary-color); padding: 16px 35px; font-size: 1.1em; border-radius: 14px; box-shadow: 0 10px 20px rgba(29, 52, 105, 0.2);">Inquire / Contact</button>
            </div>
        </div>
    </div>

    <div class="portfolio-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">

        <!-- Main Portfolio Content -->
        <div class="portfolio-main-col">

            <!-- Professional Experience (Multi) -->
            <div class="portfolio-section-card" style="background: white; border-radius: 24px; padding: 40px; margin-bottom: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
                <h3 style="margin-top: 0; font-size: 1.6em; color: #1d3469; display: flex; align-items: center; gap: 15px; margin-bottom: 35px;">
                    <span style="background: #fff7ed; color: #ea580c; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">💼</span> Professional Experience
                </h3>

                <?php if(!empty($experience_list)): foreach($experience_list as $exp): ?>
                <div class="experience-entry" style="border-left: 2px solid #e2e8f0; padding-left: 30px; position: relative; margin-bottom: 40px;">
                    <div style="position: absolute; left: -9px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #ea580c; border: 4px solid white; box-shadow: 0 0 0 4px #fff7ed;"></div>
                    <div style="font-weight: 700; font-size: 1.25em; color: #1e293b;"><?php echo esc_html($exp['title']); ?></div>
                    <div style="color: #4338ca; font-weight: 600; margin: 6px 0; font-size: 1.1em;"><?php echo esc_html($exp['company']); ?> <span style="color: #94a3b8; font-weight: 400; margin: 0 10px;">•</span> <?php echo esc_html($exp['type'] ?? 'Full-time'); ?></div>
                    <div style="font-size: 0.95em; color: #64748b; font-weight: 500; margin-bottom: 15px;">
                        <?php echo date('M Y', strtotime($exp['start'])); ?> - <?php echo !empty($exp['end']) ? date('M Y', strtotime($exp['end'])) : 'Present'; ?>
                    </div>

                    <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                        <strong style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; display: block; margin-bottom: 10px;">Core Responsibilities</strong>
                        <p style="margin: 0; font-size: 1em; color: #475569; line-height: 1.7; white-space: pre-wrap;"><?php echo esc_html($exp['tasks'] ?? ''); ?></p>

                        <?php if(!empty($exp['achievements'])): ?>
                        <div style="margin-top: 20px; border-top: 1px dashed #e2e8f0; padding-top: 20px;">
                            <strong style="font-size: 0.8em; text-transform: uppercase; color: #059669; letter-spacing: 0.05em; display: block; margin-bottom: 8px;">Key Achievements</strong>
                            <p style="margin: 0; font-size: 0.95em; color: #475569; font-style: italic;">"<?php echo esc_html($exp['achievements']); ?>"</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <p style="color: #94a3b8;">No professional experience listed.</p>
                <?php endif; ?>
            </div>

            <!-- Academic Qualifications (Multi) -->
            <div class="portfolio-section-card" style="background: white; border-radius: 24px; padding: 40px; margin-bottom: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
                <h3 style="margin-top: 0; font-size: 1.6em; color: #1d3469; display: flex; align-items: center; gap: 15px; margin-bottom: 35px;">
                    <span style="background: #eef2ff; color: #4338ca; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">🎓</span> Academic Background
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 30px;">
                    <?php if(!empty($academic_list)): foreach($academic_list as $edu): ?>
                    <div class="edu-entry" style="display: flex; gap: 20px;">
                        <div style="flex-shrink: 0; width: 60px; height: 60px; background: #f8fafc; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; border: 1px solid #e2e8f0;">🏛️</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 1.2em; color: #1e293b;"><?php echo esc_html($edu['degree']); ?> in <?php echo esc_html($edu['spec_main']); ?></div>
                            <div style="color: #64748b; font-weight: 500; margin: 4px 0;"><?php echo esc_html($edu['uni']); ?></div>
                            <div style="font-size: 0.9em; color: #94a3b8; font-weight: 500;">Class of <?php echo date('Y', strtotime($edu['grad_date'] ?? 'now')); ?> <span style="margin: 0 8px;">|</span> GPA: <?php echo esc_html($edu['gpa'] ?? 'N/A'); ?></div>

                            <?php if(!empty($edu['grad_project'])): ?>
                            <div style="margin-top: 12px; font-size: 0.9em; color: #475569;">
                                <span style="font-weight: 600; color: #1d3469;">Graduation Project:</span> <?php echo esc_html($edu['grad_project']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                        <p style="color: #94a3b8;">No academic qualifications listed.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Skills & Portfolio Tags -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
                <div class="portfolio-section-card" style="background: white; border-radius: 24px; padding: 35px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                    <h4 style="margin: 0 0 25px; color: #1d3469; font-size: 1.2em;">Expertise & Skills</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <?php
                        $core_skills = explode(',', $cv['skills']['core'] ?? '');
                        foreach($core_skills as $skill): if(trim($skill)): ?>
                            <span style="background: #f1f5f9; color: #1d3469; padding: 8px 16px; border-radius: 10px; font-size: 0.9em; font-weight: 600; border: 1px solid rgba(29, 52, 105, 0.05);"><?php echo trim($skill); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <div class="portfolio-section-card" style="background: white; border-radius: 24px; padding: 35px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                    <h4 style="margin: 0 0 25px; color: #1d3469; font-size: 1.2em;">Linguistic Proficiency</h4>
                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #fcfcfd; padding: 12px 18px; border-radius: 12px;">
                            <span style="font-weight: 600; color: #475569;"><?php echo esc_html($cv['languages']['native'] ?? 'Native'); ?></span>
                            <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.75em; font-weight: 700; text-transform: uppercase;">Mother Tongue</span>
                        </div>
                        <?php if(!empty($cv['languages']['other'])): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #fcfcfd; padding: 12px 18px; border-radius: 12px;">
                            <span style="font-weight: 600; color: #475569;"><?php echo esc_html($cv['languages']['other']); ?></span>
                            <span style="background: #eff6ff; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 0.75em; font-weight: 700; text-transform: uppercase;"><?php echo esc_html($cv['languages']['speak'] ?? 'Fluent'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Portfolio Sidebar -->
        <div class="portfolio-sidebar-col">

            <!-- Quick Professional Facts -->
            <div class="portfolio-sidebar-card" style="background: white; border-radius: 24px; padding: 35px; border: 1px solid #f1f5f9; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.2em; border-bottom: 2px solid #f8fafc; padding-bottom: 15px;">Professional Outlook</h4>

                <div class="fact-row" style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.05em;">Career Level</label>
                    <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo esc_html($cv['experience'][0]['title'] ?? 'Professional'); ?></span>
                </div>

                <div class="fact-row" style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.05em;">Total Experience</label>
                    <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo get_user_meta($user_id, '_experience', true); ?> Years</span>
                </div>

                <div class="fact-row" style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.05em;">Availability</label>
                    <span style="background: #ecfdf5; color: #065f46; padding: 6px 14px; border-radius: 12px; font-size: 0.9em; font-weight: 700;"><?php echo esc_html($cv['preferences']['availability'] ?? 'Available Now'); ?></span>
                </div>

                <div class="fact-row">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.05em;">Work Setting</label>
                    <span style="font-weight: 700; color: #1d3469;"><?php echo esc_html($cv['preferences']['flexibility'] ?? 'On-site'); ?></span>
                </div>
            </div>

            <!-- Portfolio CTA -->
            <div class="portfolio-cta-card" style="background: #1d3469; border-radius: 24px; padding: 40px; color: white; text-align: center; box-shadow: 0 20px 40px rgba(29, 52, 105, 0.2);">
                <div style="font-size: 40px; margin-bottom: 15px;">🛡️</div>
                <h4 style="margin: 0 0 12px; font-size: 1.4em; font-weight: 700;">Verified Portfolio</h4>
                <p style="font-size: 0.95em; opacity: 0.85; margin-bottom: 30px; line-height: 1.6;">This profile serves as a verified professional portfolio. All data is confirmed by the system.</p>
                <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: white; color: #1d3469; width: 100%; border: none; font-weight: 700; padding: 16px; border-radius: 14px; font-size: 1.1em;">Send Direct Offer</button>
            </div>

        </div>

    </div>
</div>

<!-- Professional Messaging Modal -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.4); backdrop-filter: blur(8px); z-index:9999; align-items:center; justify-content:center; padding: 20px;">
    <div class="modal-content" style="background:white; padding:45px; border-radius:30px; width:100%; max-width:550px; box-shadow:0 30px 70px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.2);">
        <h3 style="margin-top:0; font-size: 1.8em; color: #1d3469; margin-bottom: 10px;">Contact <?php echo esc_html($user->display_name); ?></h3>
        <p style="color: #64748b; margin-bottom: 30px;">Express your interest or send a formal interview invitation.</p>
        <textarea id="message-text" placeholder="Write your professional message or job offer details here..." style="width:100%; height:180px; padding:20px; border-radius:18px; border:1px solid #e2e8f0; margin-bottom:25px; font-family: inherit; font-size: 1em; background: #f8fafc;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:15px;">
            <button class="jobs-btn-minimal close-modal" style="padding: 14px 25px; font-weight: 600;">Discard</button>
            <button class="jobs-btn" id="confirm-send-message" style="padding: 14px 35px; border-radius: 12px; background: #1d3469;">Send Message Now</button>
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
        btn.prop('disabled', true).text('Sending...');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_send_message',
            receiver_id: <?php echo $user_id; ?>,
            message: msg,
            nonce: '<?php echo wp_create_nonce("jobs_messaging_nonce"); ?>'
        }, function(res) {
            if(res.success) {
                $('#message-modal').find('.modal-content').html('<div style="text-align:center; padding: 40px;"><div style="font-size: 50px; margin-bottom: 20px;">✅</div><h3 style="color:#1d3469;">Message Sent!</h3><p>Your message has been delivered to the candidate.</p><button class="jobs-btn close-modal" style="margin-top: 20px; background:#1d3469;">Close</button></div>');
                $('.close-modal').on('click', function() { $('#message-modal').fadeOut(300); });
            } else {
                alert('Error sending message.');
                btn.prop('disabled', false).text('Send Message Now');
            }
        });
    });
});
</script>

<?php get_footer(); ?>

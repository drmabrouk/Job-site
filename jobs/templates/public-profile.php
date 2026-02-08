<?php
/**
 * Template for Public User Profile (Professional CV View)
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

get_header();
?>

<div class="jobs-container" style="padding-top: 100px; padding-bottom: 80px; background: #f8fafc; min-height: 100vh;">

    <!-- Profile Header -->
    <div class="profile-header-card" style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 30px; position: relative;">
        <div class="profile-banner" style="height: 180px; background: linear-gradient(135deg, #1d3469 0%, #2e59a8 100%);"></div>
        <div class="profile-info-bar" style="padding: 0 40px 30px; margin-top: -60px; display: flex; align-items: flex-end; gap: 30px;">
            <div class="profile-avatar-wrap" style="position: relative;">
                <?php echo get_avatar($user_id, 140, '', '', array('class' => 'profile-avatar', 'style' => 'border: 6px solid white; border-radius: 30px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.1);')); ?>
                <?php if($is_verified): ?>
                    <div style="position: absolute; bottom: 10px; right: 10px; background: #3b82f6; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white;" title="Verified Professional">✓</div>
                <?php endif; ?>
            </div>
            <div style="flex: 1; padding-bottom: 10px;">
                <h1 style="margin: 0; font-size: 2.2em; color: #1e293b;"><?php echo esc_html($cv['personal']['full_name'] ?? $user->display_name); ?></h1>
                <p style="margin: 5px 0 0; color: #64748b; font-size: 1.1em; font-weight: 500;">
                    <?php echo esc_html($cv['experience']['title'] ?? $specialization); ?>
                    <?php if(!empty($cv['experience']['company'])): ?> @ <?php echo esc_html($cv['experience']['company']); ?><?php endif; ?>
                </p>
            </div>
            <div style="padding-bottom: 15px; display: flex; gap: 12px;">
                <?php if(!empty($cv['personal']['linkedin'])): ?>
                    <a href="<?php echo esc_url($cv['personal']['linkedin']); ?>" target="_blank" class="jobs-btn-minimal" style="padding: 10px 15px;">LinkedIn</a>
                <?php endif; ?>
                <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: var(--jobs-primary-color);">Contact Candidate</button>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

        <!-- Main Content -->
        <div class="profile-main-col">

            <!-- Academic Qualifications -->
            <?php if(!empty($cv['academic']['uni_1'])): ?>
            <div class="profile-section-card" style="background: white; border-radius: 20px; padding: 35px; margin-bottom: 30px; border: 1px solid #f1f5f9;">
                <h3 style="margin-top: 0; font-size: 1.4em; color: #1d3469; display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <span style="background: #e0e7ff; color: #4338ca; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.8em;">🎓</span> Academic Qualifications
                </h3>
                <div class="academic-item" style="border-left: 2px solid #e2e8f0; padding-left: 25px; position: relative;">
                    <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #4338ca; border: 3px solid white;"></div>
                    <div style="font-weight: 700; font-size: 1.1em; color: #1e293b;"><?php echo esc_html($cv['academic']['degree_1']); ?> in <?php echo esc_html($cv['academic']['spec_main']); ?></div>
                    <div style="color: #64748b; margin: 5px 0;"><?php echo esc_html($cv['academic']['uni_1']); ?></div>
                    <div style="font-size: 0.9em; color: #94a3b8; font-weight: 500;">Graduated: <?php echo esc_html($cv['academic']['grad_date'] ?? 'N/A'); ?> | GPA: <?php echo esc_html($cv['academic']['gpa'] ?? 'N/A'); ?></div>

                    <?php if(!empty($cv['academic']['grad_project'])): ?>
                        <div style="margin-top: 15px; background: #f8fafc; padding: 15px; border-radius: 12px;">
                            <strong style="font-size: 0.85em; color: #475569; display: block; margin-bottom: 5px;">Graduation Project:</strong>
                            <p style="margin: 0; font-size: 0.95em; color: #334155;"><?php echo esc_html($cv['academic']['grad_project']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Professional Experience -->
            <?php if(!empty($cv['experience']['company'])): ?>
            <div class="profile-section-card" style="background: white; border-radius: 20px; padding: 35px; margin-bottom: 30px; border: 1px solid #f1f5f9;">
                <h3 style="margin-top: 0; font-size: 1.4em; color: #1d3469; display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <span style="background: #fef3c7; color: #d97706; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.8em;">💼</span> Professional Experience
                </h3>
                <div class="experience-item" style="border-left: 2px solid #e2e8f0; padding-left: 25px; position: relative;">
                    <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #d97706; border: 3px solid white;"></div>
                    <div style="font-weight: 700; font-size: 1.1em; color: #1e293b;"><?php echo esc_html($cv['experience']['title']); ?></div>
                    <div style="color: #64748b; margin: 5px 0;"><?php echo esc_html($cv['experience']['company']); ?> • <?php echo esc_html($cv['experience']['type']); ?></div>
                    <div style="font-size: 0.9em; color: #94a3b8; font-weight: 500;">
                        <?php echo esc_html($cv['experience']['start']); ?> - <?php echo esc_html($cv['experience']['end'] ?: 'Present'); ?>
                        (<?php echo esc_html($cv['experience']['duration'] ?? ''); ?> Years)
                    </div>

                    <div style="margin-top: 15px;">
                        <strong style="font-size: 0.85em; color: #475569; display: block; margin-bottom: 5px;">Responsibilities:</strong>
                        <p style="margin: 0; font-size: 0.95em; color: #475569; line-height: 1.6; white-space: pre-wrap;"><?php echo esc_html($cv['experience']['tasks'] ?? ''); ?></p>
                    </div>

                    <?php if(!empty($cv['experience']['achievements'])): ?>
                    <div style="margin-top: 15px; border-top: 1px dashed #e2e8f0; padding-top: 15px;">
                        <strong style="font-size: 0.85em; color: #059669; display: block; margin-bottom: 5px;">Key Achievements:</strong>
                        <p style="margin: 0; font-size: 0.95em; color: #475569;"><?php echo esc_html($cv['experience']['achievements']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Skills & Certifications -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="profile-section-card" style="background: white; border-radius: 20px; padding: 30px; border: 1px solid #f1f5f9;">
                    <h4 style="margin: 0 0 20px; color: #1d3469;">Skills</h4>
                    <div style="display: flex; wrap: wrap; gap: 8px;">
                        <?php
                        $core_skills = explode(',', $cv['skills']['core'] ?? '');
                        foreach($core_skills as $skill): if(trim($skill)): ?>
                            <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 8px; font-size: 0.85em; font-weight: 500;"><?php echo trim($skill); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <div class="profile-section-card" style="background: white; border-radius: 20px; padding: 30px; border: 1px solid #f1f5f9;">
                    <h4 style="margin: 0 0 20px; color: #1d3469;">Languages</h4>
                    <div style="font-size: 0.9em; color: #475569;">
                        <div style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                            <span><?php echo esc_html($cv['languages']['native'] ?? 'Native'); ?></span>
                            <span style="color: #10b981; font-weight: 600;">Native</span>
                        </div>
                        <?php if(!empty($cv['languages']['other'])): ?>
                        <div style="display: flex; justify-content: space-between;">
                            <span><?php echo esc_html($cv['languages']['other']); ?></span>
                            <span style="color: #3b82f6; font-weight: 600;"><?php echo esc_html($cv['languages']['speak'] ?? ''); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Col -->
        <div class="profile-sidebar-col">

            <div class="sidebar-info-card" style="background: white; border-radius: 20px; padding: 30px; border: 1px solid #f1f5f9; margin-bottom: 30px;">
                <h4 style="margin: 0 0 20px; color: #1e293b; font-size: 1.1em; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;">Professional Details</h4>

                <div class="info-row" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 5px;">Availability</label>
                    <span style="font-weight: 600; color: #1e293b;"><?php echo esc_html($cv['preferences']['availability'] ?? 'Immediate'); ?></span>
                </div>

                <div class="info-row" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 5px;">Expected Salary</label>
                    <span style="font-weight: 600; color: #10b981; font-size: 1.1em;"><?php echo esc_html($cv['preferences']['salary'] ?? 'Negotiable'); ?></span>
                </div>

                <div class="info-row" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 5px;">Work Preference</label>
                    <span style="background: #ecfdf5; color: #065f46; padding: 4px 10px; border-radius: 20px; font-size: 0.8em; font-weight: 600;"><?php echo esc_html($cv['preferences']['flexibility'] ?? 'On-site'); ?></span>
                </div>

                <div class="info-row">
                    <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 5px;">Location</label>
                    <span style="color: #475569;"><?php echo esc_html($cv['personal']['city'] ?? ''); ?>, <?php echo esc_html($cv['personal']['country'] ?? ''); ?></span>
                </div>
            </div>

            <div class="sidebar-cta-card" style="background: #1d3469; border-radius: 20px; padding: 30px; color: white; text-align: center;">
                <h4 style="margin: 0 0 10px;">Interested in this candidate?</h4>
                <p style="font-size: 0.9em; opacity: 0.8; margin-bottom: 25px;">Send a direct job offer or invite them for an interview.</p>
                <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: white; color: #1d3469; width: 100%; border: none; font-weight: 700;">Send Direct Offer</button>
            </div>

        </div>

    </div>
</div>

<!-- Messaging Modal -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:40px; border-radius:24px; width:100%; max-width:500px; box-shadow:0 20px 50px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0;">Send Message to <?php echo esc_html($user->display_name); ?></h3>
        <textarea id="message-text" placeholder="Write your message here..." style="width:100%; height:150px; padding:15px; border-radius:12px; border:1px solid #ddd; margin-bottom:20px;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="jobs-btn-minimal close-modal">Cancel</button>
            <button class="jobs-btn" id="confirm-send-message">Send Message</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.open-message-modal').on('click', function() {
        $('#message-modal').css('display', 'flex');
    });
    $('.close-modal').on('click', function() {
        $('#message-modal').hide();
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
                alert('Message sent successfully!');
                $('#message-modal').hide();
                $('#message-text').val('');
            } else {
                alert('Error sending message.');
            }
            btn.prop('disabled', false).text('Send Message');
        });
    });
});
</script>

<?php get_footer(); ?>

<?php
/**
 * Template: Step-by-Step Account Setup
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
$role = $user->roles[0] ?? '';
?>
<div class="jobs-setup-page" style="padding: 100px 20px; background: #f0f4f8; min-height: 100vh; font-family: 'Rubik', sans-serif;">
    <div class="setup-container" style="max-width: 800px; margin: 0 auto; background: white; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); overflow: hidden;">

        <!-- Setup Header -->
        <div class="setup-header" style="background: var(--jobs-primary-color); padding: 40px; color: white; text-align: center;">
            <h2 style="margin: 0; font-size: 2em;">Welcome, <?php echo esc_html( $user->display_name ); ?>!</h2>
            <p style="opacity: 0.9; margin-top: 10px;">Let's get your <?php echo $role === 'employer' ? 'Company' : 'Professional'; ?> profile ready.</p>

            <div class="setup-progress" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">
                <div class="step-dot active" data-step="1" style="width: 12px; height: 12px; border-radius: 50%; background: white;"></div>
                <div class="step-dot" data-step="2" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3);"></div>
                <div class="step-dot" data-step="3" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3);"></div>
            </div>
        </div>

        <div class="setup-body" style="padding: 50px;">
            <form id="jobs-setup-form">
                <?php wp_nonce_field( 'jobs_setup_account', 'jobs_setup_nonce' ); ?>
                <input type="hidden" name="user_role" value="<?php echo esc_attr( $role ); ?>">

                <!-- Step 1: Basic Info -->
                <div class="setup-step active" id="setup-step-1">
                    <h3 style="margin-top: 0; color: #1d3469;">Step 1: Basic Information</h3>
                    <p style="color: #64748b; margin-bottom: 30px;">Tell us a bit more about yourself.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Display Name</label>
                            <input type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Contact Phone</label>
                            <input type="text" name="phone" placeholder="+1..." style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        </div>
                    </div>

                    <div style="margin-top: 40px; text-align: right;">
                        <button type="button" class="jobs-btn next-setup-step" data-next="2">Next Step</button>
                    </div>
                </div>

                <!-- Step 2: Role Specific Details -->
                <div class="setup-step" id="setup-step-2" style="display: none;">
                    <?php if ( $role === 'employer' ) : ?>
                        <h3 style="margin-top: 0; color: #1d3469;">Step 2: Company Details</h3>
                        <p style="color: #64748b; margin-bottom: 30px;">Establish your organization's presence.</p>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Company Name</label>
                            <input type="text" name="company_name" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Company Logo URL</label>
                            <input type="text" name="company_logo" placeholder="https://..." style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">About the Company</label>
                            <textarea name="company_description" style="width: 100%; height: 100px; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;"></textarea>
                        </div>
                    <?php else : ?>
                        <h3 style="margin-top: 0; color: #1d3469;">Step 2: Professional Details</h3>
                        <p style="color: #64748b; margin-bottom: 30px;">Highlight your core expertise.</p>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Specialization</label>
                            <select name="specialization" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <?php
                                $specs = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                                foreach ($specs as $spec) {
                                    echo '<option value="'.esc_attr($spec->slug).'">'.esc_html($spec->name).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Short Bio</label>
                            <textarea name="bio" style="width: 100%; height: 100px; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;" placeholder="Write a few words about your career..."></textarea>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
                        <button type="button" class="jobs-btn-minimal prev-setup-step" data-prev="1">Back</button>
                        <button type="button" class="jobs-btn next-setup-step" data-next="3">Next Step</button>
                    </div>
                </div>

                <!-- Step 3: Final Touches / Account Data -->
                <div class="setup-step" id="setup-step-3" style="display: none;">
                    <h3 style="margin-top: 0; color: #1d3469;">Step 3: Finish Setup</h3>
                    <p style="color: #64748b; margin-bottom: 30px;">Almost there! Once you finish, you can access your full dashboard.</p>

                    <div style="background: #f8fafc; padding: 30px; border-radius: 20px; text-align: center;">
                        <div style="font-size: 50px; margin-bottom: 20px;">🚀</div>
                        <h4>Ready to start?</h4>
                        <p style="font-size: 0.9em; color: #64748b;">By clicking finish, we will save your profile and redirect you to your personalized workspace.</p>
                    </div>

                    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
                        <button type="button" class="jobs-btn-minimal prev-setup-step" data-prev="2">Back</button>
                        <button type="submit" class="jobs-btn">Complete Setup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.next-setup-step').on('click', function() {
        var next = $(this).data('next');
        $('.setup-step').hide();
        $('#setup-step-' + next).fadeIn();
        $('.step-dot').css('background', 'rgba(255,255,255,0.3)');
        $('.step-dot[data-step="' + next + '"]').css('background', 'white');
    });

    $('.prev-setup-step').on('click', function() {
        var prev = $(this).data('prev');
        $('.setup-step').hide();
        $('#setup-step-' + prev).fadeIn();
        $('.step-dot').css('background', 'rgba(255,255,255,0.3)');
        $('.step-dot[data-step="' + prev + '"]').css('background', 'white');
    });

    $('#jobs-setup-form').on('submit', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Saving...');

        $.post(jobs_vars.ajax_url, $(this).serialize() + '&action=jobs_complete_setup', function(response) {
            if (response.success) {
                window.location.href = response.data.redirect;
            } else {
                alert(response.data);
                $btn.prop('disabled', false).text('Complete Setup');
            }
        });
    });
});
</script>

<?php
/**
 * Template: Legal Policies (Copyright, Privacy, Terms)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="jobs-policies-page" style="padding: 100px 20px; background: #f8fafc; font-family: 'Rubik', sans-serif;">
    <div style="max-width: 900px; margin: 0 auto; background: white; padding: 60px; border-radius: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.03);">

        <h1 style="color: var(--jobs-primary-color); border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 40px;">Legal Policies & Terms</h1>

        <section style="margin-bottom: 50px;">
            <h2 style="color: #1d3469;">1. Copyright Notice</h2>
            <p>All content, designs, and intellectual property on this platform are &copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved. Unauthorized reproduction or distribution is strictly prohibited.</p>
        </section>

        <section style="margin-bottom: 50px;">
            <h2 style="color: #1d3469;">2. Privacy Policy</h2>
            <p>Your privacy is important to us. This policy explains how we collect and use your data:</p>
            <ul>
                <li><strong>Data Collection:</strong> We collect information provided during registration (email, name, professional history).</li>
                <li><strong>Usage:</strong> Data is used to match job seekers with employers and improve platform functionality.</li>
                <li><strong>Protection:</strong> We implement industry-standard security to protect your personal information.</li>
                <li><strong>Cookies:</strong> We use essential cookies for authentication and session management.</li>
            </ul>
        </section>

        <section style="margin-bottom: 50px;">
            <h2 style="color: #1d3469;">3. Terms of Use</h2>
            <p>By using this platform, you agree to the following:</p>
            <ul>
                <li>You will provide accurate and truthful information in your profile and job listings.</li>
                <li>You will not use the platform for any illegal or fraudulent activities.</li>
                <li>We reserve the right to suspend accounts that violate our community standards.</li>
                <li>The platform is provided "as is" without any warranties.</li>
            </ul>
        </section>

        <div style="text-align: center; margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px; color: #94a3b8; font-size: 0.9em;">
            <p>Last Updated: <?php echo date('F d, Y'); ?></p>
            <a href="<?php echo home_url(); ?>" class="jobs-btn-small" style="margin-top: 20px;">Return to Homepage</a>
        </div>
    </div>
</div>
<?php get_footer(); ?>

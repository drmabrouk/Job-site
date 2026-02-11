<?php
/**
 * Template: Stable Standalone Page for Account Setup
 * Ensures the onboarding flow is not compressed or misaligned by theme styles.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        body.jobs-setup-standalone {
            background: #f8fafc !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: 100vh !important;
        }
        .jobs-setup-root-container {
            width: 100% !important;
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 40px 20px !important;
            box-sizing: border-box !important;
        }
    </style>
</head>
<body <?php body_class('jobs-setup-standalone'); ?>>

<div class="jobs-setup-root-container">
    <?php
    // Output the setup template directly
    include JOBS_PLUGIN_DIR . 'templates/account-setup.php';
    ?>
</div>

<?php wp_footer(); ?>
</body>
</html>

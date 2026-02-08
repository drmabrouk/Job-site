<?php
/**
 * Public Profile Display Template - Professional High-End Interface
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$username = get_query_var( 'profile_user' );
if ( ! $username ) {
    echo '<p style="padding: 50px; text-align: center; color: #64748b;">User not found.</p>';
    return;
}

// Try looking up by slug (user_nicename), then fallback to login
$user = get_user_by( 'slug', $username );
if ( ! $user ) {
    $user = get_user_by( 'login', $username );
}

if ( ! $user ) {
    echo '<p style="padding: 50px; text-align: center; color: #64748b;">User not found. The profile may have been removed or the link is incorrect.</p>';
    return;
}

$user_id = $user->ID;
$role = $user->roles[0];
$cv_data = get_user_meta( $user_id, 'jobs_cv_data', true ) ?: array();
$company_data = get_user_meta( $user_id, 'jobs_company_data', true ) ?: array();
$visibility = get_user_meta( $user_id, 'profile_visibility', true ) ?: 'public';

if ( $visibility === 'private' && get_current_user_id() !== $user_id ) {
    echo '<div style="padding: 100px 20px; text-align: center; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 50px auto;">';
    echo '<span class="dashicons dashicons-hidden" style="font-size: 60px; width: 60px; height: 60px; color: #cbd5e1; margin-bottom: 20px;"></span>';
    echo '<h2 style="color: #1d3469;">Private Profile</h2>';
    echo '<p style="color: #64748b;">This user has chosen to keep their profile private.</p>';
    echo '</div>';
    return;
}

$format_pdf = isset( $_GET['format'] ) && $_GET['format'] === 'pdf';
?>

<?php if ( $format_pdf ) : ?>
<style>
    body { background: white !important; }
    body * { visibility: hidden; }
    #jobs-profile-main, #jobs-profile-main * { visibility: visible; }
    #jobs-profile-main { position: absolute; left: 0; top: 0; width: 100%; padding: 0 !important; margin: 0 !important; }
    .profile-actions, .site-header, .site-footer { display: none !important; }
</style>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>

<div class="jobs-profile-wrapper">
    <div class="jobs-profile-container" id="jobs-profile-main">

        <!-- Header / Banner Area -->
        <div class="profile-card-header">
            <div class="profile-banner" style="background: linear-gradient(135deg, #1d3469 0%, #152a55 100%); height: 180px; border-radius: 24px 24px 0 0;"></div>
            <div class="profile-header-content">
                <div class="profile-avatar-wrapper">
                    <?php if ( $role === 'employer' && ! empty( $company_data['logo'] ) ) : ?>
                        <img src="<?php echo esc_url( $company_data['logo'] ); ?>" alt="Company Logo" class="profile-img">
                    <?php else : ?>
                        <?php echo get_avatar( $user_id, 160, '', '', array('class'=>'profile-img') ); ?>
                    <?php endif; ?>
                </div>

                <div class="profile-main-meta">
                    <div class="profile-title-row">
                        <h1><?php echo esc_html( $user->display_name ); ?></h1>
                        <?php if ( $role === 'job_seeker' ) : ?>
                            <span class="verification-badge" title="Verified Professional"><span class="dashicons dashicons-yes-alt"></span></span>
                        <?php endif; ?>
                    </div>

                    <p class="profile-tagline">
                        <?php
                        if ( $role === 'employer' ) {
                            echo esc_html( $company_data['name'] ?? 'Employer' );
                        } else {
                            echo esc_html( get_user_meta($user_id, '_specialization', true) ?: 'Professional Candidate' );
                        }
                        ?>
                    </p>

                    <div class="profile-quick-tags">
                        <span class="q-tag"><span class="dashicons dashicons-businessperson"></span> <?php echo ucfirst( str_replace('_', ' ', $role) ); ?></span>
                        <?php if ( $role === 'job_seeker' && $nat = get_user_meta($user_id, '_nationality', true) ) : ?>
                            <span class="q-tag"><span class="dashicons dashicons-location"></span> <?php echo esc_html($nat); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="profile-actions">
                    <?php if ( get_current_user_id() && get_current_user_id() !== $user_id ) : ?>
                        <button class="jobs-btn contact-user-btn" data-user-id="<?php echo $user_id; ?>" data-name="<?php echo esc_attr($user->display_name); ?>">
                            <span class="dashicons dashicons-email"></span> Send Message
                        </button>
                    <?php endif; ?>
                    <a href="?format=pdf" target="_blank" class="jobs-btn-minimal" style="border: 1px solid #e2e8f0;">
                        <span class="dashicons dashicons-pdf"></span> Export PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="profile-grid">
            <!-- Sidebar: About & Highlights -->
            <aside class="profile-sidebar">
                <?php if ( $user->description ) : ?>
                <div class="profile-section">
                    <h3 class="s-title">About</h3>
                    <p class="s-content"><?php echo nl2br(esc_html($user->description)); ?></p>
                </div>
                <?php endif; ?>

                <?php if ( $role === 'job_seeker' ) : ?>
                <div class="profile-section highlights-box">
                    <h3 class="s-title">Professional Overview</h3>
                    <div class="h-list">
                        <div class="h-item">
                            <div class="h-icon" style="background: #e0f2f1; color: #00796b;"><span class="dashicons dashicons-portfolio"></span></div>
                            <div class="h-text">
                                <small>Experience</small>
                                <strong><?php echo esc_html(get_user_meta($user_id, '_experience', true) ?: '0'); ?> Years</strong>
                            </div>
                        </div>
                        <div class="h-item">
                            <div class="h-icon" style="background: #e3f2fd; color: #1976d2;"><span class="dashicons dashicons-translation"></span></div>
                            <div class="h-text">
                                <small>English Level</small>
                                <strong><?php echo ucfirst(esc_html(get_user_meta($user_id, '_english_level', true) ?: 'N/A')); ?></strong>
                            </div>
                        </div>
                        <div class="h-item">
                            <div class="h-icon" style="background: #f3e5f5; color: #7b1fa2;"><span class="dashicons dashicons-welcome-learn-more"></span></div>
                            <div class="h-text">
                                <small>Education</small>
                                <strong><?php echo ucfirst(esc_html(get_user_meta($user_id, '_qualification', true) ?: 'N/A')); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="profile-section share-profile">
                    <h3 class="s-title">Share Profile</h3>
                    <div class="share-links" style="display:flex; gap:10px;">
                        <?php
                        $curr_url = urlencode(jobs_get_profile_link($user_id));
                        $text = urlencode('Check out ' . $user->display_name . ' on Jobedia');
                        ?>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $text . '%20' . $curr_url; ?>" target="_blank" class="s-icon wa"><span class="dashicons dashicons-whatsapp"></span></a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $text; ?>&url=<?php echo $curr_url; ?>" target="_blank" class="s-icon tw">𝕏</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $curr_url; ?>" target="_blank" class="s-icon fb"><span class="dashicons dashicons-facebook"></span></a>
                    </div>
                </div>
            </aside>

            <!-- Main Content: Work, Education, Skills -->
            <main class="profile-main">
                <?php if ( $role === 'job_seeker' ) : ?>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-awards"></span> Work Experience</h2>
                        <div class="card-body"><?php echo nl2br( esc_html( $cv_data['experience'] ?? 'No work experience details provided.' ) ); ?></div>
                    </div>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-welcome-learn-more"></span> Education</h2>
                        <div class="card-body"><?php echo nl2br( esc_html( $cv_data['education'] ?? 'No education details provided.' ) ); ?></div>
                    </div>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-star-filled"></span> Key Skills</h2>
                        <div class="skills-grid">
                            <?php
                            $skills = $cv_data['skills'] ?? '';
                            if ($skills) {
                                $skills_arr = explode(',', $skills);
                                foreach ($skills_arr as $s) {
                                    echo '<span class="skill-pill">' . esc_html(trim($s)) . '</span>';
                                }
                            } else {
                                echo '<p style="color:#94a3b8; font-size:0.9em;">No skills listed.</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php if ( !empty($cv_data['certifications']) ) : ?>
                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-certificate"></span> Certifications</h2>
                        <div class="card-body"><?php echo nl2br( esc_html( $cv_data['certifications'] ) ); ?></div>
                    </div>
                    <?php endif; ?>

                <?php elseif ( $role === 'employer' ) : ?>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-building"></span> About Company</h2>
                        <div class="card-body"><?php echo nl2br( esc_html( $company_data['details'] ?? 'No company details available.' ) ); ?></div>
                    </div>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-info"></span> Contact & Location</h2>
                        <div class="company-info-grid" style="margin-top:0;">
                            <div class="info-item">
                                <strong>Address</strong>
                                <span><?php echo esc_html( $company_data['address'] ?? 'N/A' ); ?></span>
                            </div>
                            <div class="info-item">
                                <strong>Size</strong>
                                <span><?php echo esc_html( $company_data['employee_count'] ?? 'N/A' ); ?> Employees</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-card">
                        <h2 class="card-title"><span class="dashicons dashicons-portfolio"></span> Active Job Openings</h2>
                        <div class="employer-jobs-list">
                            <?php
                            $e_jobs = get_posts(array('post_type'=>'job', 'author'=>$user_id, 'posts_per_page'=>5));
                            if ($e_jobs) : foreach ($e_jobs as $j) : ?>
                                <a href="<?php echo get_permalink($j->ID); ?>" class="e-job-item">
                                    <strong><?php echo get_the_title($j->ID); ?></strong>
                                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                                </a>
                            <?php endforeach; else : ?>
                                <p style="color:#94a3b8; font-size:0.9em;">No active job openings at the moment.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>

                <?php
                require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
                Jobs_Ads_Service::display_ad( 'user_profile' );
                ?>
            </main>
        </div>

    </div>
</div>

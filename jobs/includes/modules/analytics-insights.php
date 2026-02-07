<?php
/**
 * Module: Analytics / Insights
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$is_admin = Jobs_Permission_Service::is_admin();
?>
<div class="jobs-module-content">
    <h2>Analytics & Insights</h2>

    <div class="analytics-overview-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <div class="report-card" style="padding: 20px; border: 1px solid rgba(29, 52, 105, 0.1); border-radius: 12px; text-align: center;">
            <strong style="display:block; font-size: 1.5em; color: var(--jobs-primary-color);">
                <?php echo $is_admin ? wp_count_posts('job')->publish : '8'; ?>
            </strong>
            <span style="color: #666; font-size: 0.9em;"><?php echo $is_admin ? 'Active Jobs' : 'Job Views'; ?></span>
        </div>
        <div class="report-card" style="padding: 20px; border: 1px solid rgba(29, 52, 105, 0.1); border-radius: 12px; text-align: center;">
            <strong style="display:block; font-size: 1.5em; color: var(--jobs-primary-color);">
                <?php echo $is_admin ? count_users()['total_users'] : '3'; ?>
            </strong>
            <span style="color: #666; font-size: 0.9em;"><?php echo $is_admin ? 'Total Users' : 'Active Applications'; ?></span>
        </div>
        <div class="report-card" style="padding: 20px; border: 1px solid rgba(29, 52, 105, 0.1); border-radius: 12px; text-align: center;">
            <strong style="display:block; font-size: 1.5em; color: var(--jobs-primary-color);">85%</strong>
            <span style="color: #666; font-size: 0.9em;">Engagement Rate</span>
        </div>
    </div>

    <div class="analytics-chart-placeholder" style="margin-top: 40px; height: 300px; background: rgba(0,0,0,0.02); border: 1px dashed #ddd; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
        <div style="text-align: center; color: #999;">
            <span class="dashicons dashicons-chart-area" style="font-size: 48px; width: 48px; height: 48px;"></span>
            <p>Interactive Performance Charts Loading...</p>
        </div>
    </div>
</div>

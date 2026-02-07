<?php
/**
 * Template: Job Seekers Listing
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$specializations = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
?>
<div class="jobs-seekers-page">
    <div class="seekers-container" style="display: flex; gap: 30px; max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

        <!-- Sidebar Filters -->
        <aside class="seekers-sidebar" style="flex: 1; min-width: 250px;">
            <div class="filter-card" style="background: white; padding: 20px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); position: sticky; top: 100px;">
                <h4>Filter Candidates</h4>
                <form id="jobs-seekers-filter-form">
                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>Specialization</label>
                        <select name="specialization" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="">All Specializations</option>
                            <?php foreach ( $specializations as $term ) : ?>
                                <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>Qualification</label>
                        <select name="qualification" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="">Any</option>
                            <option value="high-school">High School</option>
                            <option value="bachelor">Bachelor's Degree</option>
                            <option value="master">Master's Degree</option>
                            <option value="phd">PhD</option>
                        </select>
                    </div>

                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>Nationality</label>
                        <input type="text" name="nationality" placeholder="e.g. American" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience" placeholder="e.g. 5" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>Gender</label>
                        <select name="gender" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="">Any</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="filter-group" style="margin-bottom: 15px;">
                        <label>English Level</label>
                        <select name="english_level" style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                            <option value="">Any</option>
                            <option value="basic">Basic</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="fluent">Fluent</option>
                            <option value="native">Native</option>
                        </select>
                    </div>

                    <button type="button" id="jobs-filter-seekers-btn" class="jobs-btn" style="width: 100%;">Apply Filters</button>
                </form>
            </div>
        </aside>

        <!-- Main Content (Seekers Cards) -->
        <main class="seekers-main" style="flex: 3;">
            <div id="jobs-seekers-results" class="seekers-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <!-- Seekers will be loaded via AJAX -->
                <p>Loading candidates...</p>
            </div>
        </main>
    </div>
</div>

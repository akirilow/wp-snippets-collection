<?php

/**
 * ACF Repeater Field Examples
 * 
 * This file demonstrates various ways to read and display ACF Repeater fields
 * in WordPress themes. Copy the relevant code snippets into your theme files.
 * 
 * @package WordPress
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Example 1: Basic Repeater Field Loop
 * 
 * Field Name: 'team_members'
 * Sub-fields: 'name', 'position', 'bio'
 */
if (have_rows('team_members')) : ?>
    <div class="team-members">
        <?php while (have_rows('team_members')) : the_row();
            $name = get_sub_field('name');
            $position = get_sub_field('position');
            $bio = get_sub_field('bio');
        ?>
            <div class="team-member">
                <h3><?php echo esc_html($name); ?></h3>
                <p class="position"><?php echo esc_html($position); ?></p>
                <p class="bio"><?php echo esc_html($bio); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;

/**
 * Example 2: Repeater with Image Sub-field
 * 
 * Field Name: 'portfolio_items'
 * Sub-fields: 'title', 'description', 'image', 'link'
 */
if (have_rows('portfolio_items')) : ?>
    <div class="portfolio-grid">
        <?php while (have_rows('portfolio_items')) : the_row();
            $title = get_sub_field('title');
            $description = get_sub_field('description');
            $image = get_sub_field('image');
            $link = get_sub_field('link');
        ?>
            <div class="portfolio-item">
                <?php if ($image) : ?>
                    <img src="<?php echo esc_url($image['url']); ?>"
                        alt="<?php echo esc_attr($image['alt']); ?>"
                        class="portfolio-image">
                <?php endif; ?>

                <h4><?php echo esc_html($title); ?></h4>
                <p><?php echo esc_html($description); ?></p>

                <?php if ($link) : ?>
                    <a href="<?php echo esc_url($link); ?>" class="portfolio-link">
                        View Project
                    </a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;

/**
 * Example 3: Nested Repeater Fields
 * 
 * Parent Field: 'departments'
 * Parent Sub-fields: 'department_name', 'employees'
 * Nested Field: 'employees' (repeater)
 * Nested Sub-fields: 'employee_name', 'employee_role'
 */
if (have_rows('departments')) : ?>
    <div class="departments">
        <?php while (have_rows('departments')) : the_row();
            $department_name = get_sub_field('department_name');
        ?>
            <div class="department">
                <h3><?php echo esc_html($department_name); ?></h3>

                <?php if (have_rows('employees')) : ?>
                    <ul class="employees">
                        <?php while (have_rows('employees')) : the_row();
                            $employee_name = get_sub_field('employee_name');
                            $employee_role = get_sub_field('employee_role');
                        ?>
                            <li class="employee">
                                <strong><?php echo esc_html($employee_name); ?></strong>
                                - <?php echo esc_html($employee_role); ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;

/**
 * Example 4: Repeater with Error Handling and Fallback
 * 
 * Field Name: 'testimonials'
 * Sub-fields: 'quote', 'author', 'company'
 */
$testimonials = get_field('testimonials');

if (!empty($testimonials) && is_array($testimonials)) : ?>
    <div class="testimonials">
        <?php foreach ($testimonials as $testimonial) :
            $quote = !empty($testimonial['quote']) ? $testimonial['quote'] : '';
            $author = !empty($testimonial['author']) ? $testimonial['author'] : 'Anonymous';
            $company = !empty($testimonial['company']) ? $testimonial['company'] : '';
        ?>
            <blockquote class="testimonial">
                <?php if ($quote) : ?>
                    <p><?php echo esc_html($quote); ?></p>
                <?php endif; ?>

                <cite>
                    <?php echo esc_html($author); ?>
                    <?php if ($company) : ?>
                        <span class="company">, <?php echo esc_html($company); ?></span>
                    <?php endif; ?>
                </cite>
            </blockquote>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>No testimonials available.</p>
<?php endif;

/**
 * Example 5: Repeater in Different Contexts
 * 
 * Usage in different template files:
 * - single.php (for post-specific repeaters)
 * - page.php (for page-specific repeaters)
 * - archive.php (for term/category repeaters)
 */

// For specific post ID
$post_id = 123; // Replace with actual post ID
if (have_rows('custom_sections', $post_id)) :
    while (have_rows('custom_sections', $post_id)) : the_row();
        $section_title = get_sub_field('section_title');
        $section_content = get_sub_field('section_content');
    // Process fields...
    endwhile;
endif;

// For options page (global repeater)
if (have_rows('global_announcements', 'option')) :
    while (have_rows('global_announcements', 'option')) : the_row();
        $announcement = get_sub_field('announcement_text');
        $announcement_link = get_sub_field('announcement_link');
    // Process fields...
    endwhile;
endif;

// For taxonomy term
$term_id = get_queried_object_id();
if (have_rows('category_features', 'term_' . $term_id)) :
    while (have_rows('category_features', 'term_' . $term_id)) : the_row();
        $feature_title = get_sub_field('feature_title');
        $feature_description = get_sub_field('feature_description');
    // Process fields...
    endwhile;
endif;

/**
 * Best Practices:
 * 
 * 1. Always check if repeater has rows before looping
 * 2. Escape output with esc_html(), esc_url(), esc_attr()
 * 3. Handle empty/missing sub-fields gracefully
 * 4. Use meaningful variable names for sub-fields
 * 5. Consider performance for large repeater fields
 * 6. Use get_field() with foreach for more control
 * 7. Always define fallback content when appropriate
 */
?>
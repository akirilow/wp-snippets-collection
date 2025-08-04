<?php

/**
 * Dynamically populate Contact Form 7 select field with all job titles and preselect current job.
 *
 * Usage:
 * - Add a select field with name 'jobs' in your CF7 form.
 * - Place this snippet in your theme's functions.php or as a must-use plugin.
 *
 * @author andy
 * @version 1.0
 */

add_filter('wpcf7_form_tag', function ($tag) {
    if ($tag['name'] != 'jobs') {
        return $tag;
    }

    // Output all published job titles
    $args = [
        'post_type' => 'job',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ];
    $jobs = get_posts($args);
    $options = [];
    $current_id = get_queried_object_id();
    $current_title = null;
    foreach ($jobs as $job) {
        $options[] = $job->post_title;
        if ($job->ID == $current_id) {
            $current_title = $job->post_title;
        }
    }
    $tag['raw_values'] = $options;
    $tag['values'] = $options;
    $tag['labels'] = $options;
    // Preselect the current job if on a single job page
    if ($current_title) {
        $tag['values'] = array_merge([$current_title], array_diff($tag['values'], [$current_title]));
        $tag['labels'] = array_merge([$current_title], array_diff($tag['labels'], [$current_title]));
        $tag['options'][] = 'default:' . $current_title;
    }

    return $tag;
}, 10, 1);

// ---
// Usage instructions:
//
// 1. Add this file to your theme's functions.php, a custom plugin, or as a must-use plugin.
//    Example for functions.php:
//    require_once get_template_directory() . '/cf7/cf7-dynamic-job-select.php';
//
// 2. In your Contact Form 7 form, add a select field with the name 'jobs':
//
//    [select* jobs]
//
//    (The * makes the field required. Remove it if not required.)
//
// 3. The field will be automatically populated with all published job titles and preselect the current job on single job pages.

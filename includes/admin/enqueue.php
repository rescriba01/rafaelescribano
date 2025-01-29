<?php
/**
 * Admin Enqueue Functions
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin scripts and styles for work post type
 *
 * @param string $hook The current admin page
 */
function re_enqueue_work_admin_assets($hook) {
    global $post;

    // Only enqueue on work post edit screen
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    if (!$post || $post->post_type !== 'work') return;

    // Enqueue styles
    wp_enqueue_style(
        're-work-meta',
        RE_THEME_URL . 'assets/css/admin/work-meta.css',
        array(),
        RE_THEME_VERSION
    );

    // Enqueue scripts
    wp_enqueue_media();
    wp_enqueue_script(
        're-work-meta',
        RE_THEME_URL . 'assets/js/admin/work-meta.js',
        array('wp-i18n', 'wp-media-utils'),
        RE_THEME_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 're_enqueue_work_admin_assets'); 
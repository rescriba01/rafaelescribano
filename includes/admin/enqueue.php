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
 * Register scripts and styles for the work post type
 *
 * @param string $hook The current admin page
 */
function re_enqueue_work_admin_assets($hook) {
    global $post;
    
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    if (!$post || $post->post_type !== 'work') return;

    wp_enqueue_media();
    wp_enqueue_style(
        'work-meta', 
        RE_THEME_URL . 'assets/css/admin/work-meta.css', 
        [], 
        RE_VERSION
    );
    
    wp_enqueue_script(
        'work-meta', 
        RE_THEME_URL . 'assets/js/admin/work-meta.js', 
        ['wp-i18n', 'jquery'], 
        RE_VERSION, 
        true
    );

    // Add nonce and post ID for AJAX
    wp_localize_script('work-meta', 'reWorkMeta', [
        'nonce' => wp_create_nonce('re_work_meta'),
        'post_id' => $post->ID,
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('admin_enqueue_scripts', 're_enqueue_work_admin_assets'); 
<?php
/**
 * Style Loading
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme styles
 */
function re_enqueue_styles() {
    // Main theme style
    wp_enqueue_style(
        're-style',
        RE_THEME_URL . 'style.css',
        array(),
        RE_THEME_VERSION
    );
}
add_action('wp_enqueue_scripts', 're_enqueue_styles'); 
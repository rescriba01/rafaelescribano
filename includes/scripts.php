<?php
/**
 * Script Loading
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme scripts
 */
function re_enqueue_scripts() {
    wp_enqueue_script(
        're-navigation',
        RE_THEME_URL . 'assets/js/navigation.js',
        array(),
        RE_THEME_VERSION,
        true
    );

    // Pattern: Work Card Stack
    wp_enqueue_script(
        're-pattern-work-card-stack',
        RE_THEME_URL . 'assets/js/patterns/work-card-stack.js',
        array(),
        RE_THEME_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 're_enqueue_scripts'); 
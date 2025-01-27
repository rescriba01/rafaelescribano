<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package re
 * @since 1.0.0
 */

// If accessed directly, exit
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Data
 *
 * Gets data from the theme header.
 *
 * @since 1.0.0
 *
 * @return object Theme data
 */
function re_theme_data() {
    return wp_get_theme();
}
$theme_data = re_theme_data();

/**
 * Set Theme Constants
 *
 * Sets constants for use throughout the theme.
 *
 * @since 1.0.0
 */
define('RE_THEME_VERSION', $theme_data->get('Version'));
define('RE_THEME_PATH', get_template_directory() . '/');
define('RE_THEME_URL', get_template_directory_uri() . '/');

/**
 * Get Includes Files
 *
 * Get theme functionality and utility files.
 *
 * @since 1.0.0
 */
require_once RE_THEME_PATH . 'includes/styles.php';
require_once RE_THEME_PATH . 'includes/scripts.php';
require_once RE_THEME_PATH . 'includes/custom-blocks.php';
require_once RE_THEME_PATH . 'includes/block-styles.php';
require_once RE_THEME_PATH . 'includes/pattern-categories.php';
require_once RE_THEME_PATH . 'includes/post-type-work.php';

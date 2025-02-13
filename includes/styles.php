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

    // Pattern: Intro with Links
    wp_enqueue_style(
        're-pattern-intro-with-links',
        RE_THEME_URL . 'assets/css/patterns/intro-with-links.css',
        array(),
        RE_THEME_VERSION
    );

    // Pattern: Project
    wp_enqueue_style(
        're-pattern-project',
        RE_THEME_URL . 'assets/css/patterns/project.css',
        array(),
        RE_THEME_VERSION
    );

    // Pattern: Work Card Stack
    wp_enqueue_style(
        're-pattern-work-card-stack',
        RE_THEME_URL . 'assets/css/patterns/work-card-stack.css',
        array(),
        RE_THEME_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 're_enqueue_styles' );

/**
 * Enqueue header 
 * Part: Header
 */
function re_enqueue_header_styles() {
    wp_enqueue_style(
        're-header',
        RE_THEME_URL . 'assets/css/layout/header.css',
        array(),
        RE_THEME_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 're_enqueue_header_styles' );

/**
 * Enqueue form styles
 * Component: Forms
 */
function re_enqueue_form_styles() {
    wp_enqueue_style(
        're-forms',
        RE_THEME_URL . 'assets/css/components/forms.css',
        array(),
        RE_THEME_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 're_enqueue_form_styles' ); 
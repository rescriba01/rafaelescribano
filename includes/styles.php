<?php
/**
 * Style Loading
 *
 * @package re
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue theme styles
 */
function re_enqueue_styles() {
    // Base styles
    wp_enqueue_style(
        're-reset',
        RE_THEME_URL . 'assets/css/base/reset.css',
        array(),
        RE_THEME_VERSION
    );

    // Layout styles
    wp_enqueue_style(
        're-layout-base',
        RE_THEME_URL . 'assets/css/layout/base.css',
        array( 're-reset' ),
        RE_THEME_VERSION
    );

    wp_enqueue_style(
        're-layout-header',
        RE_THEME_URL . 'assets/css/layout/header.css',
        array( 're-layout-base' ),
        RE_THEME_VERSION
    );

    // Main theme style
    wp_enqueue_style(
        're-style',
        RE_THEME_URL . 'style.css',
        array( 're-layout-header' ),
        RE_THEME_VERSION
    );

    // Pattern styles
    wp_enqueue_style(
        're-pattern-intro-with-links',
        RE_THEME_URL . 'assets/css/patterns/intro-with-links.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    wp_enqueue_style(
        're-pattern-projects',
        RE_THEME_URL . 'assets/css/patterns/projects.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    wp_enqueue_style(
        're-pattern-single-project',
        RE_THEME_URL . 'assets/css/patterns/single-project.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    // Component styles
    wp_enqueue_style(
        're-forms',
        RE_THEME_URL . 'assets/css/components/forms.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    // Part Styles
    wp_enqueue_style(
        're-header',
        RE_THEME_URL . 'assets/css/layout/header.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    // Template Styles
    wp_enqueue_style(
        're-front-page',
        RE_THEME_URL . 'assets/css/templates/front-page.css',
        array( 're-style' ),
        RE_THEME_VERSION
    );

    // Vendor Styles
    if (is_singular('work')) {
        wp_enqueue_style(
            'swiper',
            RE_THEME_URL . 'assets/vendor/swiper/swiper-bundle.min.css',
            array(),
            '11.2.4'
        );

        wp_enqueue_style(
            'photoswipe',
            RE_THEME_URL . 'assets/vendor/photoswipe/photoswipe.css',
            array(),
            '5.4.4'
        );
    }
}
add_action( 'wp_enqueue_scripts', 're_enqueue_styles' );
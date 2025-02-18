<?php
/**
 * GSAP Scripts Integration
 *
 * @package re
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 're_enqueue_gsap_scripts' ) ) {
    /**
     * Enqueue GSAP and related animation scripts
     */
    function re_enqueue_gsap_scripts() {
        // Register GSAP Core
        wp_register_script(
            'gsap-core',
            RE_THEME_URL . 'build/vendors/gsap.js',
            array(),
            RE_THEME_VERSION,
            true
        );

        // Register ScrollTrigger
        wp_register_script(
            'gsap-scroll-trigger',
            RE_THEME_URL . 'build/vendors/ScrollTrigger.js',
            array('gsap-core'),
            RE_THEME_VERSION,
            true
        );

        // Enqueue GSAP configuration
        wp_enqueue_script(
            're-gsap-config',
            RE_THEME_URL . 'build/js/gsap-config.js',
            array('gsap-core', 'gsap-scroll-trigger'),
            RE_THEME_VERSION,
            true
        );

        // Enqueue custom animations
        wp_enqueue_script(
            're-animations',
            RE_THEME_URL . 'build/js/animations.js',
            array('re-gsap-config'),
            RE_THEME_VERSION,
            true
        );

        // Pattern: Intro with Links
        wp_enqueue_script(
            're-pattern-intro-with-links',
            RE_THEME_URL . 'build/js/patterns/intro-with-links.js',
            array('gsap-core', 'gsap-scroll-trigger'),
            RE_THEME_VERSION,
            true
        );

        // Add dynamic data for animations if needed
        wp_localize_script(
            're-animations',
            'reAnimationData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('re-animation-nonce')
            )
        );
    }
}
add_action('wp_enqueue_scripts', 're_enqueue_gsap_scripts'); 
<?php
/**
 * Helper functions for the RE theme
 *
 * @package re
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * SVG Icon Utility
 *
 * A simple utility for including SVG icons with proper WordPress block editor compatibility.
 * There are two ways to use this function:
 * 1. With fragment identifier (for sprite sheets) - returns an <img> tag
 * 2. Direct SVG inclusion - returns inline SVG with proper attributes
 *
 * Note: When using direct SVG inclusion, the XML declaration is automatically removed
 * to prevent block editor validation issues.
 *
 * @param string $icon_name The icon's name without extension
 * @param string $fragment_id Optional fragment identifier for specific icon in sprite
 * @param string $class Additional CSS classes
 * @return string|null SVG markup or null if file doesn't exist
 */
function re_get_icon( $icon_name, $fragment_id = '', $class = '' ) {
    $base_class = 're-icon';
    $classes = $base_class . ( $class ? ' ' . $class : '' );
    $icon_path = "/assets/images/svg/{$icon_name}.svg";
    $full_path = get_template_directory() . $icon_path;

    if ( file_exists( $full_path ) ) {
        if ( $fragment_id ) {
            // For sprite sheets, use img tag
            $icon_url = get_template_directory_uri() . $icon_path . '#' . $fragment_id;
            return sprintf(
                '<img class="%s" src="%s" alt="%s" width="64" height="64" loading="lazy">',
                esc_attr( $classes ),
                esc_url( $icon_url ),
                esc_attr( str_replace( '-', ' ', $icon_name ) )
            );
        } else {
            // For direct SVG inclusion
            $svg_content = file_get_contents( $full_path );
            
            // Remove XML declaration to prevent block editor issues
            $svg_content = preg_replace('/<\?xml.*\?>/i', '', $svg_content);
            
            // Ensure SVG tag has proper dimensions
            if ( ! preg_match( '/width=/', $svg_content ) ) {
                $svg_content = preg_replace( '/<svg/', '<svg width="64" height="64"', $svg_content );
            }
            
            // Add class and accessibility attributes
            return str_replace(
                '<svg',
                sprintf(
                    '<svg class="%s" role="img" aria-label="%s"',
                    esc_attr( $classes ),
                    esc_attr( str_replace( '-', ' ', $icon_name ) )
                ),
                trim($svg_content)
            );
        }
    }
    return null;
} 
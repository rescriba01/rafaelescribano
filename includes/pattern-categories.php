<?php
/**
 * Pattern Categories Registration
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register pattern categories
 */
function re_register_pattern_categories() {
    register_block_pattern_category(
        'portfolio',
        array(
            'label'       => __('Portfolio', 're'),
            'description' => __('A collection of portfolio layouts and designs.', 're'),
        )
    );

    register_block_pattern_category(
        'page',
        array(
            'label'       => __('Pages', 're'),
            'description' => __('A collection of full page layouts.', 're'),
        )
    );
}
add_action('init', 're_register_pattern_categories'); 
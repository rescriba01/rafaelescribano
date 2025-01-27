<?php
/**
 * Custom Blocks Registration
 *
 * @package re
 * @since 1.0.0
 */

namespace RE\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register theme blocks
 */
function register_theme_blocks() {
    // Get the build directory path
    $blocks_dir = get_template_directory() . '/build';
    
    // Check if directory exists
    if (!file_exists($blocks_dir)) {
        return;
    }
    
    // Get all block.json files from build subdirectories
    $block_json_files = glob($blocks_dir . '/*/block.json');
    
    // Register each block found
    foreach ($block_json_files as $json_file) {
        $block_dir = dirname($json_file);
        register_block_type($block_dir);
    }
}

add_action('init', __NAMESPACE__ . '\register_theme_blocks'); 
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
    $blocks_dir = get_template_directory() . '/build/blocks';
    
    // Check if directory exists
    if (!file_exists($blocks_dir)) {
        return;
    }
    
    // Get all block directories
    $block_directories = glob($blocks_dir . '/*', GLOB_ONLYDIR);
    
    // Register each block found
    foreach ($block_directories as $block_dir) {
        $block_json = $block_dir . '/block.json';
        
        if (file_exists($block_json)) {
            $block_data = json_decode(file_get_contents($block_json), true);
            $block_name = $block_data['name'] ?? basename($block_dir);
            
            // Register the block type
            register_block_type($block_dir);
            
            // Log registration for debugging
            error_log("Registered block: {$block_name} from {$block_dir}");
        }
    }
}

add_action('init', __NAMESPACE__ . '\register_theme_blocks'); 
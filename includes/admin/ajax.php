<?php
/**
 * Admin AJAX Handlers
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle AJAX request to update gallery layout
 */
function re_handle_gallery_layout_update() {
    // Initialize response array with debug information
    $response = [
        'success' => false,
        'debug' => [
            'post_data' => $_POST,
            'capabilities' => current_user_can('edit_posts'),
        ]
    ];

    try {
        // Verify nonce
        if (!check_ajax_referer('re_work_meta', 'nonce', false)) {
            throw new Exception('Invalid security token.');
        }

        // Verify user capabilities
        if (!current_user_can('edit_posts')) {
            throw new Exception('Permission denied.');
        }

        // Get and validate post ID
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id || get_post_type($post_id) !== 'work') {
            throw new Exception('Invalid post ID or post type.');
        }

        // Get and validate layout
        $layout = isset($_POST['layout']) ? sanitize_text_field($_POST['layout']) : '';
        if (!in_array($layout, ['full', 'split'], true)) {
            throw new Exception('Invalid layout type.');
        }

        // Success response
        $response = [
            'success' => true,
            'data' => [
                'message' => 'Layout updated successfully.',
                'layout' => $layout,
                'post_id' => $post_id
            ]
        ];

    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        error_log('Gallery layout update error: ' . $e->getMessage());
    }

    wp_send_json($response);
}
add_action('wp_ajax_re_update_gallery_layout', 're_handle_gallery_layout_update'); 
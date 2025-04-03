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
 * Handle AJAX request to update gallery data
 */
function re_handle_gallery_update() {
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

        // Get and validate gallery data
        $gallery_data = isset($_POST['gallery_data']) ? $_POST['gallery_data'] : '';
        if (empty($gallery_data)) {
            throw new Exception('No gallery data provided.');
        }

        // Decode JSON data
        $decoded_data = json_decode(wp_unslash($gallery_data), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data: ' . json_last_error_msg());
        }

        // Validate gallery data
        $valid_data = [];
        foreach ($decoded_data as $item) {
            if (!isset($item['images']) || !is_array($item['images'])) {
                continue;
            }

            $images = array_map('absint', $item['images']);
            $images = array_filter($images, function($id) {
                return wp_attachment_is_image($id);
            });

            if (!empty($images)) {
                $valid_data[] = [
                    'layout' => 'gallery', // Using a standard layout for all images
                    'images' => $images
                ];
            }
        }

        // Update post meta
        update_post_meta($post_id, '_work_gallery_data', wp_json_encode($valid_data));

        // Success response
        $response = [
            'success' => true,
            'data' => [
                'message' => 'Gallery updated successfully.',
                'gallery_data' => $valid_data,
                'post_id' => $post_id
            ]
        ];

    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        error_log('Gallery update error: ' . $e->getMessage());
    }

    wp_send_json($response);
}
add_action('wp_ajax_re_update_gallery', 're_handle_gallery_update');

/**
 * Handle AJAX request to update gallery layout (legacy version kept for compatibility)
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

        // Success response - we're no longer validating or using the layout, just returning success
        $response = [
            'success' => true,
            'data' => [
                'message' => 'Layout handled successfully.',
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
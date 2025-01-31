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
    $response = ['success' => false];

    try {
        // Verify nonce
        if (!check_ajax_referer('re_work_meta', 'nonce', false)) {
            throw new Exception(__('Invalid security token.', 're'));
        }

        // Verify user capabilities
        if (!current_user_can('edit_posts')) {
            throw new Exception(__('You do not have permission to edit posts.', 're'));
        }

        // Get and validate post ID
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id || get_post_type($post_id) !== 'work') {
            throw new Exception(__('Invalid post ID.', 're'));
        }

        // Get and validate layout
        $layout = isset($_POST['layout']) ? sanitize_text_field($_POST['layout']) : '';
        if (!in_array($layout, ['full', 'split'], true)) {
            throw new Exception(__('Invalid layout type.', 're'));
        }

        // Update the temporary layout meta
        $updated = update_post_meta($post_id, '_work_temp_gallery_layout', $layout);
        
        if ($updated === false) {
            throw new Exception(__('Failed to update layout in database.', 're'));
        }

        $response = [
            'success' => true,
            'data' => [
                'message' => __('Layout updated successfully.', 're'),
                'layout' => $layout
            ]
        ];

    } catch (Exception $e) {
        $response = [
            'success' => false,
            'data' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]
        ];

        // Log error for debugging
        error_log(sprintf(
            '[Work Gallery Error] %s in %s:%s. Post ID: %s, Layout: %s',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            isset($_POST['post_id']) ? $_POST['post_id'] : 'not set',
            isset($_POST['layout']) ? $_POST['layout'] : 'not set'
        ));
    }

    // Send JSON response
    wp_send_json($response);
}
add_action('wp_ajax_re_update_gallery_layout', 're_handle_gallery_layout_update'); 
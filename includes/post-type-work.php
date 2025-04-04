<?php
/**
 * Work Custom Post Type
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('RE_VERSION')) {
    define('RE_VERSION', '1.0.0');
}

/**
 * Register custom post types
 */
function re_register_post_types() {
    // Work CPT
    $labels = array(
        'name'               => _x('Work', 'post type general name', 're'),
        'singular_name'      => _x('Work', 'post type singular name', 're'),
        'menu_name'          => _x('Work', 'admin menu', 're'),
        'name_admin_bar'     => _x('Work', 'add new on admin bar', 're'),
        'add_new'            => _x('Add New', 'work', 're'),
        'add_new_item'       => __('Add New Work', 're'),
        'new_item'          => __('New Work', 're'),
        'edit_item'         => __('Edit Work', 're'),
        'view_item'         => __('View Work', 're'),
        'all_items'         => __('All Work', 're'),
        'search_items'      => __('Search Work', 're'),
        'not_found'         => __('No work found.', 're'),
        'not_found_in_trash'=> __('No work found in Trash.', 're')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'work'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 5,
        'menu_icon'         => 'dashicons-portfolio',
        'supports'          => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields')
    );

    register_post_type('work', $args);

    // Register Work Categories
    $cat_labels = array(
        'name'              => _x('Work Categories', 'taxonomy general name', 're'),
        'singular_name'     => _x('Work Category', 'taxonomy singular name', 're'),
        'search_items'      => __('Search Work Categories', 're'),
        'all_items'         => __('All Work Categories', 're'),
        'parent_item'       => __('Parent Work Category', 're'),
        'parent_item_colon' => __('Parent Work Category:', 're'),
        'edit_item'         => __('Edit Work Category', 're'),
        'update_item'       => __('Update Work Category', 're'),
        'add_new_item'      => __('Add New Work Category', 're'),
        'new_item_name'     => __('New Work Category Name', 're'),
        'menu_name'         => __('Categories', 're'),
    );

    register_taxonomy('work_category', 'work', array(
        'hierarchical'      => true,
        'labels'           => $cat_labels,
        'show_ui'          => true,
        'show_in_rest'     => true,
        'show_admin_column'=> true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'work-category'),
    ));

    // Register Work Tags
    $tag_labels = array(
        'name'              => _x('Work Tags', 'taxonomy general name', 're'),
        'singular_name'     => _x('Work Tag', 'taxonomy singular name', 're'),
        'search_items'      => __('Search Work Tags', 're'),
        'all_items'         => __('All Work Tags', 're'),
        'edit_item'         => __('Edit Work Tag', 're'),
        'update_item'       => __('Update Work Tag', 're'),
        'add_new_item'      => __('Add New Work Tag', 're'),
        'new_item_name'     => __('New Work Tag Name', 're'),
        'menu_name'         => __('Tags', 're'),
    );

    register_taxonomy('work_tag', 'work', array(
        'hierarchical'      => false,
        'labels'           => $tag_labels,
        'show_ui'          => true,
        'show_in_rest'     => true,
        'show_admin_column'=> true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'work-tag'),
    ));
}
add_action('init', 're_register_post_types');

/**
 * Add custom meta box wrapper
 */
function re_add_work_meta_boxes() {
    add_meta_box(
        'work_meta_tabs',
        __('Work Information', 're'),
        're_work_meta_tabs_html',
        'work',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 're_add_work_meta_boxes');

/**
 * Restrict allowed blocks in the work post type editor
 *
 * @param bool|array $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
 * @param WP_Block_Editor_Context $editor_context The current block editor context.
 * @return bool|array Array of allowed block types or boolean to enable/disable all.
 */
function re_work_allowed_block_types($allowed_block_types, $editor_context) {
    if (!empty($editor_context->post) && 'work' === $editor_context->post->post_type) {
        return array(
            'core/paragraph',
            'core/heading',
            'core/quote',
            'core/list',
            'core/code',
            'core/details',
            'core/preformatted',
            'core/pullquote'
        );
    }
    return $allowed_block_types;
}
add_filter('allowed_block_types_all', 're_work_allowed_block_types', 10, 2);

/**
 * Render tabbed meta box HTML
 *
 * @param WP_Post $post Current post object
 */
function re_work_meta_tabs_html($post) {
    // Get meta values
    $employer = get_post_meta($post->ID, '_work_employer', true);
    $role = get_post_meta($post->ID, '_work_role', true);
    $start_date = get_post_meta($post->ID, '_work_start_date', true);
    $end_date = get_post_meta($post->ID, '_work_end_date', true);
    $location = get_post_meta($post->ID, '_work_location', true);
    $project = get_post_meta($post->ID, '_work_project', true);
    $has_external_link = get_post_meta($post->ID, '_work_has_external_link', true);
    $external_link = get_post_meta($post->ID, '_work_external_link', true);
    $gallery_data = json_decode(get_post_meta($post->ID, '_work_gallery_data', true) ?: '[]', true);

    // Add nonce for security
    wp_nonce_field('re_work_meta_box', 're_work_meta_box_nonce');
    ?>
    <div class="work-meta-tabs">
        <div class="work-meta-tabs__nav">
            <button type="button" class="work-meta-tabs__nav-item active" data-tab="details">
                <?php esc_html_e('Work Details', 're'); ?>
            </button>
            <button type="button" class="work-meta-tabs__nav-item" data-tab="gallery">
                <?php esc_html_e('Project Gallery', 're'); ?>
            </button>
        </div>

        <div class="work-meta-tabs__content">
            <!-- Work Details Tab -->
            <div class="work-meta-tabs__panel active" data-tab="details">
                <p>
                    <label for="work_project"><?php esc_html_e('Project Name', 're'); ?></label><br>
                    <input type="text" id="work_project" name="work_project" value="<?php echo esc_attr($project); ?>" class="widefat">
                    <span class="description"><?php esc_html_e('The name of the specific project or product worked on', 're'); ?></span>
                </p>

                <!-- External Link Toggle and Field -->
                <p>
                    <label for="work_has_external_link">
                        <input type="checkbox" id="work_has_external_link" name="work_has_external_link" value="1" <?php checked($has_external_link, '1'); ?>>
                        <?php esc_html_e('Use External Link?', 're'); ?>
                    </label>
                    <span class="description"><?php esc_html_e('Check this to use an external URL instead of defualting to the work post.', 're'); ?></span>
                </p>

                <p id="work_external_link_wrapper">
                    <label for="work_external_link"><?php esc_html_e('External Link', 're'); ?></label><br>
                    <input type="url" id="work_external_link" name="work_external_link" value="<?php echo esc_url($external_link); ?>" class="widefat">
                    <span class="description"><?php esc_html_e('The URL where this project can be viewed. Make sure to include https://', 're'); ?></span>
                </p>

                <p>
                    <label for="work_employer"><?php esc_html_e('Employer/Client', 're'); ?></label><br>
                    <input type="text" id="work_employer" name="work_employer" value="<?php echo esc_attr($employer); ?>" class="widefat">
                </p>
                <p>
                    <label for="work_role"><?php esc_html_e('Role', 're'); ?></label><br>
                    <input type="text" id="work_role" name="work_role" value="<?php echo esc_attr($role); ?>" class="widefat">
                </p>
                <p>
                    <label for="work_start_date"><?php esc_html_e('Start Date', 're'); ?></label><br>
                    <input type="month" id="work_start_date" name="work_start_date" value="<?php echo esc_attr($start_date); ?>" class="widefat">
                </p>
                <p>
                    <label for="work_end_date"><?php esc_html_e('End Date', 're'); ?></label><br>
                    <input type="month" id="work_end_date" name="work_end_date" value="<?php echo esc_attr($end_date); ?>" class="widefat">
                    <span class="description"><?php esc_html_e('Leave empty if this is your current position', 're'); ?></span>
                </p>
                <p>
                    <label for="work_location"><?php esc_html_e('Location', 're'); ?></label><br>
                    <input type="text" id="work_location" name="work_location" value="<?php echo esc_attr($location); ?>" class="widefat">
                    <span class="description"><?php esc_html_e('City, Country or Remote', 're'); ?></span>
                </p>
            </div>

            <!-- Gallery Tab -->
            <div class="work-meta-tabs__panel" data-tab="gallery">
                <div class="work-gallery-container">
                    <input type="hidden" name="work_gallery_data" id="work_gallery_data" value="<?php 
                        echo esc_attr(get_post_meta($post->ID, '_work_gallery_data', true)); 
                    ?>">
                    
                    <div class="work-gallery-controls">
                        <button type="button" class="button" id="work_gallery_upload">
                            <?php esc_html_e('Add Images', 're'); ?>
                        </button>
                    </div>

                    <p class="gallery-help-text">
                        <?php esc_html_e('Select one or more images to add to the project gallery.', 're'); ?>
                    </p>
                    
                    <div class="work-gallery-sections">
                        <?php
                        
                        if (!empty($gallery_data)) {
                            echo '<div class="gallery-section">';
                            echo '<div class="gallery-section-header">';
                            echo '<h4>' . esc_html__('Project Images Gallery', 're') . '</h4>';
                            echo '</div>';
                            
                            echo '<div class="gallery-images-container">';
                            foreach ($gallery_data as $section) {
                                if (isset($section['images']) && is_array($section['images'])) {
                                    foreach ($section['images'] as $image_id) {
                                        $image = wp_get_attachment_image($image_id, 'thumbnail');
                                        if ($image) {
                                            echo '<div class="gallery-image-preview">';
                                            echo $image;
                                            echo '<button type="button" class="remove-image" data-id="' . esc_attr($image_id) . '">×</button>';
                                            echo '</div>';
                                        }
                                    }
                                }
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Validate gallery data structure
 *
 * @param array $gallery_data The gallery data to validate
 * @return bool|array False if invalid, sanitized array if valid
 */
function re_validate_gallery_data($gallery_data) {
    if (!is_array($gallery_data)) {
        return false;
    }

    $sanitized_data = array();

    foreach ($gallery_data as $section) {
        // Check required keys exist
        if (!isset($section['layout']) || !isset($section['images']) || !is_array($section['images'])) {
            continue; // Skip invalid sections instead of failing completely
        }

        // Validate images
        $images = array_map('absint', $section['images']);
        $images = array_filter($images, function($id) {
            return wp_attachment_is_image($id);
        });

        // Only add section if it has valid images
        if (count($images) > 0) {
            $sanitized_data[] = array(
                'layout' => sanitize_text_field($section['layout']),
                'images' => $images
            );
        }
    }

    return $sanitized_data;
}

/**
 * Save work meta box data
 *
 * @param int $post_id Post ID
 */
function re_save_work_meta_box($post_id) {
    if (!isset($_POST['re_work_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['re_work_meta_box_nonce'], 're_work_meta_box') ||
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        !current_user_can('edit_post', $post_id)) {
        return;
    }

    // Regular text fields
    $text_fields = array(
        'work_project',
        'work_employer',
        'work_role',
        'work_start_date',
        'work_end_date',
        'work_location'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                '_' . $field,
                sanitize_text_field($_POST[$field])
            );
        }
    }

    // Handle external link checkbox
    $has_external_link = isset($_POST['work_has_external_link']) ? '1' : '';
    update_post_meta($post_id, '_work_has_external_link', $has_external_link);

    // Handle external link URL
    if ($has_external_link && isset($_POST['work_external_link'])) {
        update_post_meta(
            $post_id,
            '_work_external_link',
            esc_url_raw($_POST['work_external_link'])
        );
    } else {
        delete_post_meta($post_id, '_work_external_link');
    }

    // Handle gallery data with debugging
    if (isset($_POST['work_gallery_data'])) {
        $raw_data = wp_unslash($_POST['work_gallery_data']);
        
        // Log raw data for debugging
        error_log('Raw gallery data: ' . $raw_data);
        
        $gallery_data = json_decode($raw_data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error: ' . json_last_error_msg());
            return;
        }
        
        // Validate and sanitize gallery data
        $validated_data = re_validate_gallery_data($gallery_data);
        
        if ($validated_data !== false) {
            update_post_meta(
                $post_id,
                '_work_gallery_data',
                wp_json_encode($validated_data)
            );
        } else {
            error_log('Gallery validation failed for post ' . $post_id);
            
            // Add error notice if validation fails
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p>' . esc_html__('Gallery data validation failed. Please check your gallery sections.', 're') . '</p>';
                echo '</div>';
            });
        }
    }
}
add_action('save_post_work', 're_save_work_meta_box'); 
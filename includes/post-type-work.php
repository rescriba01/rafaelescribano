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
    $gallery_images = get_post_meta($post->ID, '_work_gallery_images', true);
    $gallery_layout = get_post_meta($post->ID, '_work_gallery_layout', true);

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
                    <input type="hidden" name="work_gallery_images" id="work_gallery_images" value="<?php echo esc_attr($gallery_images); ?>">
                    
                    <div class="work-gallery-preview" id="work_gallery_preview">
                        <?php
                        if ($gallery_images) {
                            $image_ids = explode(',', $gallery_images);
                            foreach ($image_ids as $image_id) {
                                $image = wp_get_attachment_image($image_id, 'thumbnail');
                                if ($image) {
                                    echo '<div class="gallery-image-preview">';
                                    echo $image;
                                    echo '<button type="button" class="remove-image" data-id="' . esc_attr($image_id) . '">×</button>';
                                    echo '</div>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <p>
                        <button type="button" class="button" id="work_gallery_upload"><?php esc_html_e('Add Images', 're'); ?></button>
                    </p>

                    <p>
                        <label for="work_gallery_layout"><?php esc_html_e('Image Layout', 're'); ?></label><br>
                        <select name="work_gallery_layout" id="work_gallery_layout" class="widefat">
                            <option value="full" <?php selected($gallery_layout, 'full'); ?>><?php esc_html_e('Full Width', 're'); ?></option>
                            <option value="split" <?php selected($gallery_layout, 'split'); ?>><?php esc_html_e('Split 50/50', 're'); ?></option>
                        </select>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
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

    $fields = array(
        'work_project',
        'work_employer',
        'work_role',
        'work_start_date',
        'work_end_date',
        'work_location',
        'work_gallery_images',
        'work_gallery_layout'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                '_' . $field,
                sanitize_text_field($_POST[$field])
            );
        }
    }
}
add_action('save_post_work', 're_save_work_meta_box'); 
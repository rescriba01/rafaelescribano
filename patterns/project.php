<?php
/**
 * Title: Project
 * Slug: re/project
 * Categories: featured
 * Keywords: project, work, portfolio
 * Block Types: core/group
 * 
 * @package re
 * @since 1.0.0
 */

// Get all work posts
$work_query = new WP_Query([
    'post_type' => 'work',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
]);

$total_posts = $work_query->found_posts;
$current_post = 1;

if ($work_query->have_posts()) : 
    while ($work_query->have_posts()) : $work_query->the_post();
        // Get work meta data
        $employer = get_post_meta(get_the_ID(), '_work_employer', true);
        $role = get_post_meta(get_the_ID(), '_work_role', true);
        $start_date = get_post_meta(get_the_ID(), '_work_start_date', true);
        $location = get_post_meta(get_the_ID(), '_work_location', true);
        $project = get_post_meta(get_the_ID(), '_work_project', true);
        $gallery_data = json_decode(get_post_meta(get_the_ID(), '_work_gallery_data', true) ?: '[]', true);
        $tags = get_the_terms(get_the_ID(), 'work_tag');
        $post_count = sprintf('%02d/%02d', $current_post, $total_posts);
?>

<!-- wp:group {"tagName":"article","metadata":{"name":"Project"},"align":"full","className":"project","layout":{"type":"constrained"}} -->
<article class="wp-block-group alignfull project"><!-- wp:group {"metadata":{"name":"Project-content"},"className":"project-content","layout":{"type":"default"}} -->
<div class="wp-block-group project-content"><!-- wp:group {"metadata":{"name":"Project-header"},"className":"project-header","layout":{"type":"default"}} -->
<div class="wp-block-group project-header"><!-- wp:paragraph {"className":"project-count"} -->
<p class="project-count"><?php echo esc_html($post_count); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"project-title"} -->
<h1 class="project-title"><?php echo esc_html(get_the_title()); ?> - <?php echo esc_html($project); ?></h1>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Project-info"},"className":"project-info","layout":{"type":"default"}} -->
<div class="wp-block-group project-info">
    <?php if ($project) : ?>
    <!-- wp:group {"metadata":{"name":"Meta-field"},"className":"meta-field","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group meta-field">
        <!-- wp:paragraph {"className":"meta-label"} -->
        <p class="meta-label">Project</p>
        <!-- /wp:paragraph -->
        <!-- wp:paragraph {"className":"project-value"} -->
        <p class="project-value"><?php echo esc_html($project); ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
    <?php endif; ?>

    <?php if ($role || $start_date) : ?>
    <!-- wp:group {"metadata":{"name":"Project-meta"},"className":"project-meta","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group project-meta">
        <?php if ($role) : ?>
        <!-- wp:group {"metadata":{"name":"Meta-field-role"},"className":"meta-field","layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group meta-field">
            <!-- wp:paragraph {"className":"meta-label"} -->
            <p class="meta-label">Role</p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph {"className":"project-role"} -->
            <p class="project-role"><?php echo esc_html($role); ?></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <?php endif; ?>

        <?php if ($start_date) : ?>
        <!-- wp:group {"metadata":{"name":"Meta-field-date"},"className":"meta-field","layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group meta-field">
            <!-- wp:paragraph {"className":"meta-label"} -->
            <p class="meta-label">Date</p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph {"className":"project-date"} -->
            <p class="project-date"><?php echo esc_html($start_date); ?></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <?php endif; ?>
    </div>
    <!-- /wp:group -->
    <?php endif; ?>
</div>
<!-- /wp:group -->

<?php if ($tags && !is_wp_error($tags)) : ?>
<!-- wp:group {"metadata":{"name":"Project-tags"},"className":"project-tags-wrapper desktop-only","layout":{"type":"default"}} -->
<div class="wp-block-group project-tags-wrapper desktop-only">
    <!-- wp:group {"metadata":{"name":"Tags-list"},"className":"project-tags","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group project-tags">
        <?php foreach ($tags as $tag) : ?>
        <!-- wp:paragraph {"className":"project-tag"} -->
        <p class="project-tag"><?php echo esc_html($tag->name); ?></p>
        <!-- /wp:paragraph -->
        <?php endforeach; ?>
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
<?php endif; ?>

<!-- wp:group {"metadata":{"name":"Project-gallery"},"className":"project-gallery-wrapper","layout":{"type":"default"}} -->
<div class="wp-block-group project-gallery-wrapper">
    <?php if (has_post_thumbnail()) : 
        $thumb_id = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_url($thumb_id, 'large');
    ?>
    <!-- wp:image {"id":<?php echo $thumb_id; ?>,"sizeSlug":"large","className":"project-image"} -->
    <figure class="wp-block-image size-large project-image"><img src="<?php echo esc_url($thumb_url); ?>" alt="" class="wp-image-<?php echo esc_attr($thumb_id); ?>"/></figure>
    <!-- /wp:image -->
    <?php endif; ?>

    <?php 
    if (!empty($gallery_data)) : 
        foreach ($gallery_data as $section) :
            $layout = $section['layout'];
            $images = $section['images'];

            if ($layout === 'split' && count($images) === 2) : ?>
                <!-- wp:columns {"className":"project-gallery split"} -->
                <div class="wp-block-columns project-gallery split">
                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <?php $img_url = wp_get_attachment_image_url($images[0], 'large'); ?>
                        <!-- wp:image {"id":<?php echo esc_attr($images[0]); ?>,"sizeSlug":"large","className":"project-gallery-image"} -->
                        <figure class="wp-block-image size-large project-gallery-image"><img src="<?php echo esc_url($img_url); ?>" alt="" class="wp-image-<?php echo esc_attr($images[0]); ?>"/></figure>
                        <!-- /wp:image -->
                    </div>
                    <!-- /wp:column -->

                    <!-- wp:column -->
                    <div class="wp-block-column">
                        <?php $img_url = wp_get_attachment_image_url($images[1], 'large'); ?>
                        <!-- wp:image {"id":<?php echo esc_attr($images[1]); ?>,"sizeSlug":"large","className":"project-gallery-image"} -->
                        <figure class="wp-block-image size-large project-gallery-image"><img src="<?php echo esc_url($img_url); ?>" alt="" class="wp-image-<?php echo esc_attr($images[1]); ?>"/></figure>
                        <!-- /wp:image -->
                    </div>
                    <!-- /wp:column -->
                </div>
                <!-- /wp:columns -->
            <?php elseif ($layout === 'full' && count($images) === 1) : 
                $img_url = wp_get_attachment_image_url($images[0], 'large');
            ?>
                <!-- wp:image {"id":<?php echo esc_attr($images[0]); ?>,"sizeSlug":"large","className":"project-gallery-image full"} -->
                <figure class="wp-block-image size-large project-gallery-image full"><img src="<?php echo esc_url($img_url); ?>" alt="" class="wp-image-<?php echo esc_attr($images[0]); ?>"/></figure>
                <!-- /wp:image -->
            <?php endif;
        endforeach;
    endif; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->

<?php
        $current_post++;
    endwhile;
    wp_reset_postdata();
endif;
?> 
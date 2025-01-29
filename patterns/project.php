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
$current_post = 1; // Start with first post

if ($work_query->have_posts()) : 
    while ($work_query->have_posts()) : $work_query->the_post();
        // Get work meta data
        $employer = get_post_meta(get_the_ID(), '_work_employer', true);
        $role = get_post_meta(get_the_ID(), '_work_role', true);
        $start_date = get_post_meta(get_the_ID(), '_work_start_date', true);
        $location = get_post_meta(get_the_ID(), '_work_location', true);
        
        // Get work categories and tags
        $categories = get_the_terms(get_the_ID(), 'work_category');
        $tags = get_the_terms(get_the_ID(), 'work_tag');
        
        // Format the post count
        $post_count = sprintf('%02d/%02d', $current_post, $total_posts);
?>

<!-- wp:group {"tagName":"section","className":"project-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group project-section">
    <!-- wp:group {"className":"project-header","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group project-header">
        <!-- wp:heading {"level":1,"className":"project-title"} -->
        <h1 class="project-title"><?php echo esc_html(get_the_title()); ?> - <?php echo esc_html($employer); ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"className":"project-count"} -->
        <p class="project-count"><?php echo esc_html($post_count); ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"className":"project-content"} -->
    <div class="wp-block-columns project-content">
        <!-- wp:column {"width":"60%"} -->
        <div class="wp-block-column" style="flex-basis:60%">
            <?php if (has_post_thumbnail()) : ?>
            <!-- wp:image {"sizeSlug":"large","className":"project-image"} -->
            <figure class="wp-block-image size-large project-image">
                <?php the_post_thumbnail('large'); ?>
            </figure>
            <!-- /wp:image -->
            <?php endif; ?>
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%">
            <!-- wp:group {"className":"project-meta","layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group project-meta">
                <?php if ($role) : ?>
                <!-- wp:paragraph {"className":"project-role"} -->
                <p class="project-role"><?php echo esc_html($role); ?></p>
                <!-- /wp:paragraph -->
                <?php endif; ?>

                <?php if ($start_date) : ?>
                <!-- wp:paragraph {"className":"project-date"} -->
                <p class="project-date"><?php echo esc_html($start_date); ?></p>
                <!-- /wp:paragraph -->
                <?php endif; ?>

                <?php if ($categories && !is_wp_error($categories)) : ?>
                <!-- wp:group {"className":"project-categories","layout":{"type":"flex","flexWrap":"wrap"}} -->
                <div class="wp-block-group project-categories">
                    <?php foreach ($categories as $category) : ?>
                    <!-- wp:paragraph {"className":"project-category"} -->
                    <p class="project-category"><?php echo esc_html($category->name); ?></p>
                    <!-- /wp:paragraph -->
                    <?php endforeach; ?>
                </div>
                <!-- /wp:group -->
                <?php endif; ?>

                <?php if ($tags && !is_wp_error($tags)) : ?>
                <!-- wp:group {"className":"project-tags","layout":{"type":"flex","flexWrap":"wrap"}} -->
                <div class="wp-block-group project-tags">
                    <?php foreach ($tags as $tag) : ?>
                    <!-- wp:paragraph {"className":"project-tag"} -->
                    <p class="project-tag"><?php echo esc_html($tag->name); ?></p>
                    <!-- /wp:paragraph -->
                    <?php endforeach; ?>
                </div>
                <!-- /wp:group -->
                <?php endif; ?>
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"className":"project-description"} -->
            <div class="wp-block-group project-description">
                <?php the_content(); ?>
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</section>
<!-- /wp:group -->

<?php
        $current_post++;
    endwhile;
    wp_reset_postdata();
endif;
?> 
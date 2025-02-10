<?php
/**
 * Title: Work Card Stack
 * Slug: re/work-card-stack
 * Categories: query
 * Block Types: core/query
 * 
 * @package re
 * @since 1.0.0
 */

// Get unique employers from work posts
$employers = [];
$work_query = new WP_Query([
    'post_type' => 'work',
    'posts_per_page' => -1,
    'orderby' => 'meta_value',
    'meta_key' => '_work_employer',
    'order' => 'ASC'
]);

if ($work_query->have_posts()) {
    while ($work_query->have_posts()) {
        $work_query->the_post();
        $employer = get_post_meta(get_the_ID(), '_work_employer', true);
        if (!empty($employer) && !isset($employers[$employer])) {
            $employers[$employer] = [];
        }
        if (!empty($employer)) {
            $employers[$employer][] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'project' => get_post_meta(get_the_ID(), '_work_project', true),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                'permalink' => get_the_permalink()
            ];
        }
    }
    wp_reset_postdata();
}

// Only proceed if we have employers
if (!empty($employers)):
?>

<!-- wp:group {"className":"work-card-stacks","align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group work-card-stacks alignwide">
    <?php foreach ($employers as $employer => $projects): ?>
    <!-- wp:group {"className":"card-stack","layout":{"type":"constrained"}} -->
    <div class="wp-block-group card-stack">
        <!-- wp:heading {"level":2,"className":"employer-title"} -->
        <h2 class="wp-block-heading employer-title"><?php echo esc_html($employer); ?></h2>
        <!-- /wp:heading -->

        <!-- wp:group {"className":"cards-wrapper","layout":{"type":"constrained"}} -->
        <div class="wp-block-group cards-wrapper">
            <?php foreach ($projects as $project): ?>
            <!-- wp:group {"className":"card","layout":{"type":"constrained"}} -->
            <div class="wp-block-group card">
                <?php if (!empty($project['image'])): ?>
                <!-- wp:image {"sizeSlug":"large","linkDestination":"custom"} -->
                <figure class="wp-block-image size-large">
                    <a href="<?php echo esc_url($project['permalink']); ?>">
                        <img src="<?php echo esc_url($project['image']); ?>" alt="<?php echo esc_attr($project['title']); ?>"/>
                    </a>
                </figure>
                <!-- /wp:image -->
                <?php endif; ?>

                <!-- wp:group {"className":"card-content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group card-content">
                    <!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading"><?php echo esc_html($project['title']); ?></h3>
                    <!-- /wp:heading -->

                    <?php if (!empty($project['project'])): ?>
                    <!-- wp:paragraph {"className":"project-name"} -->
                    <p class="project-name"><?php echo esc_html($project['project']); ?></p>
                    <!-- /wp:paragraph -->
                    <?php endif; ?>
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
            <?php endforeach; ?>
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
    <?php endforeach; ?>
</div>
<!-- /wp:group -->

<?php endif; ?> 
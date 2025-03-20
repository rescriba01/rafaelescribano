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

// Get work posts and organize by employer
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
        if (!empty($employer)) {
            if (!isset($employers[$employer])) {
                $employers[$employer] = [];
            }
            $employers[$employer][] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'project' => get_post_meta(get_the_ID(), '_work_project', true),
                'excerpt' => get_the_excerpt()
            ];
        }
    }
    wp_reset_postdata();
}

if (!empty($employers)):
?>

<!-- wp:group {"className":"re-work-grid","align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group re-work-grid alignwide">
    <?php foreach ($employers as $employer => $projects): 
        $employer_class = 're-work-employer employer-' . sanitize_title($employer);
    ?>
    <!-- wp:group {"className":"re-work-employer","layout":{"type":"constrained"}} -->
    <div class="wp-block-group <?php echo esc_attr($employer_class); ?>">
        <!-- wp:group {"className":"re-project-stack","layout":{"type":"constrained"}} -->
        <div class="wp-block-group re-project-stack">
            <!-- wp:group {"className":"re-project-card re-project-card--hidden","layout":{"type":"constrained"}} -->
            <div class="wp-block-group re-project-card re-project-card--hidden">
                <!-- wp:group {"className":"re-project-card__content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group re-project-card__content">
                    <!-- wp:group {"className":"re-project-card__header","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group re-project-card__header">
                        <!-- wp:html -->
                        <div class="re-project-card__avatar"></div>
                        <!-- /wp:html -->
                        <!-- wp:paragraph {"className":"re-project-card__employer"} -->
                        <p class="re-project-card__employer"></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:paragraph {"className":"text has-large-font-size"} -->
                    <p class="text has-large-font-size"></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph {"className":"text has-medium-font-size"} -->
                    <p class="text has-medium-font-size"></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph {"className":"text has-small-font-size"} -->
                    <p class="text has-small-font-size"></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:html -->
                    <div class="re-project-card__button"></div>
                    <!-- /wp:html -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <?php foreach ($projects as $project): 
                $first_letter = mb_substr($employer, 0, 1);
            ?>
            <!-- wp:group {"className":"re-project-card","layout":{"type":"constrained"}} -->
            <div class="wp-block-group re-project-card">
                <!-- wp:group {"className":"re-project-card__content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group re-project-card__content">
                    <!-- wp:group {"className":"re-project-card__header","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group re-project-card__header">
                        <!-- wp:html -->
                        <div class="re-project-card__avatar" data-letter="<?php echo esc_attr($first_letter); ?>"></div>
                        <!-- /wp:html -->
                        <!-- wp:paragraph {"className":"re-project-card__employer has-medium-font-size"} -->
                        <p class="re-project-card__employer has-medium-font-size"><?php echo esc_html($employer); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                    
                    <!-- wp:paragraph {"className":"text has-large-font-size"} -->
                    <p class="text has-large-font-size"><?php echo esc_html($project['title']); ?></p>
                    <!-- /wp:paragraph -->

                    <?php if (!empty($project['project'])): ?>
                    <!-- wp:paragraph {"className":"text has-medium-font-size"} -->
                    <p class="text has-medium-font-size"><?php echo esc_html($project['project']); ?></p>
                    <!-- /wp:paragraph -->
                    <?php endif; ?>

                    <!-- wp:paragraph {"className":"text has-small-font-size"} -->
                    <p class="text has-small-font-size"><?php echo wp_kses_post($project['excerpt']); ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:html -->
                    <a href="<?php echo esc_url(get_permalink($project['id'])); ?>" class="re-project-card__button has-small-font-size">View Details</a>
                    <!-- /wp:html -->
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
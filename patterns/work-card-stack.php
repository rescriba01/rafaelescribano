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
    <?php foreach ($employers as $employer => $projects): ?>
    <!-- wp:group {"className":"re-work-employer","layout":{"type":"constrained"}} -->
    <div class="wp-block-group re-work-employer" data-employer="<?php echo esc_attr($employer); ?>">
        <!-- wp:group {"className":"re-project-stack","layout":{"type":"constrained"}} -->
        <div class="wp-block-group re-project-stack">
            <!-- wp:group {"className":"re-project-card re-project-card--hidden","layout":{"type":"constrained"}} -->
            <div class="wp-block-group re-project-card re-project-card--hidden">
                <div class="re-project-card__content">
                    <div class="re-project-card__header">
                        <div class="re-project-card__avatar"></div>
                        <div class="re-project-card__employer"></div>
                    </div>
                    <div class="text has-large-font-size"></div>
                    <div class="text has-medium-font-size"></div>
                    <div class="text has-small-font-size"></div>
                    <div class="re-project-card__button"></div>
                </div>
            </div>
            <!-- /wp:group -->

            <?php foreach ($projects as $project): 
                $first_letter = mb_substr($employer, 0, 1);
            ?>
            <!-- wp:group {"className":"re-project-card","layout":{"type":"constrained"}} -->
            <div class="wp-block-group re-project-card">
                <div class="re-project-card__content">
                    <div class="re-project-card__header">
                        <div class="re-project-card__avatar" data-letter="<?php echo esc_attr($first_letter); ?>"></div>
                        <div class="re-project-card__employer has-medium-font-size"><?php echo esc_html($employer); ?></div>
                    </div>
                    <div class="text has-large-font-size"><?php echo esc_html($project['title']); ?></div>
                    <?php if (!empty($project['project'])): ?>
                    <div class="text has-medium-font-size"><?php echo esc_html($project['project']); ?></div>
                    <?php endif; ?>
                    <div class="text has-small-font-size"><?php echo wp_kses_post($project['excerpt']); ?></div>
                    <a href="<?php echo esc_url(get_permalink($project['id'])); ?>" class="re-project-card__button has-small-font-size">View Details</a>
                </div>
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
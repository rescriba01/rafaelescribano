<?php
/**
 * Title: Projects
 * Slug: re/projects
 * Categories: re
 */

// Query work posts
$args = array(
    'post_type' => 'work',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);

$work_query = new WP_Query($args);

// Pre-process post data to keep markup clean
$projects = array();
if ($work_query->have_posts()) {
    while ($work_query->have_posts()) {
        $work_query->the_post();
        $post_id = get_the_ID();
        
        // Debug external link data
        $has_external_link = get_post_meta($post_id, '_work_has_external_link', true);
        $external_link = get_post_meta($post_id, '_work_external_link', true);

        $projects[] = array(
            'id' => $post_id,
            'title' => get_the_title(),
            'excerpt' => get_the_excerpt(),
            'permalink' => get_permalink(),
            'thumbnail_id' => get_post_thumbnail_id(),
            'has_thumbnail' => has_post_thumbnail(),
            'tags' => get_the_terms($post_id, 'work_tag'),
            'has_external_link' => $has_external_link,
            'external_link' => $external_link,
            'link' => ($has_external_link && $external_link) ? esc_url($external_link) : get_permalink(),
            'target' => ($has_external_link && $external_link) ? ' target="_blank" rel="noopener noreferrer"' : ''
        );
    }
    wp_reset_postdata();
}
?>

<!-- wp:group {"metadata":{"name":"Projects"},"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"right":"0","left":"0","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0">
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group">
        <!-- wp:heading -->
        <h2 class="wp-block-heading">Projects</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary"} -->
        <p class="has-secondary-color has-text-color has-link-color">Just some of the things I've gotten to work on</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"className":"project-grid","layout":{"type":"grid","minimumColumnWidth":"14rem"}} -->
    <div class="wp-block-group project-grid">
        <?php foreach ($projects as $project) : ?>
        <!-- wp:group {"className":"project-grid-item","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"border":{"width":"1px","color":"#c8c8c8","radius":"20px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group project-grid-item has-border-color" style="border-color:#c8c8c8;border-width:1px;border-radius:20px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10)">
            <!-- wp:group {"className":"project-image-wrapper"} -->
            <div class="wp-block-group project-image-wrapper">
                <?php if ($project['has_thumbnail']) : ?>
                <!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"2048x2048","linkDestination":"none"} -->
                <figure class="wp-block-image size-2048x2048">
                    <?php 
                    echo wp_get_attachment_image(
                        $project['thumbnail_id'],
                        '2048x2048',
                        false,
                        array(
                            'style' => 'aspect-ratio:4/3;object-fit:cover',
                            'sizes' => '(max-width: 781px) 100vw, (max-width: 1280px) 50vw, 33vw',
                            'loading' => 'lazy',
                            'decoding' => 'async'
                        )
                    );
                    ?>
                </figure>
                <!-- /wp:image -->
                <?php endif; ?>

                <!-- wp:group {"className":"project-overlay"} -->
                <div class="wp-block-group project-overlay">
                    <?php if ($project['tags'] && !is_wp_error($project['tags'])) : ?>
                    <!-- wp:group {"className":"project-details__meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                    <div class="wp-block-group project-details__meta">
                        <?php foreach ($project['tags'] as $tag) : ?>
                        <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"small"} -->
                        <p class="has-base-color has-text-color has-link-color has-small-font-size">#<?php echo esc_html($tag->name); ?></p>
                        <!-- /wp:paragraph -->
                        <?php endforeach; ?>
                    </div>
                    <!-- /wp:group -->
                    <?php endif; ?>
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"className":"project-details","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"}} -->
            <div class="wp-block-group project-details" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:0">
                <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"fontSize":"medium"} -->
                <h3 class="wp-block-heading has-medium-font-size" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0">
                    <a href="<?php echo $project['link']; ?>"<?php echo $project['target']; ?>><?php echo esc_html($project['title']); ?></a>
                </h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"className":"project-details__description","style":{"spacing":{"margin":{"top":"var:preset|spacing|10","right":"0","bottom":"0","left":"0"}},"typography":{"lineHeight":"1.5"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small"} -->
                <p class="project-details__description has-secondary-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--10);margin-right:0;margin-bottom:0;margin-left:0;line-height:1.5">
                    <?php echo esc_html($project['excerpt']); ?>
                </p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <?php endforeach; ?>
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
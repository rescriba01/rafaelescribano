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
        <?php if ($work_query->have_posts()) : while ($work_query->have_posts()) : $work_query->the_post(); 
            // Get post meta/taxonomies
            $tags = get_the_terms(get_the_ID(), 'work_tag');
        ?>
        <!-- wp:group {"className":"project-grid-item","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"border":{"width":"1px","color":"#c8c8c8","radius":"20px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group project-grid-item has-border-color" style="border-color:#c8c8c8;border-width:1px;border-radius:20px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10)">
            <!-- wp:group {"className":"project-image-wrapper"} -->
            <div class="wp-block-group project-image-wrapper">
                <?php if (has_post_thumbnail()) : ?>
                <!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full">
                    <?php the_post_thumbnail('full', array('style' => 'aspect-ratio:4/3;object-fit:cover')); ?>
                </figure>
                <!-- /wp:image -->
                <?php endif; ?>

                <!-- wp:group {"className":"project-overlay"} -->
                <div class="wp-block-group project-overlay">
                    <?php if ($tags && !is_wp_error($tags)) : ?>
                    <!-- wp:group {"className":"project-details__meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                    <div class="wp-block-group project-details__meta">
                        <?php foreach ($tags as $tag) : ?>
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
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"className":"project-details__description","style":{"spacing":{"margin":{"top":"var:preset|spacing|10","right":"0","bottom":"0","left":"0"}},"typography":{"lineHeight":"1.5"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small"} -->
                <p class="project-details__description has-secondary-color has-text-color has-link-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--10);margin-right:0;margin-bottom:0;margin-left:0;line-height:1.5">
                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                </p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
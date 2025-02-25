<?php
/**
 * Title: Work Content
 * Slug: re/work-content
 * Categories: custom
 * 
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get work meta data
 *
 * @return array Work meta data
 */
function re_get_work_meta() {
    return array(
        'employer' => get_post_meta(get_the_ID(), '_work_employer', true),
        'project' => get_post_meta(get_the_ID(), '_work_project', true),
        'role' => get_post_meta(get_the_ID(), '_work_role', true),
        'location' => get_post_meta(get_the_ID(), '_work_location', true),
        'start_date' => get_post_meta(get_the_ID(), '_work_start_date', true),
        'end_date' => get_post_meta(get_the_ID(), '_work_end_date', true)
    );
}

/**
 * Process gallery data
 *
 * @return array Processed gallery sections
 */
function re_get_gallery_sections() {
    $gallery_sections = array();
    $gallery_data = json_decode(get_post_meta(get_the_ID(), '_work_gallery_data', true) ?: '[]', true);

    if (!empty($gallery_data)) {
        foreach ($gallery_data as $section) {
            $gallery_section = array(
                'layout' => $section['layout'],
                'images' => array()
            );
            
            foreach ($section['images'] as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_meta = wp_get_attachment_metadata($image_id);
                $image_width = isset($image_meta['width']) ? $image_meta['width'] : 1200;
                $image_height = isset($image_meta['height']) ? $image_meta['height'] : 800;
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title($image_id);
                
                $gallery_section['images'][] = array(
                    'id' => $image_id,
                    'full_url' => $image_url,
                    'meta' => $image_meta,
                    'html' => wp_get_attachment_image(
                        $image_id,
                        'large',
                        false,
                        array(
                            'class' => 'gallery-image',
                            'loading' => 'lazy',
                            'alt' => $image_alt,
                            'data-pswp-src' => $image_url,
                            'data-pswp-width' => $image_width,
                            'data-pswp-height' => $image_height
                        )
                    )
                );
            }
            
            $gallery_sections[] = $gallery_section;
        }
    }

    return $gallery_sections;
}

// Get all necessary data
$meta = re_get_work_meta();
$date_display = $meta['start_date'] . ($meta['end_date'] ? ' - ' . $meta['end_date'] : ' - Present');
$gallery_sections = re_get_gallery_sections();
$tech_terms = get_the_terms(get_the_ID(), 'work_tag');
$has_tech_terms = $tech_terms && !is_wp_error($tech_terms);

// Flatten all images for carousel
$all_images = array();
if (!empty($gallery_sections)) {
    foreach ($gallery_sections as $section) {
        foreach ($section['images'] as $image) {
            $all_images[] = $image;
        }
    }
}

?>

<!-- wp:group {"metadata":{"name":"WorkHeader"},"className":"work-header","layout":{"type":"wide"}} -->
<div class="wp-block-group work-header">
    <!-- wp:heading {"level":1,"metadata":{"name":"WorkTitle"}} -->
    <h1 class="wp-block-heading"><?php the_title(); ?></h1>
    <!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"WorkContent"},"className":"work-content","layout":{"type":"wide"}} -->
<div class="wp-block-group work-content">
    <!-- wp:columns {"metadata":{"name":"WorkColumns"},"className":"work-columns"} -->
    <div class="wp-block-columns work-columns">
        <!-- wp:column {"metadata":{"name":"WorkColumn-Gallery"},"className":"work-column-gallery","width":"60%"} -->
        <div class="wp-block-column work-column-gallery" style="flex-basis:60%">
            <!-- wp:group {"metadata":{"name":"WorkGallery"},"className":"work-gallery work-carousel"} -->
            <div class="wp-block-group work-gallery work-carousel">
                <?php if (!empty($all_images)) : ?>
                    <!-- wp:group {"metadata":{"name":"WorkCarousel"},"className":"work-carousel-container swiper"} -->
                    <div class="wp-block-group work-carousel-container swiper">
                        <!-- Swiper wrapper -->
                        <div class="swiper-wrapper">
                            <?php foreach ($all_images as $index => $image) : 
                                $image_url = $image['full_url'];
                                $image_width = isset($image['meta']['width']) ? $image['meta']['width'] : 1200;
                                $image_height = isset($image['meta']['height']) ? $image['meta']['height'] : 800;
                                $image_alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true) ?: get_the_title($image['id']);
                            ?>
                                <div class="swiper-slide" data-slide="<?php echo esc_attr($index); ?>">
                                    <a 
                                        href="<?php echo esc_url($image_url); ?>" 
                                        data-pswp-width="<?php echo esc_attr($image_width); ?>" 
                                        data-pswp-height="<?php echo esc_attr($image_height); ?>"
                                        data-pswp-alt="<?php echo esc_attr($image_alt); ?>"
                                        class="lightbox-link"
                                    >
                                        <?php echo $image['html']; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($all_images) > 1) : ?>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                            
                            <!-- Add Navigation -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        <?php endif; ?>
                    </div>
                    <!-- /wp:group -->
                <?php endif; ?>
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"metadata":{"name":"WorkColumn-Details"},"className":"work-column-details","width":"40%"} -->
        <div class="wp-block-column work-column-details" style="flex-basis:40%">
            <!-- wp:group {"metadata":{"name":"WorkMeta"},"className":"work-meta"} -->
            <div class="wp-block-group work-meta">
                <p class="work-employer"><?php echo esc_html($meta['employer']); ?></p>
                <p class="work-project"><?php echo esc_html($meta['project']); ?></p>
                <p class="work-role"><?php echo esc_html($meta['role']); ?></p>
                <p class="work-location"><?php echo esc_html($meta['location']); ?></p>
                <p class="work-dates"><?php echo esc_html($date_display); ?></p>
            </div>
            <!-- /wp:group -->

            <!-- wp:heading {"metadata":{"name":"WorkOverview-Title"},"level":2} -->
            <h2>Project Overview</h2>
            <!-- /wp:heading -->

            <!-- wp:post-content {"metadata":{"name":"WorkOverview-Content"}} /-->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:group {"metadata":{"name":"WorkTechnologies"},"className":"work-technologies"} -->
    <div class="wp-block-group work-technologies">
        <!-- wp:heading {"metadata":{"name":"WorkTechnologies-Title"},"level":2} -->
        <h2>Technologies Used</h2>
        <!-- /wp:heading -->

        <?php if ($has_tech_terms) : ?>
            <!-- wp:group {"metadata":{"name":"WorkTechnologies-Tags"},"className":"work-tags"} -->
            <div class="wp-block-group work-tags">
                <?php foreach ($tech_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                <?php endforeach; ?>
            </div>
            <!-- /wp:group -->
        <?php endif; ?>
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"metadata":{"name":"WorkCTA"},"className":"work-cta"} -->
    <div class="wp-block-group work-cta">
        <!-- wp:heading {"metadata":{"name":"WorkCTA-Title"},"level":2} -->
        <h2>Want to Build Something Similar?</h2>
        <!-- /wp:heading -->

        <!-- wp:buttons {"metadata":{"name":"WorkCTA-Buttons"},"layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-buttons">
            <!-- wp:button {"metadata":{"name":"WorkCTA-ContactButton"},"className":"is-style-fill"} -->
            <div class="wp-block-button is-style-fill">
                <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(home_url('/contact')); ?>">Get in Touch</a>
            </div>
            <!-- /wp:button -->

            <!-- wp:button {"metadata":{"name":"WorkCTA-PortfolioButton"},"className":"is-style-outline"} -->
            <div class="wp-block-button is-style-outline">
                <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(home_url('/work')); ?>">View More Projects</a>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group --> 
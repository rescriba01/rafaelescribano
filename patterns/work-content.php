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

/**
 * Format date to mm/yyyy
 * 
 * @param string $date Date in Y-m format
 * @return string Formatted date in mm/yyyy format
 */
function re_format_work_date($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date . '-01'); // Add day to make a valid date
    return date('m/Y', $timestamp);
}

// Get all necessary data
$meta = re_get_work_meta();
$start_date = re_format_work_date($meta['start_date']);
$end_date = $meta['end_date'] ? re_format_work_date($meta['end_date']) : 'Present';
$date_display = $start_date . ' - ' . $end_date;
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
                        <!-- wp:group {"metadata":{"name":"WorkCarouselWrapper"},"className":"swiper-wrapper"} -->
                        <div class="wp-block-group swiper-wrapper">
                            <?php foreach ($all_images as $index => $image) : 
                                $image_url = $image['full_url'];
                                $image_width = isset($image['meta']['width']) ? $image['meta']['width'] : 1200;
                                $image_height = isset($image['meta']['height']) ? $image['meta']['height'] : 800;
                                $image_alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true) ?: get_the_title($image['id']);
                                $slide_class = 'slide-' . esc_attr($index);
                            ?>
                                <!-- wp:group {"metadata":{"name":"WorkCarouselSlide"},"className":"swiper-slide","layout":{"type":"constrained"}} -->
                                <div class="wp-block-group swiper-slide <?php echo $slide_class; ?>">
                                    <!-- wp:html -->
                                    <a 
                                        href="<?php echo esc_url($image_url); ?>" 
                                        data-pswp-width="<?php echo esc_attr($image_width); ?>" 
                                        data-pswp-height="<?php echo esc_attr($image_height); ?>"
                                        data-pswp-alt="<?php echo esc_attr($image_alt); ?>"
                                        class="lightbox-link"
                                    >
                                        <?php echo $image['html']; ?>
                                    </a>
                                    <!-- /wp:html -->
                                </div>
                                <!-- /wp:group -->
                            <?php endforeach; ?>
                        </div>
                        <!-- /wp:group -->
                        
                        <?php if (count($all_images) > 1) : ?>
                            <!-- wp:group {"metadata":{"name":"WorkCarouselControls"},"className":"carousel-controls"} -->
                            <div class="wp-block-group carousel-controls">
                                <!-- wp:html -->
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <!-- /wp:html -->
                            </div>
                            <!-- /wp:group -->
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
                <!-- wp:paragraph {"className":"work-meta__employer"} -->
                <p class="work-meta__employer">Company: <span class="work-meta__employer-name"><?php echo esc_html($meta['employer']); ?></span></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"className":"work-meta__project"} -->
                <p class="work-meta__project">Project: <span class="work-meta__project-name"><?php echo esc_html($meta['project']); ?></span></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"className":"work-meta__role"} -->
                <p class="work-meta__role">Role: <span class="work-meta__role-name"><?php echo esc_html($meta['role']); ?></span></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"className":"work-meta__dates"} -->
                <p class="work-meta__dates">Dates: <span class="work-meta__dates-range"><?php echo esc_html($date_display); ?></span></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"metadata":{"name":"WorkTechnologies"},"className":"work-technologies"} -->
            <div class="wp-block-group work-technologies">
            <!-- wp:heading {"metadata":{"name":"WorkTechnologies-Title"},"level":2} -->
            <h2>Technologies Used</h2>
            <!-- /wp:heading -->

                <?php if ($has_tech_terms) : 
                    $tech_links = array_map(function($term) {
                        return sprintf(
                            '<!-- wp:paragraph {"className":"tech-tag"} --><p class="tech-tag"><a href="%s">%s</a></p><!-- /wp:paragraph -->',
                            esc_url(get_term_link($term)),
                            esc_html($term->name)
                        );
                    }, $tech_terms);
                ?>
                    <!-- wp:group {"metadata":{"name":"WorkTechnologies-Tags"},"className":"work-tags"} -->
                    <div class="wp-block-group work-tags">
                        <?php echo implode("\n", $tech_links); ?>
                    </div>
                    <!-- /wp:group -->
                <?php endif; ?>
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"metadata":{"name":"WorkOverview"},"className":"work-overview"} -->
            <div class="wp-block-group work-overview">
                <!-- wp:heading {"metadata":{"name":"WorkOverview-Title"},"level":2} -->
                <h2>Project Overview</h2>
                <!-- /wp:heading -->

                <!-- wp:post-content {"metadata":{"name":"WorkOverview-Content"}} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

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
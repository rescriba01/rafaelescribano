<?php
/**
 * Title: Intro with Links
 * Slug: re/intro-with-links
 * Categories: featured
 * Description: A personal introduction section with a heading, description, services, and a list of featured links.
 *
 * @package re
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- wp:group {"tagName":"section","metadata":{"name":"Intro-group","categories":["featured"],"patternName":"re/intro-with-links"},"align":"full","className":"intro-group","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull intro-group">
    <!-- wp:columns {"metadata":{"categories":["featured"],"patternName":"re/intro-with-links","name":"Intro-columns"},"align":"full","className":"intro-with-links-columns"} -->
    <div class="wp-block-columns alignfull intro-with-links-columns">
        <!-- wp:column {"width":"66.66%","className":"introduction"} -->
        <div class="wp-block-column introduction" style="flex-basis:66.66%">
            <!-- wp:heading {"level":1,"className":"is-style-display"} -->
            <h1 class="wp-block-heading is-style-display">Your Name Here</h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Add your professional introduction here. Describe your expertise, experience, and what makes your work unique. This is a great place to make a strong first impression.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"28%","className":"project-links"} -->
        <div class="wp-block-column project-links" style="flex-basis:28%">
            <!-- wp:re/link-list {"title":"Featured Projects","links":[{"text":"Project One","url":"#"},{"text":"Project Two","url":"#"},{"text":"Project Three","url":"#"}]} -->
            <div class="wp-block-re-link-list">
                <h3 class="link-list-title">Featured Projects</h3>
                <ul class="link-list">
                    <li><a href="#">Project One</a></li>
                    <li><a href="#">Project Two</a></li>
                    <li><a href="#">Project Three</a></li>
                </ul>
            </div>
            <!-- /wp:re/link-list -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"services-section","metadata":{"name":"Services Section"},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull services-section">
    <!-- wp:group {"className":"services-grid","metadata":{"name":"Services Grid"},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group services-grid">
        <!-- wp:heading {"level":2,"className":"services-title","align":"center","metadata":{"name":"Services Title"}} -->
        <h2 class="wp-block-heading services-title has-text-align-center">Core Services</h2>
        <!-- /wp:heading -->

        <!-- wp:columns {"className":"services-columns","metadata":{"name":"Services Items"}} -->
        <div class="wp-block-columns services-columns">
            <!-- wp:column {"className":"service-item","metadata":{"name":"WordPress Development"}} -->
            <div class="wp-block-column service-item">
                <?php 
                $wp_icon = re_get_icon( 'web-development', '', 'service-icon' );
                if ( $wp_icon ) {
                    echo wp_image_add_srcset_and_sizes( $wp_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <h3>Custom Web/WordPress Development</h3>
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"service-item","metadata":{"name":"API Integration"}} -->
            <div class="wp-block-column service-item">
                <?php 
                $api_icon = re_get_icon( 'api-vector-icon', '', 'service-icon' );
                if ( $api_icon ) {
                    echo wp_image_add_srcset_and_sizes( $api_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <h3>API Integrations</h3>
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"service-item","metadata":{"name":"Design Systems"}} -->
            <div class="wp-block-column service-item">
                <?php 
                $scale_icon = re_get_icon( 'scalability-vector-icon', '', 'service-icon' );
                if ( $scale_icon ) {
                    echo wp_image_add_srcset_and_sizes( $scale_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <h3>Scalable Design Systems</h3>
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->
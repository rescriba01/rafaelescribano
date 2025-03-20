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

<!-- wp:group {"tagName":"section","metadata":{"name":"IntroWithLinks"},"align":"full","className":"intro-with-links","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull intro-with-links">
    <!-- wp:group {"metadata":{"name":"IntroContent"},"className":"intro-content","layout":{"type":"constrained"}} -->
    <div class="wp-block-group intro-content">
        <!-- wp:heading {"metadata":{"name":"IntroHeading"},"level":1,"className":"intro-heading"} -->
        <h1 class="wp-block-heading intro-heading"><?php echo esc_html($heading); ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"metadata":{"name":"IntroText"},"className":"intro-text"} -->
        <p class="intro-text"><?php echo wp_kses_post($text); ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <?php if (!empty($links)): ?>
    <!-- wp:group {"metadata":{"name":"IntroLinks"},"className":"intro-links","layout":{"type":"constrained"}} -->
    <div class="wp-block-group intro-links">
        <!-- wp:list {"metadata":{"name":"IntroLinksList"},"className":"intro-links__list"} -->
        <ul class="wp-block-list intro-links__list">
            <?php foreach ($links as $link): ?>
            <!-- wp:list-item {"metadata":{"name":"IntroLinkItem"},"className":"intro-links__item"} -->
            <li class="wp-block-list-item intro-links__item">
                <!-- wp:html -->
                <a href="<?php echo esc_url($link['url']); ?>" class="intro-links__link">
                    <?php echo esc_html($link['text']); ?>
                </a>
                <!-- /wp:html -->
            </li>
            <!-- /wp:list-item -->
            <?php endforeach; ?>
        </ul>
        <!-- /wp:list -->
    </div>
    <!-- /wp:group -->
    <?php endif; ?>
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
                <!-- wp:html -->
                <?php 
                $wp_icon = re_get_icon( 'web-development', '', 'service-icon' );
                if ( $wp_icon ) {
                    echo wp_image_add_srcset_and_sizes( $wp_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <!-- /wp:html -->
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Custom Web/WordPress Development</h3>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"service-item","metadata":{"name":"API Integration"}} -->
            <div class="wp-block-column service-item">
                <!-- wp:html -->
                <?php 
                $api_icon = re_get_icon( 'api-vector-icon', '', 'service-icon' );
                if ( $api_icon ) {
                    echo wp_image_add_srcset_and_sizes( $api_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <!-- /wp:html -->
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">API Integrations</h3>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"service-item","metadata":{"name":"Design Systems"}} -->
            <div class="wp-block-column service-item">
                <!-- wp:html -->
                <?php 
                $scale_icon = re_get_icon( 'scalability-vector-icon', '', 'service-icon' );
                if ( $scale_icon ) {
                    echo wp_image_add_srcset_and_sizes( $scale_icon, 'medium', array(
                        'sizes' => '(max-width: 781px) 100vw, 64px'
                    ));
                }
                ?>
                <!-- /wp:html -->
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Scalable Design Systems</h3>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->
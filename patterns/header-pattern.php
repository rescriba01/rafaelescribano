<?php
/**
 * Title: Header Pattern
 * Slug: re/header-pattern
 * Categories: header
 *
 * @package re
 * @since 1.0.0
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
    <!-- wp:group {"align":"wide","className":"header-content","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
    <div class="wp-block-group alignwide header-content">
        <!-- wp:group {"style":{"spacing":{"blockGap":"14px"}},"className":"header-branding","layout":{"type":"flex","alignItems":"center"}} -->
        <div class="wp-block-group header-branding">
            <!-- wp:image {"id":0,"sizeSlug":"full","linkDestination":"custom","className":"header-logo is-style-rounded","width":40,"height":40} -->
            <figure class="wp-block-image size-full header-logo is-style-rounded">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(RE_THEME_URL . 'assets/images/headshot.jpg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="40" height="40"/>
                </a>
            </figure>
            <!-- /wp:image -->

            <!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:navigation {"className":"header-nav","layout":{"type":"flex","justifyContent":"right"}} /-->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
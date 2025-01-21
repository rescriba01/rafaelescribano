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

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
    <!-- wp:group {"align":"wide","className":"site-header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
    <div class="wp-block-group alignwide site-header">
        <!-- wp:group {"style":{"spacing":{"blockGap":"14px"}},"className":"site-header__branding","layout":{"type":"flex","alignItems":"center"}} -->
        <div class="wp-block-group site-header__branding">
            <!-- wp:image {"width":40,"height":40,"scale":"cover","sizeSlug":"full","linkDestination":"custom","className":"site-header__logo is-style-rounded"} -->
            <figure class="wp-block-image size-full is-resized is-style-rounded site-header__logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( RE_THEME_URL . 'assets/images/headshot.jpg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="object-fit:cover;width:40px;height:40px"/>
                </a>
            </figure>
            <!-- /wp:image -->

            <!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:navigation {"className":"site-header__nav","layout":{"type":"flex","justifyContent":"right"}} /-->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
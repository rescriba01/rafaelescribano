<?php
/**
 * Title: Hero
 * Slug: re/hero
 * Categories: re
 */
?>

<!-- wp:columns {"verticalAlignment":"center","metadata":{"name":"Cover"},"className":"mobile\u002d\u002dno-padding-top","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"blockGap":{"left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center mobile--no-padding-top" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:column {"verticalAlignment":"center","width":"300px","className":"mobile-order-1"} -->
<div class="wp-block-column is-vertically-aligned-center mobile-order-1" style="flex-basis:300px"><!-- wp:image {"width":"300px","aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"mobile-align-none","style":{"color":[]}} -->
<figure class="wp-block-image size-full is-resized mobile-align-none"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/headshot.jpg" alt="" style="aspect-ratio:3/4;object-fit:cover;width:300px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"mobile-order-2"} -->
<div class="wp-block-column is-vertically-aligned-center mobile-order-2"><!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"bottom":"0","top":"0"}}},"fontSize":"huge-mobile"} -->
<h1 class="wp-block-heading has-huge-mobile-font-size" style="margin-top:0;margin-bottom:0"><?php echo __('Hi, I’m Rafael!<br>WordPress Developer<br>who simplifies things.', 're'); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mobile-no-br","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40","top":"var:preset|spacing|20"}},"typography":{"lineHeight":"1.5","fontSize":"20px"}}} -->
<p class="mobile-no-br" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--40);font-size:20px;line-height:1.5"><?php echo __('I create <a href="#">apps</a> and <a href="#">websites</a>, read books,<br>and publish a “Coming Soon”<br>newsletter every week.', 're'); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|20"}}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#contacts"><?php echo __('Contacts', 're'); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
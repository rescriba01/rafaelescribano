<?php
/**
 * Title: Contacts
 * Slug: re/contacts
 * Categories: re
 */
?>

<!-- wp:group {"metadata":{"name":"Contacts"},"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"right":"0","left":"0","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo __('Contacts', 're'); ?></h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"20px"}}} -->
<h3 class="wp-block-heading" style="font-size:20px"><?php echo __('Email', 're'); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"18px"}}} -->
<p style="font-size:18px"><a href="mailto:hello@makoa.com"><?php echo __('hello@makoa.com', 're'); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"20px"}}} -->
<h3 class="wp-block-heading" style="font-size:20px"><?php echo __('Phone', 'makoa'); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"18px"}}} -->
<p style="font-size:18px"><a href="mailto:hello@makoa.com"><?php echo __('+1 (111) 222-33-44', 're'); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"20px"}}} -->
<h3 class="wp-block-heading" style="font-size:20px"><?php echo __('Address', 'makoa'); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"18px"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary"} -->
<p class="has-secondary-color has-text-color has-link-color" style="font-size:18px"><?php echo __('60 29th Street San Francisco', 'makoa'); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
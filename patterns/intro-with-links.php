<?php
/**
 * Title: Intro with Links
 * Slug: re/intro-with-links
 * Categories: featured
 * Description: A personal introduction section with a heading, description, and a list of featured links.
 *
 * @package re
 * @since 1.0.0
 */
?>

<!-- wp:group {"tagName":"section","metadata":{"name":"Intro-group","categories":["featured"],"patternName":"re/intro-with-links"},"align":"full","className":"intro-group","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull intro-group">
    <!-- wp:columns {"metadata":{"categories":["featured"],"patternName":"re/intro-with-links","name":"Intro-columns"},"align":"full","className":"intro-with-links-columns"} -->
    <div class="wp-block-columns alignfull intro-with-links-columns">
        <!-- wp:column {"width":"66.66%","metadata":{"name":"Introduction"},"className":"introduction"} -->
        <div class="wp-block-column introduction" style="flex-basis:66.66%">
            <!-- wp:heading {"level":1,"className":"is-style-display"} -->
            <h1 class="wp-block-heading is-style-display">Your Name Here</h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>Add your professional introduction here. Describe your expertise, experience, and what makes your work unique. This is a great place to make a strong first impression.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"28%","metadata":{"name":"Links"},"className":"project-links"} -->
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
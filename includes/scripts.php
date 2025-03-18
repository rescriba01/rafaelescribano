<?php
/**
 * Script Loading
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme scripts
 */
function re_enqueue_scripts() {
    
    // Pattern: Work Card Stack
    wp_enqueue_script(
        're-pattern-work-card-stack',
        RE_THEME_URL . 'assets/js/patterns/work-card-stack.js',
        array(),
        RE_THEME_VERSION,
        true
    );

    // Work template scripts
    if (is_singular('work')) {
        wp_enqueue_style(
            're-work-template',
            RE_THEME_URL . 'assets/css/templates/single-work.css',
            array(),
            RE_THEME_VERSION
        );

        // Swiper CSS
        wp_enqueue_style(
            'swiper',
            RE_THEME_URL . 'assets/vendor/swiper/swiper-bundle.min.css',
            array(),
            '11.2.4'
        );

        // Swiper JS
        wp_enqueue_script(
            'swiper',
            RE_THEME_URL . 'assets/vendor/swiper/swiper-bundle.min.js',
            array(),
            '11.2.4',
            true
        );

        // PhotoSwipe CSS
        wp_enqueue_style(
            'photoswipe',
            RE_THEME_URL . 'assets/vendor/photoswipe/photoswipe.css',
            array(),
            '5.4.4'
        );

        // PhotoSwipe JS
        wp_enqueue_script(
            'photoswipe',
            RE_THEME_URL . 'assets/vendor/photoswipe/photoswipe.umd.min.js',
            array(),
            '5.4.4',
            true
        );

        // PhotoSwipe Lightbox JS
        wp_enqueue_script(
            'photoswipe-lightbox',
            RE_THEME_URL . 'assets/vendor/photoswipe/photoswipe-lightbox.umd.min.js',
            array('photoswipe'),
            '5.4.4',
            true
        );

        // PhotoSwipe Helper
        wp_enqueue_script(
            'photoswipe-helper',
            RE_THEME_URL . 'assets/js/modules/photoswipe-helper.js',
            array('photoswipe', 'photoswipe-lightbox'),
            RE_THEME_VERSION,
            true
        );

        // Work Carousel (Swiper implementation)
        wp_enqueue_script(
            're-work-carousel',
            RE_THEME_URL . 'assets/js/templates/work-carousel.js',
            array('swiper', 'photoswipe-helper'),
            RE_THEME_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 're_enqueue_scripts');

/**
 * Add PhotoSwipe markup to footer
 */
function re_add_photoswipe_markup() {
    if (!is_singular('work')) return;
    ?>
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <!-- Background of PhotoSwipe. 
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">
            <!-- Container that holds slides. 
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <!-- Controls are self-explanatory. Order can be changed. -->
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 're_add_photoswipe_markup'); 
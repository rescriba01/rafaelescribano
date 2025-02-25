/**
 * Work Carousel Functionality
 * 
 * Handles the carousel functionality for the work template using Swiper.
 * Integrates PhotoSwipe for lightbox functionality.
 * 
 * @package re
 * @since 1.0.0
 */

(() => {
    /**
     * Initialize the Swiper carousel
     */
    function initSwiper() {
        // Initialize Swiper
        const carouselContainers = document.querySelectorAll('.work-carousel-container');
        
        if (!carouselContainers.length) return;
        
        carouselContainers.forEach(container => {
            // Check if there are enough slides for loop mode
            const slides = container.querySelectorAll('.swiper-slide');
            const hasMultipleSlides = slides.length > 1;
            
            // If only one slide, don't initialize Swiper at all
            if (!hasMultipleSlides) {
                // Make sure the single slide is visible
                if (slides.length === 1) {
                    slides[0].style.display = 'block';
                    
                    // Hide navigation and pagination elements
                    const navigation = container.querySelectorAll('.swiper-button-next, .swiper-button-prev');
                    const pagination = container.querySelector('.swiper-pagination');
                    
                    navigation.forEach(nav => {
                        nav.style.display = 'none';
                    });
                    
                    if (pagination) {
                        pagination.style.display = 'none';
                    }
                }
                return;
            }
            
            // Initialize Swiper only if there are multiple slides
            const swiper = new Swiper(container, {
                // Enable CSS mode for better performance on desktop
                cssMode: true,
                
                // Optional parameters
                loop: hasMultipleSlides, // Only enable loop if multiple slides
                autoplay: hasMultipleSlides ? {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                } : false,
                
                // Enable keyboard control
                keyboard: {
                    enabled: true,
                    onlyInViewport: true,
                },
                
                // Enable pagination (dots)
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                
                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                    // Disable default icons to use our custom CSS arrows
                    disabledClass: 'swiper-button-disabled',
                    hiddenClass: 'swiper-button-hidden',
                    lockClass: 'swiper-button-lock'
                },
                
                // Accessibility
                a11y: {
                    prevSlideMessage: 'Previous slide',
                    nextSlideMessage: 'Next slide',
                    firstSlideMessage: 'This is the first slide',
                    lastSlideMessage: 'This is the last slide',
                    paginationBulletMessage: 'Go to slide {{index}}',
                },
                
                // Responsive breakpoints
                breakpoints: {
                    // Mobile
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 10
                    },
                    // Tablet
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    // Desktop
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 30
                    }
                }
            });
            
            // Only add event listeners if swiper was initialized
            if (hasMultipleSlides) {
                // Add event listeners for pause on hover if needed
                container.addEventListener('mouseenter', () => {
                    if (swiper.autoplay.running) {
                        swiper.autoplay.stop();
                    }
                });
                
                container.addEventListener('mouseleave', () => {
                    if (!swiper.autoplay.running) {
                        swiper.autoplay.start();
                    }
                });
            }
        });
    }
    
    /**
     * Initialize PhotoSwipe Lightbox
     */
    function initPhotoSwipe() {
        if (typeof PhotoSwipe === 'undefined' || typeof PhotoSwipeLightbox === 'undefined') {
            console.warn('PhotoSwipe not loaded yet');
            return;
        }
        
        // Initialize PhotoSwipe Lightbox
        const lightbox = new PhotoSwipeLightbox({
            gallery: '.work-carousel-container',
            children: 'a.lightbox-link',
            pswpModule: PhotoSwipe,
            showHideAnimationType: 'fade',
            
            // Image attributes
            imageClickAction: 'zoom',
            tapAction: 'zoom',
            
            // UI options
            arrowKeys: true,
            returnFocus: true,
            
            // Accessibility
            arrowPrev: true,
            arrowNext: true,
            counter: true,
            close: true,
            
            // Captions
            captionContent: (slide) => {
                return slide.data.alt || '';
            }
        });
        
        // Initialize the lightbox
        lightbox.init();
    }
    
    /**
     * Initialize everything
     */
    function init() {
        // Always initialize Swiper
        initSwiper();
        
        // Always initialize PhotoSwipe regardless of slide count
        // Use the helper to ensure PhotoSwipe is loaded
        if (typeof window.ensurePhotoSwipeLoaded === 'function') {
            window.ensurePhotoSwipeLoaded(initPhotoSwipe);
        } else {
            // Fallback if helper isn't available
            console.warn('PhotoSwipe helper not found, trying direct initialization');
            setTimeout(initPhotoSwipe, 500);
        }
    }
    
    // Initialize when the DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 
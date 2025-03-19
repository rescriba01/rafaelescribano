import { gsap, fadeIn, staggerFadeIn } from './modules/gsap-config';

(() => {
    // Initialize animations when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Header animations
        const header = document.querySelector('.site-header.wp-block-template-part');
        const navItems = document.querySelectorAll('.wp-block-navigation-item');
        if (header) {
            gsap.from(header, {
                opacity: 0,
                duration: 0.75,
                ease: 'power2.inOut'
            })
        }

        // Navigation items stagger animation
        if (navItems.length) {
            gsap.from(navItems, {
                opacity: 0,
                y: -20,
                duration: 0.5,
                stagger: {
                    amount: 0.3,
                    ease: 'power2.out'
                },
                delay: 0.2 // Slight delay after header animation
            });
        }
    });

    // Clean up animations before page transitions or when page is hidden
    const cleanupAnimations = () => {
        // Kill any remaining GSAP animations if needed
        gsap.killTweensOf('*');
    };

    // Use pagehide for better compatibility with browser caching
    window.addEventListener('pagehide', cleanupAnimations);
})(); 
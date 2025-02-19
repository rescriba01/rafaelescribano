import { gsap, ScrollTrigger, fadeIn, staggerFadeIn } from './modules/gsap-config';

(() => {
    // Initialize animations when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Header animations
        const header = document.querySelector('.site-header');
        if (header) {
            fadeIn(header, 0, false);
        }

        // Navigation items stagger animation
        const navItems = document.querySelectorAll('.wp-block-navigation-item');
        if (navItems.length) {
            staggerFadeIn(navItems, 0.2, false);
        }

        // Initialize ScrollTrigger for content sections
        // Explicitly exclude intro-group and its children
        const contentSections = document.querySelectorAll('.entry-content > *:not(.project):not(.intro-group):not(.introduction):not(.project-links)');
        contentSections.forEach((section) => {
            gsap.from(section, {
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                },
                opacity: 0,
                y: 30,
                duration: 0.8,
                clearProps: 'transform,opacity'
            });
        });

        // Refresh ScrollTrigger on dynamic content changes
        ScrollTrigger.refresh();
    });

    // Clean up on page unload
    window.addEventListener('unload', () => {
        ScrollTrigger.getAll().forEach(trigger => trigger.kill());
    });
})(); 
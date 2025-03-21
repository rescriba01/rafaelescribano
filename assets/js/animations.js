import { gsap, ScrollTrigger, fadeIn, staggerFadeIn } from './modules/gsap-config';

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

(() => {
    // Initialize animations when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Header animations
        const header = document.querySelector('.site-header.wp-block-template-part');
        if (header) {
            gsap.from(header, {
                opacity: 0,
                duration: 0.75,
                ease: 'power2.inOut'
            })
        }

        // Combined navigation and social media icons animation
        const navItems = document.querySelectorAll('.wp-block-navigation-item');
        const socialLinks = document.querySelectorAll('.wp-block-social-links .wp-block-social-link');
        const allElements = [...navItems, ...socialLinks];

        if (allElements.length) {
            // Create main timeline for all elements
            const animationTimeline = gsap.timeline({
                defaults: {
                    duration: 0.2 // Set default duration for all animations
                }
            });

            // Create individual animations for each element
            allElements.forEach((element, index) => {
                // Create a timeline for this specific element
                const elementTimeline = gsap.timeline({
                    delay: index * 0.1 // Each element starts 0.1s after the previous one
                });

                // Animation sequence for each element:
                elementTimeline
                    // 1. Fade in the element
                    .from(element, {
                        opacity: 0,
                        duration: 0.2,
                        ease: "power1.inOut"
                    })
                    // 2. Smooth upward movement
                    .to(element, {
                        y: -25,
                        duration: 0.25,
                        ease: "power3.out"
                    })
                    // 3. Smooth return to original position
                    .to(element, {
                        y: 0,
                        duration: 0.35,
                        ease: "power2.inOut"
                    });

                // Add this element's timeline to the main timeline
                animationTimeline.add(elementTimeline, index * 0.1);
            });
        }

        // Service columns shake animation
        const serviceColumns = document.querySelectorAll('.service-column');
        if (serviceColumns.length) {
            gsap.utils.toArray(serviceColumns).forEach((column) => {
                ScrollTrigger.create({
                    trigger: column,
                    start: "top center", // Changed from "top bottom" to "top center"
                    end: "bottom center",
                    onEnter: () => {
                        gsap.fromTo(
                            column,
                            { x: -10 },
                            {
                                x: 10,
                                duration: 0.2,
                                repeat: 3,
                                yoyo: true,
                                onComplete: () => gsap.to(column, { 
                                    x: 0,
                                    duration: 0.2,
                                    ease: "power2.out"
                                }),
                                ease: "none" // Linear movement for shake effect
                            }
                        );
                    },
                    once: true // Only trigger once per element
                });
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
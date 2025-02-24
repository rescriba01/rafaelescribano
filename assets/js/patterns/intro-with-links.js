import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const initIntroAnimations = () => {
    // Check if intro section exists using WordPress classes
    const introGroup = document.querySelector('.wp-block-group.intro-group');
    const introColumns = document.querySelector('.intro-with-links-columns');
    const introText = document.querySelector('.introduction');
    const introLinks = document.querySelector('.project-links .link-list');
    const linkItems = introLinks ? introLinks.querySelectorAll('li') : null;

    if (!introGroup || !introColumns || !introText || !introLinks || !linkItems) {
        console.warn('Required elements for intro animation not found');
        return; // Exit if required elements don't exist
    }

    // Create main timeline
    const mainTimeline = gsap.timeline({
        paused: true,
        defaults: {
            duration: 1,
            ease: 'power2.out'
        },
        onStart: () => {
            // Add animation-ready class when timeline starts
            introGroup.classList.add('animation-ready');
        }
    });

    // Add animations to timeline
    mainTimeline
        // Animate intro text from left
        .to(introText, {
            opacity: 1,
            x: 0,
            visibility: 'visible',
            duration: 1.2,
            clearProps: 'transform',
        }, 0.2)
        // Animate project links from right with stagger
        .to(linkItems, {
            opacity: 1,
            x: 0,
            visibility: 'visible',
            stagger: 0.2,
            duration: 0.8,
            clearProps: 'transform'
        }, '-=0.8');

    // Start animation after a short delay to ensure DOM is ready
    requestAnimationFrame(() => {
        mainTimeline.play();
    });

    // Handle resize events
    const handleResize = () => {
        if (!mainTimeline.progress() === 1) {
            const mobileOffset = window.innerWidth <= 781 ? 1 : 2;
            const baseSpacing = 20; // Default spacing unit
            
            gsap.set(introText, {
                x: -baseSpacing * mobileOffset
            });
            gsap.set(linkItems, {
                x: baseSpacing * mobileOffset
            });
        }
    };

    window.addEventListener('resize', handleResize);
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initIntroAnimations); 
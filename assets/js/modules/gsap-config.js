import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Default GSAP configuration
gsap.defaults({
    ease: 'power2.out',
    duration: 0.8
});

// Common animation utilities
const fadeIn = (element, delay = 0) => {
    return gsap.from(element, {
        opacity: 0,
        y: 20,
        duration: 0.8,
        delay,
        clearProps: 'all'
    });
};

const staggerFadeIn = (elements, stagger = 0.2) => {
    return gsap.from(elements, {
        opacity: 0,
        y: 20,
        duration: 0.8,
        stagger,
        clearProps: 'all'
    });
};

const createScrollTrigger = (element, animation) => {
    return ScrollTrigger.create({
        trigger: element,
        animation,
        start: 'top 80%',
        toggleActions: 'play none none reverse'
    });
};

export { gsap, ScrollTrigger, fadeIn, staggerFadeIn, createScrollTrigger }; 
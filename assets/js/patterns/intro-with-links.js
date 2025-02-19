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

    // Create and setup canvas only if intro group exists
    let canvas;
    try {
        // Only create canvas if it doesn't already exist
        if (!introGroup.querySelector('.intro-canvas')) {
            canvas = document.createElement('canvas');
            canvas.classList.add('intro-canvas');
            introGroup.insertBefore(canvas, introColumns);
            
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = 300;
            
            // Particle system setup
            setupParticleSystem(canvas, ctx, width, height);
            
            // Handle canvas resize
            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = 300;
            });
        }
    } catch (error) {
        console.warn('Error setting up canvas:', error);
    }

    // Add animations to timeline
    mainTimeline
        // Fade in the canvas first
        .add(() => {
            if (canvas) {
                canvas.classList.add('is-ready');
            }
        })
        // Animate intro text from left
        .to(introText, {
            opacity: 1,
            x: 0,
            visibility: 'visible',
            duration: 1.2,
            clearProps: 'transform'
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

// Particle system setup function
function setupParticleSystem(canvas, ctx, width, height) {
    const particles = [];
    const particleCount = 50;
    
    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 2 + 1;
            this.speedX = Math.random() * 1 - 0.5;
            this.speedY = Math.random() * 1 - 0.5;
            this.opacity = Math.random() * 0.5 + 0.2;
        }
        
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (this.x > width) this.x = 0;
            if (this.x < 0) this.x = width;
            if (this.y > height) this.y = 0;
            if (this.y < 0) this.y = height;
        }
        
        draw() {
            ctx.fillStyle = `rgba(51, 51, 51, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    // Initialize particles
    for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle());
    }
    
    // Animation loop
    function animate() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });
        requestAnimationFrame(animate);
    }
    
    // Start animation
    animate();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initIntroAnimations); 
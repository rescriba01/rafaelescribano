import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const initIntroAnimations = () => {
    // Check if intro section exists using WordPress classes
    const introGroup = document.querySelector('.intro-group');
    const introColumns = document.querySelector('.intro-with-links-columns');
    const introText = document.querySelector('.introduction');
    const introLinks = document.querySelector('.project-links .link-list');
    const linkItems = introLinks ? introLinks.querySelectorAll('li') : null;

    if (!introGroup || !introColumns || !introText || !introLinks || !linkItems) {
        console.warn('Required elements for intro animation not found');
        return; // Exit if required elements don't exist
    }

    // Create and setup canvas only if intro group exists
    try {
        // Only create canvas if it doesn't already exist
        if (!introGroup.querySelector('.intro-canvas')) {
            const canvas = document.createElement('canvas');
            canvas.classList.add('intro-canvas');
            introGroup.insertBefore(canvas, introColumns); // Insert before the columns
            
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
        // Continue with animations even if canvas fails
    }

    // Reset any inline styles that might be present
    gsap.set([introText, linkItems], {
        clearProps: 'all'
    });

    // Set initial states - hide elements before animation
    gsap.set(introText, {
        opacity: 0,
        x: gsap.utils.clamp(-50, -100, window.innerWidth * -0.1),
        visibility: 'visible',
        immediateRender: true
    });

    gsap.set(linkItems, {
        opacity: 0,
        x: gsap.utils.clamp(50, 100, window.innerWidth * 0.1),
        visibility: 'visible',
        immediateRender: true
    });

    // Initial animations for intro content
    const introTimeline = gsap.timeline({
        defaults: {
            duration: 1,
            ease: 'power2.out'
        },
        onStart: () => {
            // Ensure elements are visible when animation starts
            introText.style.visibility = 'visible';
            linkItems.forEach(li => {
                li.style.visibility = 'visible';
            });
        }
    });

    // Animate intro text from left
    introTimeline.to(introText, {
        opacity: 1,
        x: 0,
        duration: 1.2,
        clearProps: 'transform'
    });

    // Animate project links from right with stagger
    introTimeline.to(linkItems, {
        opacity: 1,
        x: 0,
        stagger: 0.2,
        duration: 0.8,
        clearProps: 'transform'
    }, '-=0.8');

    // Handle resize events
    const handleResize = () => {
        if (!introTimeline.progress() === 1) {
            gsap.set(introText, {
                x: gsap.utils.clamp(-50, -100, window.innerWidth * -0.1)
            });
            gsap.set(linkItems, {
                x: gsap.utils.clamp(50, 100, window.innerWidth * 0.1)
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
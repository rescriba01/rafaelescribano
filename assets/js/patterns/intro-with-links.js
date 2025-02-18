import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const initIntroAnimations = () => {
    // Set initial states
    gsap.set(['.introduction', '.project-links .link-list li'], {
        autoAlpha: 0,
    });
    
    gsap.set('.introduction', {
        x: -50
    });
    
    gsap.set('.project-links .link-list li', {
        x: 50
    });

    // Initial animations for intro content
    const introTimeline = gsap.timeline({
        defaults: {
            duration: 1,
            ease: 'power3.out'
        }
    });

    // Animate intro text from left
    introTimeline.to('.introduction', {
        x: 0,
        opacity: 1,
        duration: 1.2
    });

    // Animate project links from right with stagger
    introTimeline.to('.project-links .link-list li', {
        x: 0,
        opacity: 1,
        stagger: 0.2,
        duration: 0.8
    }, '-=0.8'); // Overlap with previous animation

    // Interactive background animation
    const canvas = document.createElement('canvas');
    canvas.classList.add('intro-canvas');
    document.querySelector('.intro-group').appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = 300; // Adjust height as needed
    
    // Particle system
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
    
    // Handle resize
    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = 300;
    });
    
    // Start animation
    animate();
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initIntroAnimations); 
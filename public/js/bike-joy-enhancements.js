document.addEventListener('DOMContentLoaded', function() {
    // Initialize dark mode toggle sound effects
    const setupSoundEffects = () => {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        const darkModeSound = document.getElementById('dark-mode-sound');
        const lightModeSound = document.getElementById('light-mode-sound');
        
        if (darkModeToggle && darkModeSound && lightModeSound) {
            // Pre-load the sounds
            darkModeSound.load();
            lightModeSound.load();
            
            // Create a function to handle the toggle with sound
            function handleToggleWithSound() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    lightModeSound.play().catch(err => console.log('Sound play prevented by browser'));
                } else {
                    darkModeSound.play().catch(err => console.log('Sound play prevented by browser'));
                }
            }
            
            // Listen for dark mode changes
            darkModeToggle.addEventListener('click', handleToggleWithSound);
        }
    };
    
    // Try to setup sound effects
    try {
        setupSoundEffects();
    } catch (e) {
        console.log('Sound effects setup failed:', e);
    }
    // Particle background animation for home page
    const setupParticles = () => {
        const canvas = document.getElementById('particles-canvas');
        if (\!canvas) return;

        const ctx = canvas.getContext('2d');
        const isDarkMode = document.documentElement.classList.contains('dark');

        // Set canvas dimensions
        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Particle configuration
        const particles = [];
        const particleCount = 50;
        const particleBaseSize = 2;
        const particleVariation = 1;
        const baseSpeed = 0.3;
        const lineDistance = 150;
        
        // Color configuration based on theme
        const getParticleColor = (opacity) => {
            return isDarkMode 
                ? `rgba(14, 165, 233, ${opacity})` // Primary blue in dark mode
                : `rgba(20, 184, 166, ${opacity})`; // Secondary teal in light mode
        };

        // Create particles
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: particleBaseSize + Math.random() * particleVariation,
                speedX: (Math.random() - 0.5) * baseSpeed,
                speedY: (Math.random() - 0.5) * baseSpeed,
                opacity: 0.1 + Math.random() * 0.4
            });
        }

        // Animation function
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Update and draw particles
            particles.forEach((particle, index) => {
                // Update position
                particle.x += particle.speedX;
                particle.y += particle.speedY;
                
                // Bounce off edges
                if (particle.x < 0 || particle.x > canvas.width) particle.speedX *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.speedY *= -1;
                
                // Draw particle
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = getParticleColor(particle.opacity);
                ctx.fill();
                
                // Draw connections
                particles.forEach((otherParticle, otherIndex) => {
                    if (index === otherIndex) return;
                    
                    const dx = particle.x - otherParticle.x;
                    const dy = particle.y - otherParticle.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < lineDistance) {
                        const opacity = (1 - distance / lineDistance) * 0.2;
                        ctx.beginPath();
                        ctx.moveTo(particle.x, particle.y);
                        ctx.lineTo(otherParticle.x, otherParticle.y);
                        ctx.strokeStyle = getParticleColor(opacity);
                        ctx.stroke();
                    }
                });
            });
            
            requestAnimationFrame(animate);
        }
        
        animate();
    };

    // Initialize features
    setupParticles();

    // Listen for dark mode changes
    const darkModeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                setupParticles(); // Reinitialize particles when theme changes
            }
        });
    });
    
    darkModeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });

    // Fancy hover effects for project cards
    const projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const angleX = (y - centerY) / 15;
            const angleY = (centerX - x) / 15;
            
            card.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) scale3d(1.02, 1.02, 1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
        });
    });
    
    // Typewriter effect for hero section text
    const typewriterElements = document.querySelectorAll('.typewriter-text');
    typewriterElements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        };
        
        typeWriter();
    });
});

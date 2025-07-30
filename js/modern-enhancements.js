// Modern interactive enhancements for the web app starter
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.feature-card, .testimonial-card, .stat-item, .demo-card').forEach(el => {
        observer.observe(el);
    });

    // Add scroll-triggered animations
    const style = document.createElement('style');
    style.textContent = `
        .feature-card, .testimonial-card, .stat-item, .demo-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        /* Parallax effect for hero section */
        .hero-section {
            transform: translateZ(0);
        }
        
        /* Enhanced button interactions */
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:active::after {
            width: 300px;
            height: 300px;
        }
    `;
    document.head.appendChild(style);

    // Form validation and enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            // Add floating label effect
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
            
            // Real-time validation
            input.addEventListener('input', function() {
                validateField(this);
            });
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            showNotification('Message sent successfully! 🎉', 'success');
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        // Remove existing validation classes
        field.classList.remove('valid', 'invalid');
        
        // Basic validation rules
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } else if (field.required) {
            isValid = value.length > 0;
        }
        
        // Add validation class
        field.classList.add(isValid ? 'valid' : 'invalid');
        
        return isValid;
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '1rem 1.5rem',
            background: type === 'success' ? '#10b981' : '#3b82f6',
            color: 'white',
            borderRadius: '8px',
            boxShadow: '0 10px 25px rgba(0, 0, 0, 0.1)',
            zIndex: '9999',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease'
        });
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Add dynamic background particles to hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        createFloatingParticles(heroSection);
    }

    function createFloatingParticles(container) {
        const particleCount = 20;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            Object.assign(particle.style, {
                position: 'absolute',
                width: Math.random() * 4 + 1 + 'px',
                height: Math.random() * 4 + 1 + 'px',
                background: 'rgba(255, 255, 255, 0.3)',
                borderRadius: '50%',
                left: Math.random() * 100 + '%',
                top: Math.random() * 100 + '%',
                animation: `float ${Math.random() * 3 + 2}s ease-in-out infinite`,
                animationDelay: Math.random() * 2 + 's'
            });
            
            container.appendChild(particle);
        }
    }

    // Enhanced navigation scroll effect
    const nav = document.querySelector('nav');
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            nav.classList.add('nav-scrolled');
        } else {
            nav.classList.remove('nav-scrolled');
        }
        
        lastScrollY = currentScrollY;
    });

    // Typing animation for hero title
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        // Start typing animation after a short delay
        setTimeout(typeWriter, 500);
    }

    console.log('🚀 Modern web app starter loaded with enhanced interactions!');
});

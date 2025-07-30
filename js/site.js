var enable_debug = true;

K = {
  // Add smooth scrolling for anchor links
  initSmoothScrolling: function() {
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
  },
  
  // Add intersection observer for animations
  initScrollAnimations: function() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);
    
    // Observe elements that should animate on scroll
    document.querySelectorAll('.feature-card, .testimonial-card, .stat-item').forEach(el => {
      observer.observe(el);
    });
  },
  
  // Add form enhancement
  enhanceForms: function() {
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitBtn) {
          submitBtn.textContent = 'Sending...';
          submitBtn.disabled = true;
          
          // Re-enable after 3 seconds (for demo purposes)
          setTimeout(() => {
            submitBtn.textContent = 'Send Message';
            submitBtn.disabled = false;
          }, 3000);
        }
      });
    });
  },
  
  // Initialize all features
  init: function() {
    //this.initSmoothScrolling();
    //this.initScrollAnimations();
    this.enhanceForms();
    
    // Add loading complete class
    document.body.classList.add('loaded');
  }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  K.init();
});


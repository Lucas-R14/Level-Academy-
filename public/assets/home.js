// JavaScript for the home page
document.addEventListener('DOMContentLoaded', function() {
  // Initialize scroll animations
  initScrollAnimations();

  // Home page specific functionality
  function setupHomeHeroAnimation() {
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
      heroContent.classList.add('animated');
    }
  }

  // Initialize home page specific functions
  setupHomeHeroAnimation();
}); 
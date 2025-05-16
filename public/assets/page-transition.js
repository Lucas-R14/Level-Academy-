document.addEventListener('DOMContentLoaded', function() {
  // Create the page transition element if it doesn't exist
  if (!document.querySelector('.page-transition')) {
    createPageTransitionElement();
  }
  
  // Create orbiting pixels
  createOrbitPixels();
  
  // Add click event listeners to all internal links
  const links = document.querySelectorAll('a[href^="/"], a[href^="./"], a[href^="../"], a[href^="index"], a[href^="#"]');
  
  links.forEach(link => {
    // Skip links that open in new tabs or have the 'no-transition' class
    if (link.target === '_blank' || link.classList.contains('no-transition') || link.getAttribute('href').startsWith('#')) {
      return;
    }
    
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const targetHref = this.getAttribute('href');
      
      // Show transition
      showPageTransition();
      
      // Navigate to the new page after a delay
      setTimeout(function() {
        window.location.href = targetHref;
      }, 1200); // Slightly longer than the CSS transition to ensure animation completes
    });
  });
  
  // Auto-hide transition on page load
  window.addEventListener('load', function() {
    // Hide the loader if it's showing (on page load)
    hidePageTransition();
  });
});

// Create the transition element and add it to the DOM
function createPageTransitionElement() {
  const transitionHTML = `
    <div class="page-transition">
      <div class="cyber-grid"></div>
      <div class="transition-content">
        <div class="logo">Level Academy</div>
        <div class="loading-container">
          <div class="loading-spinner">
            <div class="spinner-circle"></div>
            <div class="spinner-glitch"></div>
            <div class="pixel-orbit" id="pixel-orbit"></div>
          </div>
          <div class="loading-text">Loading...</div>
        </div>
      </div>
    </div>
  `;
  
  const transitionElement = document.createElement('div');
  transitionElement.innerHTML = transitionHTML;
  document.body.appendChild(transitionElement.firstElementChild);
}

// Create orbiting pixels for the loading spinner
function createOrbitPixels() {
  const orbitContainer = document.getElementById('pixel-orbit');
  if (!orbitContainer) return;
  
  // Clear any existing pixels
  orbitContainer.innerHTML = '';
  
  // Create 12 pixels in orbit
  for (let i = 0; i < 12; i++) {
    const pixel = document.createElement('div');
    pixel.className = 'orbit-pixel';
    
    // Position pixels evenly around the circle
    const angle = (i / 12) * 360;
    pixel.style.transform = `rotate(${angle}deg) translateX(75px) rotate(-${angle}deg)`;
    
    orbitContainer.appendChild(pixel);
  }
}

// Show the page transition animation
function showPageTransition() {
  const transition = document.querySelector('.page-transition');
  if (transition) {
    transition.classList.add('active');
  }
}

// Hide the page transition animation
function hidePageTransition() {
  const transition = document.querySelector('.page-transition');
  if (transition) {
    setTimeout(() => {
      transition.classList.remove('active');
    }, 300); // Small delay to ensure the transition is fully shown before hiding
  }
}

// Make transition visible immediately on page load to show loading effect
document.querySelector('.page-transition')?.classList.add('active'); 
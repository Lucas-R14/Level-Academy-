<?php
// Page transition include file
?>
<!-- Page Transition Element -->
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

<!-- Add the Page Transition CSS if not already included -->
<?php if (!isset($pageTransitionIncluded)): ?>
<link rel="stylesheet" href="../../public/assets/page-transition.css">
<script src="../../public/assets/page-transition.js"></script>
<script>
  // Initialize orbit pixels
  document.addEventListener('DOMContentLoaded', function() {
    const orbitContainer = document.getElementById('pixel-orbit');
    if (orbitContainer && orbitContainer.children.length === 0) {
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
  });
</script>
<?php $pageTransitionIncluded = true; ?>
<?php endif; ?> 
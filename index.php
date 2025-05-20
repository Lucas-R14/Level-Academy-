<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Learning - Redirecting</title>
    <meta http-equiv="refresh" content="0; URL=src/pages/home.php">
    <link rel="stylesheet" href="public/assets/style.css">
    <link rel="stylesheet" href="public/assets/page-transition.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Page Transition Element -->
    <div class="page-transition active">
      <div class="cyber-grid"></div>
      <div class="transition-content">
        <a href="src/pages/home.php" class="logo">Level Academy</a>
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

    <h1>Redirecting to Advanced Learning...</h1>
    <p>If you are not redirected automatically, <a href="src/pages/home.php">click here</a>.</p>
   
    <script src="public/assets/page-transition.js"></script>
    <script>
        // Create orbit pixels
        document.addEventListener('DOMContentLoaded', function() {
            const orbitContainer = document.getElementById('pixel-orbit');
            if (orbitContainer) {
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
        
        // Redirect with JavaScript as fallback
        setTimeout(function() {
            window.location.href = "src/pages/home.php";
        }, 1500);
    </script>
</body>
</html>
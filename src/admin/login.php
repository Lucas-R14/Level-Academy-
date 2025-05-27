<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    session_start();
}

// Include required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';

// Initialize error message
$error = '';

// Check if user is already logged in
if (!empty($_SESSION['user']['id'])) {
    try {
        $pdo = getPDO();
        $user = new User($pdo);
        if ($user->isLoggedIn()) {
            header('Location: index.php');
            exit();
        }
    } catch (Exception $e) {
        // Continue with login if there's an error checking session
        error_log('Session check error: ' . $e->getMessage());
    }
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            // Get database connection
            $pdo = getPDO();
            $user = new User($pdo);
            
            // Attempt to authenticate
            if ($user->authenticate($username, $password)) {
                // Check if user has admin role
                // Regenerate session ID to prevent session fixation
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_regenerate_id(true);
                }
                
                // Set success message
                $_SESSION['success'] = 'Login successful. Welcome back, ' . htmlspecialchars($username) . '!';
                
                // Redirect to dashboard
                header('Location: index.php');
                exit();
            } else {
                // Invalid credentials
                $error = 'Invalid username or password';
            }
        } catch (PDOException $e) {
            error_log('Database error during login: ' . $e->getMessage());
            $error = 'Database error. Please try again later.';
        } catch (Exception $e) {
            error_log('Login error: ' . $e->getMessage());
            $error = 'An error occurred during login. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Level Academy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Level Academy</h1>
            <p>Admin Dashboard Login</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
            
            <div class="forgot-password">
                <a href="#">Forgot your password?</a>
            </div>
        </form>
    </div>
    
    <script>
        // Auto-hide error message after 5 seconds
        setTimeout(function() {
            const errorMessage = document.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 5000);
        
        // Focus username field on page load
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            if (usernameField) {
                usernameField.focus();
            }
        });
    </script>
</body>
</html>

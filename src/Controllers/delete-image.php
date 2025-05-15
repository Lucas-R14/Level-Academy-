<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';

// Initialize User
$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}

// Define allowed upload directories
$ALLOWED_DIRECTORIES = [
    'articles' => 'articles',
    'tournaments' => 'tournaments',
    'default' => ''
];

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized'
    ]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
    exit;
}

try {
    // Get image data from POST request
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['image'])) {
        throw new Exception('Image ID not provided');
    }

    // Get the type parameter from POST data
    $type = isset($data['type']) ? $data['type'] : 'default';
    
    // Get the corresponding directory name
    $directory = isset($ALLOWED_DIRECTORIES[$type]) ? $ALLOWED_DIRECTORIES[$type] : $ALLOWED_DIRECTORIES['default'];
    
    // Directory path
    $upload_dir = "../../public/assets/images/uploads/$directory/";
    
    // Check if directory exists
    if (!is_dir($upload_dir)) {
        throw new Exception('Upload directory not found');
    }

    // Get full path to image
    $image_path = $upload_dir . basename($data['image']);
    
    // Check if file exists
    if (!file_exists($image_path)) {
        throw new Exception('Image not found');
    }

    // Delete the file
    if (!unlink($image_path)) {
        throw new Exception('Failed to delete image');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Image deleted successfully'
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

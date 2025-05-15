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


try {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Not authorized');
    }

    // Handle image upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
        // Get the type parameter from POST data
        $type = isset($_POST['type']) ? $_POST['type'] : 'default';
        
        // Get the corresponding directory name
        $directory = isset($ALLOWED_DIRECTORIES[$type]) ? $ALLOWED_DIRECTORIES[$type] : $ALLOWED_DIRECTORIES['default'];
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $upload_dir = "../../public/assets/images/uploads/$directory/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
        }

        $image_name = uniqid() . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            throw new Exception('Failed to upload image.');
        }

        // Return success response with the relative path
        echo json_encode([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'path' => "/Level-Academy-/public/assets/images/uploads/$directory/$image_name",
            'directory' => $directory
        ]);
        exit;
    } else {
        throw new Exception('No image uploaded');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>

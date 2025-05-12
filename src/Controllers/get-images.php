<?php
session_start();
require_once '../config/config.php';

// Define allowed upload directories
$ALLOWED_DIRECTORIES = [
    'articles' => 'articles',
    'tournaments' => 'tournaments',
    'default' => ''
];

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Get the type parameter from GET data
    $type = isset($_GET['type']) ? $_GET['type'] : 'default';
    
    // Get the corresponding directory name
    $directory = isset($ALLOWED_DIRECTORIES[$type]) ? $ALLOWED_DIRECTORIES[$type] : $ALLOWED_DIRECTORIES['default'];
    
    // Directory path
    $upload_dir = "../../public/assets/images/uploads/$directory/";
    
    // Check if directory exists
    if (!is_dir($upload_dir)) {
        throw new Exception('Upload directory not found');
    }

    // Get all files in directory
    $files = array_diff(scandir($upload_dir), ['.', '..']);
    $images = [];

    // Filter for image files
    foreach ($files as $file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $images[] = [
                'id' => $file,
                'path' => "/Level-Academy-/public/assets/images/uploads/$directory/$file",
                'name' => $file
            ];
        }
    }

    // Sort images by modification time (newest first)
    usort($images, function($a, $b) use ($upload_dir) {
        return filemtime($upload_dir . $b['name']) - filemtime($upload_dir . $a['name']);
    });

    echo json_encode([
        'success' => true,
        'images' => $images,
        'directory' => $directory
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

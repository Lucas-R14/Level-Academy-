<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../Controllers/ArticleController.php';

try {
    $articleController = new ArticleController($pdo);
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $articles = $articleController->getAll();
        echo json_encode(['success' => true, 'data' => $articles]);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

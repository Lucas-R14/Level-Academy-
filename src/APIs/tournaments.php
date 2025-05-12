<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../Controllers/TournamentController.php';

try {
    $tournamentController = new TournamentController($pdo);
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $tournaments = $tournamentController->getAll();
        echo json_encode(['success' => true, 'data' => $tournaments]);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

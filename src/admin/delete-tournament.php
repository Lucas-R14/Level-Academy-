<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/User.php';
require_once '../Controllers/TournamentController.php';

try {
    // Get database connection
    $pdo = getPDO();

    // Initialize User and TournamentController
    $user = new User($pdo);
    $tournamentController = new TournamentController($pdo);

    // Ensure user is logged in and is admin
    if (!$user->isLoggedIn() || !$user->isAdmin()) {
        throw new Exception('You do not have permission to perform this action');
    }

    // Check if tournament ID is provided
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('Invalid tournament ID');
    }

    $tournamentId = (int)$_POST['id'];

    // Delete the tournament using the controller
    $result = $tournamentController->delete($tournamentId);
    
    if ($result > 0) {
        $_SESSION['success'] = 'Tournament deleted successfully';
    } else {
        throw new Exception('Tournament not found or could not be deleted');
    }
} catch (Exception $e) {
    http_response_code(500);
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to tournaments page
header('Location: tournaments.php');
exit();

<?php
/**
 * =====================================================
 * USER LOGOUT - FitZone Gym
 * =====================================================
 * Endpoint: POST /auth/logout.php
 * 
 * Destroys user session and clears cookies
 */

require_once __DIR__ . '/../includes/functions.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user info before destroying session (for logging)
$userEmail = $_SESSION['user_email'] ?? 'unknown';
$userId = $_SESSION['user_id'] ?? 'unknown';

// Clear all session variables
$_SESSION = [];

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Log logout
log_error("User logged out: $userEmail (ID: $userId)");

// Return success response
send_json_response(true, 'Logged out successfully.');
?>

<?php
/**
 * =====================================================
 * AUTHENTICATION CHECK - FitZone Gym
 * =====================================================
 * Include this file at the top of protected endpoints
 * Will return 401 Unauthorized if user is not logged in
 */

require_once __DIR__ . '/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
if (!is_logged_in()) {
    send_json_response(false, 'Unauthorized. Please login first.', [], 401);
}

// User is authenticated, continue with the request
// The user_id is available via $_SESSION['user_id'] or get_current_user_id()
?>

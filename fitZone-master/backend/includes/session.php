<?php
/*
 * ============================================================================
 * BACKEND FILE - Session Management
 * ============================================================================
 * Purpose: Handle user sessions, authentication state, and session utilities
 * Used By: All PHP pages that need authentication
 * ============================================================================
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user ID
 * @return int|null User ID or null if not logged in
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current logged-in user name
 * @return string|null User name or null if not logged in
 */
function getUserName() {
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get current logged-in user email
 * @return string|null User email or null if not logged in
 */
function getUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

/**
 * Login user by creating session
 * @param array $user User data from database
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
}

/**
 * Logout user by destroying session
 */
function logoutUser() {
    session_unset();
    session_destroy();
}

/**
 * Require user to be logged in, redirect to login if not
 * @param string $redirect_url URL to redirect after login
 */
function requireLogin($redirect_url = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Redirect if user is already logged in
 * @param string $redirect_url URL to redirect to
 */
function redirectIfLoggedIn($redirect_url = 'index.php') {
    if (isLoggedIn()) {
        header("Location: $redirect_url");
        exit;
    }
}
?>

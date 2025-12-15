<?php
/**
 * =====================================================
 * HELPER FUNCTIONS - FitZone Gym
 * =====================================================
 * Common utility functions used across the application
 */

/**
 * Sanitize user input
 * Removes HTML tags and trims whitespace
 * 
 * @param string $input Raw input from user
 * @return string Sanitized input
 */
function sanitize_input(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Send JSON response
 * Standardized response format for all API endpoints
 * 
 * @param bool $success Operation success status
 * @param string $message Human-readable message
 * @param array $data Optional data to include
 * @param int $httpCode HTTP status code
 */
function send_json_response(bool $success, string $message, array $data = [], int $httpCode = 200): void {
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Validate email format
 * 
 * @param string $email Email to validate
 * @return bool True if valid email format
 */
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * Minimum 6 characters
 * 
 * @param string $password Password to validate
 * @return bool True if password meets requirements
 */
function validate_password(string $password): bool {
    return strlen($password) >= 6;
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is authenticated
 */
function is_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged in user ID
 * 
 * @return int|null User ID or null if not logged in
 */
function get_current_user_id(): ?int {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get JSON input from request body
 * 
 * @return array Decoded JSON data
 */
function get_json_input(): array {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Hash password securely
 * Uses PASSWORD_DEFAULT which uses bcrypt
 * 
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * 
 * @param string $password Plain text password
 * @param string $hash Stored hash
 * @return bool True if password matches
 */
function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generate_csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if token is valid
 */
function verify_csrf_token(string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Log error to file
 * 
 * @param string $message Error message
 * @param array $context Additional context
 */
function log_error(string $message, array $context = []): void {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if (!empty($context)) {
        $logMessage .= " - Context: " . json_encode($context);
    }
    error_log($logMessage);
}
?>

<?php
/**
 * =====================================================
 * USER REGISTRATION - FitZone Gym
 * =====================================================
 * Endpoint: POST /auth/register.php
 * 
 * Creates new user account with hashed password
 * Stores user data in MySQL database
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Method not allowed. Use POST.', [], 405);
}

// Get input data (supports both JSON and form data)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    $data = get_json_input();
} else {
    $data = $_POST;
}

// Extract and sanitize inputs
$name = isset($data['name']) ? sanitize_input($data['name']) : '';
$email = isset($data['email']) ? sanitize_input($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : ''; // Don't sanitize password

// Validate required fields
if (empty($name)) {
    send_json_response(false, 'Name is required.', [], 400);
}

if (empty($email)) {
    send_json_response(false, 'Email is required.', [], 400);
}

if (empty($password)) {
    send_json_response(false, 'Password is required.', [], 400);
}

// Validate email format
if (!validate_email($email)) {
    send_json_response(false, 'Invalid email format.', [], 400);
}

// Validate password strength
if (!validate_password($password)) {
    send_json_response(false, 'Password must be at least 6 characters.', [], 400);
}

try {
    $db = getDB();
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        send_json_response(false, 'Email is already registered.', [], 409);
    }
    
    // Hash password
    $hashedPassword = hash_password($password);
    
    // Insert new user
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password, created_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $email, $hashedPassword]);
    
    $userId = $db->lastInsertId();
    
    // Log successful registration
    log_error("New user registered: $email (ID: $userId)");
    
    // Return success response
    send_json_response(true, 'Registration successful! Please login.', [
        'user_id' => (int)$userId,
        'email' => $email
    ], 201);
    
} catch (PDOException $e) {
    log_error("Registration error: " . $e->getMessage(), ['email' => $email]);
    send_json_response(false, 'Registration failed. Please try again.', [], 500);
}
?>

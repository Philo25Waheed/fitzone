<?php
/**
 * =====================================================
 * USER LOGIN - FitZone Gym
 * =====================================================
 * Endpoint: POST /auth/login.php
 * 
 * Authenticates user and creates session
 * User data is retrieved from MySQL database
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

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get input data (supports both JSON and form data)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    $data = get_json_input();
} else {
    $data = $_POST;
}

// Extract and sanitize inputs
$email = isset($data['email']) ? sanitize_input($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : ''; // Don't sanitize password

// Validate required fields
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

try {
    $db = getDB();
    
    // Get user by email
    $stmt = $db->prepare("
        SELECT id, name, email, password, weight, height, age, gender, goal, avatar, created_at 
        FROM users 
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Check if user exists
    if (!$user) {
        send_json_response(false, 'Invalid email or password.', [], 401);
    }
    
    // Verify password
    if (!verify_password($password, $user['password'])) {
        send_json_response(false, 'Invalid email or password.', [], 401);
    }
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
    
    // Store user data in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['login_time'] = time();
    
    // Log successful login
    log_error("User logged in: {$user['email']} (ID: {$user['id']})");
    
    // Remove password from response
    unset($user['password']);
    
    // Return success response with user data
    send_json_response(true, 'Login successful!', [
        'user' => [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'weight' => $user['weight'],
            'height' => $user['height'],
            'age' => $user['age'],
            'gender' => $user['gender'],
            'goal' => $user['goal'],
            'avatar' => $user['avatar']
        ]
    ]);
    
} catch (PDOException $e) {
    log_error("Login error: " . $e->getMessage(), ['email' => $email]);
    send_json_response(false, 'Login failed. Please try again.', [], 500);
}
?>

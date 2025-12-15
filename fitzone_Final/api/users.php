<?php
/**
 * =====================================================
 * USER PROFILE API - FitZone Gym
 * =====================================================
 * Endpoint: /api/users.php
 * 
 * GET  - Get current user profile
 * PUT  - Update user profile
 * 
 * Requires authentication
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check authentication
if (!is_logged_in()) {
    send_json_response(false, 'Unauthorized. Please login first.', [], 401);
}

$userId = get_current_user_id();
$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handleGetProfile($db, $userId);
        break;
    case 'PUT':
        handleUpdateProfile($db, $userId);
        break;
    default:
        send_json_response(false, 'Method not allowed.', [], 405);
}

/**
 * Get user profile
 */
function handleGetProfile(PDO $db, int $userId): void {
    try {
        $stmt = $db->prepare("
            SELECT id, name, email, weight, height, age, gender, goal, avatar, created_at, updated_at 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            send_json_response(false, 'User not found.', [], 404);
        }
        
        send_json_response(true, 'Profile retrieved.', ['user' => $user]);
        
    } catch (PDOException $e) {
        log_error("Get profile error: " . $e->getMessage());
        send_json_response(false, 'Error retrieving profile.', [], 500);
    }
}

/**
 * Update user profile
 */
function handleUpdateProfile(PDO $db, int $userId): void {
    $data = get_json_input();
    
    // Build update query dynamically based on provided fields
    $allowedFields = ['name', 'weight', 'height', 'age', 'gender', 'goal'];
    $updates = [];
    $params = [];
    
    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $field === 'name' ? sanitize_input($data[$field]) : $data[$field];
        }
    }
    
    if (empty($updates)) {
        send_json_response(false, 'No valid fields to update.', [], 400);
    }
    
    // Validate specific fields
    if (isset($data['gender']) && !in_array($data['gender'], ['male', 'female'])) {
        send_json_response(false, 'Gender must be "male" or "female".', [], 400);
    }
    
    if (isset($data['goal']) && !in_array($data['goal'], ['maintenance', 'bulking', 'cutting'])) {
        send_json_response(false, 'Goal must be "maintenance", "bulking", or "cutting".', [], 400);
    }
    
    $params[] = $userId;
    
    try {
        $sql = "UPDATE users SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        // Return updated profile
        $stmt = $db->prepare("
            SELECT id, name, email, weight, height, age, gender, goal, avatar, created_at, updated_at 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        send_json_response(true, 'Profile updated successfully.', ['user' => $user]);
        
    } catch (PDOException $e) {
        log_error("Update profile error: " . $e->getMessage());
        send_json_response(false, 'Error updating profile.', [], 500);
    }
}
?>

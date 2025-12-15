<?php
/**
 * =====================================================
 * CONTACT FORM API - FitZone Gym
 * =====================================================
 * Endpoint: /api/contact.php
 * 
 * POST - Submit contact form message
 * 
 * Public endpoint (no auth required)
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

// Get input data
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    $data = get_json_input();
} else {
    $data = $_POST;
}

// Extract and sanitize inputs
$name = isset($data['name']) ? sanitize_input($data['name']) : '';
$email = isset($data['email']) ? sanitize_input($data['email']) : '';
$message = isset($data['message']) ? sanitize_input($data['message']) : '';

// Validate required fields
if (empty($name)) {
    send_json_response(false, 'Name is required.', [], 400);
}

if (empty($email)) {
    send_json_response(false, 'Email is required.', [], 400);
}

if (empty($message)) {
    send_json_response(false, 'Message is required.', [], 400);
}

// Validate email format
if (!validate_email($email)) {
    send_json_response(false, 'Invalid email format.', [], 400);
}

// Validate message length
if (strlen($message) < 10) {
    send_json_response(false, 'Message must be at least 10 characters.', [], 400);
}

if (strlen($message) > 2000) {
    send_json_response(false, 'Message must not exceed 2000 characters.', [], 400);
}

try {
    $db = getDB();
    
    // Insert contact message
    $stmt = $db->prepare("
        INSERT INTO contacts (name, email, message, created_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $email, $message]);
    
    $contactId = $db->lastInsertId();
    
    log_error("New contact message from: $email (ID: $contactId)");
    
    send_json_response(true, 'Message sent successfully! We will contact you soon.', [
        'contact_id' => (int)$contactId
    ], 201);
    
} catch (PDOException $e) {
    log_error("Contact form error: " . $e->getMessage());
    send_json_response(false, 'Error sending message. Please try again.', [], 500);
}
?>

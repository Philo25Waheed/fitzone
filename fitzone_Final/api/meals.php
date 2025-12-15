<?php
/**
 * =====================================================
 * MEALS API - FitZone Gym
 * =====================================================
 * Endpoint: /api/meals.php
 * 
 * GET - Get all healthy meals
 * 
 * Public endpoint (no auth required)
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    send_json_response(false, 'Method not allowed. Use GET.', [], 405);
}

try {
    $db = getDB();
    
    // Get all active meals
    $stmt = $db->prepare("
        SELECT id, name, name_ar, image_url, calories, protein, carbs, fat, description 
        FROM meals 
        WHERE is_active = TRUE 
        ORDER BY id ASC
    ");
    $stmt->execute();
    $meals = $stmt->fetchAll();
    
    send_json_response(true, 'Meals retrieved.', [
        'meals' => $meals,
        'count' => count($meals)
    ]);
    
} catch (PDOException $e) {
    log_error("Meals API error: " . $e->getMessage());
    send_json_response(false, 'Error retrieving meals.', [], 500);
}
?>

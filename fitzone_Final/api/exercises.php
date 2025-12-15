<?php
/**
 * =====================================================
 * EXERCISES API - FitZone Gym
 * =====================================================
 * Endpoint: /api/exercises.php
 * 
 * GET - Get all exercises or filter by category
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
    
    // Check for category filter
    $category = isset($_GET['category']) ? sanitize_input($_GET['category']) : null;
    
    if ($category) {
        $stmt = $db->prepare("
            SELECT id, title, title_ar, video_url, description, description_ar, category, difficulty 
            FROM exercises 
            WHERE is_active = TRUE AND category = ?
            ORDER BY title ASC
        ");
        $stmt->execute([$category]);
    } else {
        $stmt = $db->prepare("
            SELECT id, title, title_ar, video_url, description, description_ar, category, difficulty 
            FROM exercises 
            WHERE is_active = TRUE 
            ORDER BY category ASC, title ASC
        ");
        $stmt->execute();
    }
    
    $exercises = $stmt->fetchAll();
    
    // Get available categories
    $stmtCats = $db->prepare("SELECT DISTINCT category FROM exercises WHERE is_active = TRUE ORDER BY category");
    $stmtCats->execute();
    $categories = $stmtCats->fetchAll(PDO::FETCH_COLUMN);
    
    send_json_response(true, 'Exercises retrieved.', [
        'exercises' => $exercises,
        'categories' => $categories,
        'count' => count($exercises)
    ]);
    
} catch (PDOException $e) {
    log_error("Exercises API error: " . $e->getMessage());
    send_json_response(false, 'Error retrieving exercises.', [], 500);
}
?>

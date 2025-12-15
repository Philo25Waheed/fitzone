<?php
/**
 * =====================================================
 * PROGRESS TRACKING API - FitZone Gym
 * =====================================================
 * Endpoint: /api/progress.php
 * 
 * GET  - Get user's workout progress and streak
 * POST - Add new workout/progress entry
 * 
 * Requires authentication
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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
        handleGetProgress($db, $userId);
        break;
    case 'POST':
        handleAddProgress($db, $userId);
        break;
    default:
        send_json_response(false, 'Method not allowed.', [], 405);
}

/**
 * Get user's progress history and streak
 */
function handleGetProgress(PDO $db, int $userId): void {
    try {
        // Get progress entries
        $stmt = $db->prepare("
            SELECT id, workout_date, weight, note, created_at 
            FROM user_progress 
            WHERE user_id = ? 
            ORDER BY workout_date DESC
            LIMIT 30
        ");
        $stmt->execute([$userId]);
        $entries = $stmt->fetchAll();
        
        // Calculate streak
        $streak = calculateStreak($db, $userId);
        
        // Get last workout date
        $stmtLast = $db->prepare("SELECT MAX(workout_date) as last_workout FROM user_progress WHERE user_id = ?");
        $stmtLast->execute([$userId]);
        $lastWorkout = $stmtLast->fetchColumn();
        
        send_json_response(true, 'Progress retrieved.', [
            'entries' => $entries,
            'streak' => $streak,
            'last_workout' => $lastWorkout,
            'total_workouts' => count($entries)
        ]);
        
    } catch (PDOException $e) {
        log_error("Get progress error: " . $e->getMessage());
        send_json_response(false, 'Error retrieving progress.', [], 500);
    }
}

/**
 * Add new workout/progress entry
 */
function handleAddProgress(PDO $db, int $userId): void {
    $data = get_json_input();
    
    // Get today's date if not provided
    $workoutDate = isset($data['date']) ? sanitize_input($data['date']) : date('Y-m-d');
    $weight = isset($data['weight']) ? floatval($data['weight']) : null;
    $note = isset($data['note']) ? sanitize_input($data['note']) : null;
    
    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $workoutDate)) {
        send_json_response(false, 'Invalid date format. Use YYYY-MM-DD.', [], 400);
    }
    
    try {
        // Check if already logged workout today
        $stmtCheck = $db->prepare("
            SELECT id FROM user_progress 
            WHERE user_id = ? AND workout_date = ?
        ");
        $stmtCheck->execute([$userId, $workoutDate]);
        
        if ($stmtCheck->fetch()) {
            // Update existing entry
            $stmt = $db->prepare("
                UPDATE user_progress 
                SET weight = COALESCE(?, weight), note = COALESCE(?, note)
                WHERE user_id = ? AND workout_date = ?
            ");
            $stmt->execute([$weight, $note, $userId, $workoutDate]);
            $message = 'Workout updated.';
        } else {
            // Insert new entry
            $stmt = $db->prepare("
                INSERT INTO user_progress (user_id, workout_date, weight, note) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $workoutDate, $weight, $note]);
            $message = 'Workout logged!';
        }
        
        // Calculate new streak
        $streak = calculateStreak($db, $userId);
        
        log_error("Workout logged for user $userId on $workoutDate");
        
        send_json_response(true, $message, [
            'streak' => $streak,
            'workout_date' => $workoutDate
        ], 201);
        
    } catch (PDOException $e) {
        log_error("Add progress error: " . $e->getMessage());
        send_json_response(false, 'Error logging workout.', [], 500);
    }
}

/**
 * Calculate consecutive workout days (streak)
 */
function calculateStreak(PDO $db, int $userId): int {
    try {
        $stmt = $db->prepare("
            SELECT DISTINCT workout_date 
            FROM user_progress 
            WHERE user_id = ? 
            ORDER BY workout_date DESC
        ");
        $stmt->execute([$userId]);
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($dates)) {
            return 0;
        }
        
        $streak = 0;
        $today = new DateTime();
        $lastWorkout = new DateTime($dates[0]);
        
        // Check if last workout was today or yesterday
        $diff = $today->diff($lastWorkout)->days;
        if ($diff > 1) {
            return 0; // Streak broken
        }
        
        // Count consecutive days
        $streak = 1;
        for ($i = 1; $i < count($dates); $i++) {
            $current = new DateTime($dates[$i - 1]);
            $prev = new DateTime($dates[$i]);
            $dayDiff = $current->diff($prev)->days;
            
            if ($dayDiff === 1) {
                $streak++;
            } else {
                break;
            }
        }
        
        return $streak;
        
    } catch (Exception $e) {
        return 0;
    }
}
?>

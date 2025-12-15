<?php
/**
 * =====================================================
 * SESSION CHECK - FitZone Gym
 * =====================================================
 * Endpoint: GET /auth/check.php
 * 
 * Checks if user has active session and returns user data
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

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!is_logged_in()) {
    send_json_response(false, 'Not authenticated.', ['authenticated' => false]);
}

try {
    $db = getDB();
    $userId = get_current_user_id();
    
    // Get current user data
    $stmt = $db->prepare("
        SELECT id, name, email, weight, height, age, gender, goal, avatar, created_at 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // User was deleted, clear session
        session_destroy();
        send_json_response(false, 'User not found.', ['authenticated' => false], 401);
    }
    
    // Get user's streak (count of progress entries)
    $stmtStreak = $db->prepare("
        SELECT COUNT(*) as workout_count,
               MAX(workout_date) as last_workout
        FROM user_progress 
        WHERE user_id = ?
    ");
    $stmtStreak->execute([$userId]);
    $streakData = $stmtStreak->fetch();
    
    // Calculate streak
    $streak = 0;
    if ($streakData && $streakData['last_workout']) {
        $lastWorkout = strtotime($streakData['last_workout']);
        $today = strtotime('today');
        $daysDiff = floor(($today - $lastWorkout) / (60 * 60 * 24));
        
        if ($daysDiff <= 1) {
            // Count consecutive days
            $stmtConsecutive = $db->prepare("
                SELECT workout_date 
                FROM user_progress 
                WHERE user_id = ? 
                ORDER BY workout_date DESC
            ");
            $stmtConsecutive->execute([$userId]);
            $dates = $stmtConsecutive->fetchAll(PDO::FETCH_COLUMN);
            
            $streak = 1;
            $currentDate = strtotime($dates[0]);
            
            for ($i = 1; $i < count($dates); $i++) {
                $prevDate = strtotime($dates[$i]);
                $diff = floor(($currentDate - $prevDate) / (60 * 60 * 24));
                
                if ($diff == 1) {
                    $streak++;
                    $currentDate = $prevDate;
                } else {
                    break;
                }
            }
        }
    }
    
    send_json_response(true, 'Authenticated.', [
        'authenticated' => true,
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
        ],
        'streak' => $streak
    ]);
    
} catch (PDOException $e) {
    log_error("Session check error: " . $e->getMessage());
    send_json_response(false, 'Error checking session.', [], 500);
}
?>

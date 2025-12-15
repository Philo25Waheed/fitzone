<?php
/**
 * =====================================================
 * DATABASE CONNECTION - FitZone Gym
 * =====================================================
 * PDO-based MySQL connection with error handling
 * Uses prepared statements for all queries
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'fitzone_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty

// PDO options for secure and efficient connection
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
]);

/**
 * Get database connection
 * Uses singleton pattern to reuse connection
 * 
 * @return PDO Database connection object
 */
function getDB(): PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
        } catch (PDOException $e) {
            // Log error and return generic message
            error_log("Database connection failed: " . $e->getMessage());
            die(json_encode([
                'success' => false,
                'message' => 'Database connection error. Please try again later.'
            ]));
        }
    }
    
    return $pdo;
}

/**
 * Test database connection
 * Used for debugging and setup verification
 * 
 * @return bool True if connection successful
 */
function testConnection(): bool {
    try {
        $pdo = getDB();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

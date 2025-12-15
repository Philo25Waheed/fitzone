<?php
/**
 * =====================================================
 * DATABASE SETUP SCRIPT - FitZone Gym
 * =====================================================
 * Run this script once to create the database and tables
 * 
 * Usage: Navigate to http://localhost/Meister%20Company/fitzone_gym_test/setup.php
 */

// Prevent running multiple times accidentally
$lockFile = __DIR__ . '/.setup_complete';

// Check if already set up
if (file_exists($lockFile) && !isset($_GET['force'])) {
    echo "<html><body style='background:#1a1a2e;color:#eee;font-family:Arial;padding:40px'>";
    echo "<h1 style='color:#ffeb0e'>⚠️ Setup Already Complete</h1>";
    echo "<p>The database has already been set up.</p>";
    echo "<p>To run setup again, add <code>?force=1</code> to the URL.</p>";
    echo "<p><a href='index.html' style='color:#6decfb'>← Go to FitZone</a></p>";
    echo "</body></html>";
    exit;
}

// Database credentials (same as config/db.php)
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password
$dbName = 'fitzone_db';

echo "<html><head><title>FitZone Setup</title></head>";
echo "<body style='background:#1a1a2e;color:#eee;font-family:Arial;padding:40px;max-width:800px;margin:0 auto'>";
echo "<h1 style='color:#ffeb0e'>🏋️ FitZone Database Setup</h1>";

try {
    // Step 1: Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p style='color:#10b981'>✓ Connected to MySQL server</p>";
    
    // Step 2: Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color:#10b981'>✓ Database '$dbName' created/verified</p>";
    
    // Step 3: Select the database
    $pdo->exec("USE `$dbName`");
    echo "<p style='color:#10b981'>✓ Using database '$dbName'</p>";
    
    // Step 4: Create tables
    
    // Users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            weight DECIMAL(5,2) NULL,
            height DECIMAL(5,2) NULL,
            age INT NULL,
            gender ENUM('male', 'female') NULL,
            goal ENUM('maintenance', 'bulking', 'cutting') NULL DEFAULT 'maintenance',
            avatar VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: users</p>";
    
    // Meals table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS meals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            name_ar VARCHAR(100) NULL,
            image_url VARCHAR(255) NOT NULL,
            calories INT NOT NULL,
            protein DECIMAL(5,2) NULL,
            carbs DECIMAL(5,2) NULL,
            fat DECIMAL(5,2) NULL,
            description TEXT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: meals</p>";
    
    // Exercises table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS exercises (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            title_ar VARCHAR(100) NULL,
            video_url VARCHAR(255) NOT NULL,
            description TEXT NULL,
            description_ar TEXT NULL,
            category VARCHAR(50) NULL,
            difficulty ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: exercises</p>";
    
    // Training programs table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS training_programs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(50) NOT NULL UNIQUE,
            description TEXT NULL,
            description_ar TEXT NULL,
            schedule JSON NULL,
            days_per_week INT DEFAULT 5,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: training_programs</p>";
    
    // User progress table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_progress (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            workout_date DATE NOT NULL,
            weight DECIMAL(5,2) NULL,
            note TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_date (user_id, workout_date)
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: user_progress</p>";
    
    // Contacts table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            replied_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_is_read (is_read)
        ) ENGINE=InnoDB
    ");
    echo "<p style='color:#10b981'>✓ Created table: contacts</p>";
    
    // Step 5: Insert default data (only if tables are empty)
    
    // Check if meals already has data
    $mealsCount = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();
    if ($mealsCount == 0) {
        $pdo->exec("
            INSERT INTO meals (name, image_url, calories, protein, carbs, fat) VALUES
            ('Grilled Salmon', 'img/meal_1.jpg', 450, 40, 5, 28),
            ('Chicken Salad', 'img/meal_2.jpg', 320, 35, 15, 12),
            ('Quinoa Bowl', 'img/meal_3.jpg', 380, 14, 55, 10),
            ('Tofu Stir-fry', 'img/meal_4.jpg', 300, 20, 25, 14),
            ('Beef Steak', 'img/meal_5.jpg', 600, 55, 2, 40),
            ('Veggie Wrap', 'img/meal_6.jpg', 280, 10, 40, 8),
            ('Protein Pancakes', 'img/meal_7.jpg', 350, 25, 35, 12),
            ('Turkey Sandwich', 'img/meal_8.jpg', 400, 30, 35, 15),
            ('Greek Yogurt Parfait', 'img/meal_9.jpg', 220, 15, 30, 5),
            ('Avocado Toast', 'img/meal_10.jpg', 270, 8, 25, 16)
        ");
        echo "<p style='color:#10b981'>✓ Inserted 10 meals</p>";
    }
    
    // Check if exercises already has data
    $exercisesCount = $pdo->query("SELECT COUNT(*) FROM exercises")->fetchColumn();
    if ($exercisesCount == 0) {
        $pdo->exec("
            INSERT INTO exercises (title, title_ar, video_url, category, difficulty) VALUES
            ('Push Ups', 'تمرين الضغط', 'https://www.youtube.com/embed/_l3ySVKYVJ8', 'chest', 'beginner'),
            ('Squats', 'السكوات', 'https://www.youtube.com/embed/aclHkVaku9U', 'legs', 'beginner'),
            ('Plank', 'البلانك', 'https://www.youtube.com/embed/pSHjTRCQxIw', 'core', 'beginner'),
            ('Deadlift', 'الديدليفت', 'https://www.youtube.com/embed/op9kVnSso6Q', 'back', 'intermediate'),
            ('Shoulder Press', 'ضغط الكتف', 'https://www.youtube.com/embed/qEwKCR5JCog', 'shoulders', 'beginner'),
            ('Bench Press', 'ضغط الصدر', 'https://www.youtube.com/embed/gRVjAtPip0Y', 'chest', 'intermediate'),
            ('Barbell Row', 'التجديف بالبار', 'https://www.youtube.com/embed/FWJR5Ve8bnQ', 'back', 'intermediate'),
            ('Lat Pulldown', 'سحب علوي', 'https://www.youtube.com/embed/CAwf7n6Luuc', 'back', 'beginner'),
            ('Leg Press', 'ضغط الرجل', 'https://www.youtube.com/embed/IZxyjW7MPJQ', 'legs', 'beginner'),
            ('Barbell Curl', 'كيرل بالبار', 'https://www.youtube.com/embed/kwG2ipFRgfo', 'arms', 'beginner')
        ");
        echo "<p style='color:#10b981'>✓ Inserted 10 exercises</p>";
    }
    
    // Check if training programs already has data
    $programsCount = $pdo->query("SELECT COUNT(*) FROM training_programs")->fetchColumn();
    if ($programsCount == 0) {
        $pdo->exec("
            INSERT INTO training_programs (name, slug, description, days_per_week) VALUES
            ('Bro Split', 'bro', 'Classic bodybuilding split - one muscle group per day', 5),
            ('Full Body', 'full', 'Full body workouts 3-4 times per week', 3),
            ('Push / Pull', 'pushpull', 'Push muscles and pull muscles split', 4),
            ('Body Part Split', 'bodypart', 'Big muscles day and small muscles day', 4),
            ('Powerbuilding', 'power', 'Combination of strength and hypertrophy training', 5)
        ");
        echo "<p style='color:#10b981'>✓ Inserted 5 training programs</p>";
    }
    
    // Create lock file
    file_put_contents($lockFile, date('Y-m-d H:i:s'));
    
    echo "<h2 style='color:#10b981;margin-top:30px'>✅ Setup Complete!</h2>";
    echo "<p>Your FitZone database is ready to use.</p>";
    
    echo "<h3 style='color:#ffeb0e'>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to <a href='index.html' style='color:#6decfb'>FitZone Home</a></li>";
    echo "<li>Click 'Login' → 'Sign Up' to create an account</li>";
    echo "<li>Start tracking your workouts!</li>";
    echo "</ol>";
    
    echo "<div style='margin-top:30px;padding:20px;background:#2a2a4a;border-radius:10px'>";
    echo "<h4 style='color:#ffeb0e'>Database Info</h4>";
    echo "<p>Database: <strong>$dbName</strong></p>";
    echo "<p>Tables created: <strong>6</strong></p>";
    echo "<p>phpMyAdmin: <a href='http://localhost/phpmyadmin/index.php?route=/database/structure&db=$dbName' style='color:#6decfb'>View Database</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<h2 style='color:#ef4444'>❌ Setup Error</h2>";
    echo "<p style='color:#ef4444'>" . htmlspecialchars($e->getMessage()) . "</p>";
    
    echo "<h3 style='color:#ffeb0e'>Troubleshooting:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Verify MySQL credentials (default: root with no password)</li>";
    echo "<li>Check if MySQL is accessible on localhost</li>";
    echo "</ol>";
}

echo "</body></html>";
?>

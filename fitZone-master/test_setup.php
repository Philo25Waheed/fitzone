<?php
/*
 * ============================================================================
 * SETUP & TEST FILE
 * ============================================================================
 * Purpose: Test database connection and help setup the database
 * Instructions: Visit this page in browser to check if everything is working
 * ============================================================================
 */

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>FitZone Database Setup & Test</h1>";
echo "<hr>";

// Step 1: Check if database config file exists
echo "<h2>Step 1: Check Database Config File</h2>";
if (file_exists('backend/config/db.php')) {
    echo "✅ Database config file found!<br>";
    require_once 'backend/config/db.php';
} else {
    echo "❌ Database config file NOT found at backend/config/db.php<br>";
    echo "Please make sure the file exists.<br>";
    exit;
}

// Step 2: Test database connection
echo "<h2>Step 2: Test Database Connection</h2>";
try {
    if (isset($pdo)) {
        echo "✅ Database connection successful!<br>";
        echo "Connected to database: <strong>fitzone_master</strong><br>";
    } else {
        echo "❌ Database connection variable (\$pdo) not set<br>";
        exit;
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit;
}

// Step 3: Check if database exists and has tables
echo "<h2>Step 3: Check Database Tables</h2>";
try {
    // Check for users table
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "✅ 'users' table exists<br>";
        
        // Count users
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "   - Currently has <strong>$count</strong> user(s)<br>";
    } else {
        echo "❌ 'users' table does NOT exist<br>";
        echo "<strong>ACTION REQUIRED:</strong> Import the database.sql file<br>";
        echo "<code>mysql -u root -p fitzone_master < backend/database.sql</code><br>";
    }
    
    // Check for contacts table
    $result = $pdo->query("SHOW TABLES LIKE 'contacts'");
    if ($result->rowCount() > 0) {
        echo "✅ 'contacts' table exists<br>";
        $count = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
        echo "   - Currently has <strong>$count</strong> contact(s)<br>";
    } else {
        echo "❌ 'contacts' table does NOT exist<br>";
    }
    
    // List all tables
    echo "<br><strong>All tables in database:</strong><br>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "   - $table<br>";
        }
    } else {
        echo "   No tables found. <strong>Please import database.sql</strong><br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error checking tables: " . $e->getMessage() . "<br>";
    echo "<br><strong>This usually means the database hasn't been created yet.</strong><br>";
    echo "<br><strong>TO FIX:</strong><br>";
    echo "1. Open phpMyAdmin<br>";
    echo "2. Create a database named: <strong>fitzone_master</strong><br>";
    echo "3. Import the file: <strong>backend/database.sql</strong><br>";
    echo "<br>OR run this command:<br>";
    echo "<code>mysql -u root -p fitzone_master < backend/database.sql</code><br>";
}

// Step 4: Test session
echo "<h2>Step 4: Test Session Support</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['test'] = 'working';
if (isset($_SESSION['test']) && $_SESSION['test'] === 'working') {
    echo "✅ PHP sessions are working!<br>";
    unset($_SESSION['test']);
} else {
    echo "❌ PHP sessions are NOT working<br>";
}

// Step 5: Test includes
echo "<h2>Step 5: Test Required Files</h2>";
$required_files = [
    'backend/includes/functions.php',
    'backend/includes/flash.php',
    'backend/includes/session.php',
    'backend/includes/header.php',
    'backend/includes/footer.php',
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ $file NOT FOUND<br>";
    }
}

// Final summary
echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>If all checks passed, your registration page should work!</p>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Make sure all checks above show ✅</li>";
echo "<li>If database tables are missing, import <strong>backend/database.sql</strong></li>";
echo "<li>Visit <a href='register.php'>register.php</a> to test registration</li>";
echo "<li>After successful registration, check the database: <code>SELECT * FROM users;</code></li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>Once everything is working, you can delete this test_setup.php file for security.</em></p>";
?>

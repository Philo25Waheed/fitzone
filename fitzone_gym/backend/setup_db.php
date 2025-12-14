<?php
// setup_db.php
require 'config/db.php';

try {
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);
    echo "Database and tables created successfully for fitzone_gym.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>

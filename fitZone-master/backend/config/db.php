<?php
/*
 * ============================================================================
 * BACKEND FILE - Database Configuration
 * ============================================================================
 * Purpose: Establish PDO connection to MySQL database
 * Used By: All backend files and frontend PHP pages that need database access
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - This file is BACKEND ONLY - Do not modify unless changing database credentials
 * - No frontend/UI changes needed here
 * ============================================================================
 */

// ===== BACKEND: Database Connection Settings =====
// تعديل هذه الإعدادات فقط في حالة تغيير بيانات السيرفر أو قاعدة البيانات
$host = 'localhost';        // Database host (عنوان السيرفر)
$db_name = 'fitzone_master';        // Database name (اسم قاعدة البيانات)
$username = 'root';          // Database username (اسم المستخدم)
$password = '';              // Database password (كلمة المرور - فارغة في XAMPP)

// ===== BACKEND: PDO Connection Initialization =====
// إنشاء اتصال PDO آمن مع قاعدة البيانات
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
    // BACKEND: Set Error Mode to Exception (عرض الأخطاء كـ exceptions)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // BACKEND: Set Default Fetch Mode to Associative Array (الحصول على البيانات كـ array)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // BACKEND: Handle connection errors (معالجة أخطاء الاتصال)
    die("Connection failed: " . $e->getMessage());
}
?>

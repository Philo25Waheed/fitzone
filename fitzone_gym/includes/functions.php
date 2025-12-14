<?php
/*
 * ============================================================================
 * BACKEND FILE - Helper Functions
 * ============================================================================
 * Purpose: Common utility functions used across the application
 * Contains: Authentication helpers, redirection, sanitization, flash messages
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - This file is BACKEND ONLY - Contains server-side logic
 * - Do not modify these functions unless coordinating with backend team
 * ============================================================================
 */

// ===== BACKEND: Authentication Helper =====
// التحقق من حالة تسجيل الدخول للمستخدم
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ===== BACKEND: Redirection Helper =====
// توجيه المستخدم إلى صفحة معينة
function redirect($url) {
    header("Location: $url");
    exit();
}

// ===== BACKEND: Input Sanitization =====
// تنظيف البيانات المدخلة من المستخدم لحماية من XSS attacks
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// ===== BACKEND: Flash Message System =====
// نظام لعرض رسائل مؤقتة للمستخدم (نجاح، خطأ، تحذير)

// BACKEND FUNCTION: Set flash message
// تخزين رسالة مؤقتة في الـ session
function setFlash($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type  // Types: success, danger, warning, info
    ];
}

// BACKEND FUNCTION: Get and clear flash message
// قراءة الرسالة المؤقتة ثم حذفها من الـ session
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>


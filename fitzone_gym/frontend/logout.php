<?php
/*
 * ============================================================================
 * BACKEND FILE - Logout Script
 * ============================================================================
 * Purpose: Destroy user session and redirect to home page
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - This file is BACKEND ONLY - Handles session termination
 * - No frontend/UI changes needed here
 * ============================================================================
 */

// ===== BACKEND: Load Helper Functions =====
require '../backend/includes/functions.php';

// ===== BACKEND: Destroy User Session =====
// إنهاء جلسة المستخدم وتسجيل الخروج
session_start();           // BACKEND: Start session to access it
session_unset();           // BACKEND: Clear all session variables
session_destroy();         // BACKEND: Destroy the session completely

// ===== BACKEND: Set Success Message =====
session_start();           // BACKEND: Start new session to set flash message
setFlash("Logged out successfully.", "info");

// ===== BACKEND: Redirect to Home =====
redirect("index.php");     // BACKEND: Redirect user to home page
?>


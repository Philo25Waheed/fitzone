<?php
/*
 * ============================================================================
 * MIXED FILE - Header (Backend Session + Frontend Navigation)
 * ============================================================================
 * BACKEND PART: Session management and includes
 * FRONTEND PART: HTML header, navigation menu, and branding
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Lines 1-4 are BACKEND (session & includes) - Do not modify
 * - Lines 5-35 are FRONTEND (HTML/CSS) - Safe to modify for UI changes
 * - Navigation links can be updated for new pages
 * - Styling can be changed via inline styles or CSS classes
 * ============================================================================
 */

// ===== BACKEND: Session & Includes =====
session_start();  // BACKEND: Start PHP session for user authentication
include_once __DIR__ . '/functions.php';  // BACKEND: Load helper functions
?>

<!-- ===== FRONTEND: HTML Document Start ===== -->
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<!-- FRONTEND: Meta tags and document setup -->
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>FitZone - Gym</title>

<!-- FRONTEND: CSS Stylesheet -->
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="site-overlay">

<!-- ===== FRONTEND: Header Navigation ===== -->
<!-- تعديل الـ header والـ navigation هنا آمن لفريق الـ Frontend -->
<header class="header"><div style="margin-left:auto"><div class="logo-badge" style="transform:none;position:relative;right:0;top:0"></div></div>
  
  <!-- FRONTEND: Brand/Logo Section -->
  <div class="brand"><div class="logo"></div><div style="font-weight:700;color:var(--neon)">FitZone</div></div>
  
  <!-- FRONTEND: Main Navigation Menu -->
  <!-- يمكن إضافة أو تعديل الروابط هنا -->
  <nav class="nav">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="services.php">Services</a>
    <a href="nutrition.php">Healthy Meals</a>
    <a href="contact.php">Contact</a>
    <a href="workouts.php">Training</a>
    
    <!-- ===== BACKEND LOGIC: Conditional Navigation Based on Login Status ===== -->
    <!-- الكود التالي يعرض روابط مختلفة حسب حالة تسجيل الدخول -->
    <?php if(isLoggedIn()): ?>
        <!-- FRONTEND: Logged-in user menu -->
        <span class="auth-only">
            <a href="profile.php" class="btn" style="margin-left:5px">Profile</a>
            <a href="logout.php" class="btn">Logout</a>
        </span>
    <?php else: ?>
        <!-- FRONTEND: Guest user menu -->
        <span class="guest-only"><a href="login.php" class="btn">Login</a></span>
    <?php endif; ?>
</nav>
</header>


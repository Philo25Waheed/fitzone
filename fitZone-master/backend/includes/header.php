<?php
/*
 * ============================================================================
 * BACKEND FILE - PHP Header Template
 * ============================================================================
 * Purpose: Common header HTML with navigation for all PHP pages
 * Includes: Session handling, navigation bar, user authentication state
 * ============================================================================
 */

// Include database and session
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo $page_title ?? 'FitZone'; ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="site-overlay">
    <header class="header">
      <div class="brand">
        <div class="logo"></div>
        <div style="font-weight:700;color:var(--neon)">FitZone</div>
      </div>
      <nav class="nav">
        <a href="index.php">Home</a>
        <a href="about.html">About</a>
        <a href="services.html">Services</a>
        <a href="training.html">Training</a>
        <a href="meals.html">Healthy Meals</a>
        <a href="contact.php">Contact</a>
        
        <?php if (isLoggedIn()): ?>
          <!-- Logged in user -->
          <span class="auth-only">
            <a href="profile.php" style="color:var(--neon)"><?php echo htmlspecialchars(getUserName()); ?></a>
            <a href="logout.php" class="btn">Logout</a>
          </span>
        <?php else: ?>
          <!-- Guest user -->
          <span class="guest-only">
            <a class="btn" href="login.php">Login</a>
          </span>
        <?php endif; ?>
      </nav>
    </header>

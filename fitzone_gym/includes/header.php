<?php
session_start();
include_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>FitZone - Gym</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="site-overlay">

<header class="header"><div style="margin-left:auto"><div class="logo-badge" style="transform:none;position:relative;right:0;top:0"></div></div>
  <div class="brand"><div class="logo"></div><div style="font-weight:700;color:var(--neon)">FitZone</div></div>
  <nav class="nav">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="services.php">Services</a>
    <a href="nutrition.php">Healthy Meals</a>
    <a href="contact.php">Contact</a>
    <a href="workouts.php">Training</a>
    
    <?php if(isLoggedIn()): ?>
        <span class="auth-only">
            <a href="profile.php" class="btn" style="margin-left:5px">Profile</a>
            <a href="logout.php" class="btn">Logout</a>
        </span>
    <?php else: ?>
        <span class="guest-only"><a href="login.php" class="btn">Login</a></span>
    <?php endif; ?>
</nav>
</header>

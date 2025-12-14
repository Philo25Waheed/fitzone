<?php
/*
 * ============================================================================
 * MIXED FILE - Home Page (Backend + Frontend)
 * ============================================================================
 * Backend: Session handling and user-specific content
 * Frontend: Neon cyberpunk homepage
 * ============================================================================
 */

$page_title = 'FitZone - Home';
require_once 'backend/includes/header.php';
?>

<section class="hero container" aria-label="hero">
  <div class="hero-left">
    <h1 class="h-title">Shape Your Ideal Body</h1>
    <p class="h-sub">Transform your body with FitZone — the futuristic fitness companion.</p>
    <a href="services.html" class="cta">Get Started</a>
  </div>
  <div class="hero-right" style="flex:1;display:flex;justify-content:center;align-items:center">
    <img src="img/hero_v2.jpeg" alt="hero" style="width:520px;border-radius:12px;box-shadow:0 30px 80px rgba(177,59,255,0.12)">
  </div>
</section>

<div class="container">
  <?php include 'backend/includes/flash.php'; ?>
  
  <?php if (isLoggedIn()): ?>
    <!-- Logged in user view -->
    <div class="panel">
      <h2 style="color:var(--neon)">Welcome back, <?php echo htmlspecialchars(getUserName()); ?>! 👋</h2>
      <p style="margin-top:8px;color:#cfc7dd">Ready to crush your fitness goals today?</p>
      <div style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;">
        <a href="profile.php" class="btn">View Profile</a>
        <a href="services.html" class="btn">Browse Workouts</a>
        <a href="meals.html" class="btn">Meal Plans</a>
      </div>
    </div>
  <?php else: ?>
    <!-- Guest user view -->
    <div class="panel">
      <h2 style="color:var(--neon)">Activity • <span id="streakCount">0</span> أيام حماس</h2>
      <p style="margin-top:8px;color:#cfc7dd">سجل تمارينك واحصل على أيام حماس متتالية.</p>
      <div style="margin-top:12px">
        <p style="color:#cfc7dd;margin-bottom:12px;">
          <a href="register.php" style="color:var(--neon);font-weight:600;">Create an account</a> to track your progress!
        </p>
      </div>
    </div>
  <?php endif; ?>

<?php include 'backend/includes/footer.php'; ?>

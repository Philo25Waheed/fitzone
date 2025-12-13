<?php
// index.php
require 'config/db.php';
include 'includes/header.php';
?>

<section class="hero container" aria-label="hero">
  <div class="hero-left">
    <h1 class="h-title">Shape Your Ideal Body</h1>
    <p class="h-sub">Transform your body with FitZone — the futuristic fitness companion.</p>
    <a href="register.php" class="cta">Get Started</a>
  </div>
  <div class="hero-right" style="flex:1;display:flex;justify-content:center;align-items:center">
    <img src="img/hero_v2.jpeg" alt="hero" style="width:520px;border-radius:12px;box-shadow:0 30px 80px rgba(177,59,255,0.12)">
  </div>
</section>

<div class="container">
    <?php include 'includes/flash.php'; ?>
    <div class="panel">
        <h2 style="color:var(--neon)">Activity • <span id="streakCount">0</span> Days Streak</h2>
        <p style="margin-top:8px;color:#cfc7dd">Log your workouts and build your streak.</p>
        <?php if(isLoggedIn()): ?>
            <div style="margin-top:12px">
                <p>Welcome back, <strong><?php echo $_SESSION['user_name'] ?? 'User'; ?></strong>!</p>
                <a href="profile.php" class="btn">Go to Profile</a>
            </div>
        <?php else: ?>
            <div style="margin-top:12px"><a href="register.php" class="btn">Join Now</a></div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

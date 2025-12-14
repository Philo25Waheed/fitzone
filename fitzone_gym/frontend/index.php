<?php
/*
 * ============================================================================
 * MIXED FILE - Home Page (Backend Auth Check + Frontend Hero & Content)
 * ============================================================================
 * BACKEND PART: Lines 3-4 - Database connection and header includes
 * FRONTEND PART: Lines 7-33 - HTML hero section and activity panel
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend includes: DO NOT MODIFY
 * - Frontend HTML/CSS: SAFE to modify for UI/UX changes
 * ============================================================================
 */

// ===== BACKEND: Database and Includes =====
require '../backend/config/db.php';  // BACKEND: Load database connection
include '../backend/includes/header.php';  // MIXED: Load header
?>

<!-- ===== FRONTEND: Hero Section ===== -->
<!-- يمكن تعديل النصوص والصور والستايل هنا -->
<section class="hero container" aria-label="hero">
  <!-- FRONTEND: Hero Left - Text Content -->
  <div class="hero-left">
    <h1 class="h-title">Shape Your Ideal Body</h1>
    <p class="h-sub">Transform your body with FitZone — the futuristic fitness companion.</p>
    <a href="register.php" class="cta">Get Started</a>
  </div>
  
  <!-- FRONTEND: Hero Right - Image -->
  <div class="hero-right" style="flex:1;display:flex;justify-content:center;align-items:center">
    <img src="img/hero_v2.jpeg" alt="hero" style="width:520px;border-radius:12px;box-shadow:0 30px 80px rgba(177,59,255,0.12)">
  </div>
</section>

<!-- ===== FRONTEND: Activity Panel ===== -->
<div class="container">
    <!-- BACKEND: Display flash messages -->
    <?php include '../backend/includes/flash.php'; ?>
    
    <!-- FRONTEND: Activity Status Panel -->
    <div class="panel">
        <h2 style="color:var(--neon)">Activity • <span id="streakCount">0</span> Days Streak</h2>
        <p style="margin-top:8px;color:#cfc7dd">Log your workouts and build your streak.</p>
        
        <!-- BACKEND LOGIC: Show different content based on login status -->
        <?php if(isLoggedIn()): ?>
            <!-- FRONTEND: Logged-in user message -->
            <div style="margin-top:12px">
                <!-- BACKEND: Display user's name from session -->
                <p>Welcome back, <strong><?php echo $_SESSION['user_name'] ?? 'User'; ?></strong>!</p>
                <a href="profile.php" class="btn">Go to Profile</a>
            </div>
        <?php else: ?>
            <!-- FRONTEND: Guest user message -->
            <div style="margin-top:12px"><a href="register.php" class="btn">Join Now</a></div>
        <?php endif; ?>
    </div>
</div>

<?php include '../backend/includes/footer.php'; ?>

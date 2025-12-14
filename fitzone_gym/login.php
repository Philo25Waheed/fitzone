<?php
/*
 * ============================================================================
 * MIXED FILE - Login Page (Backend Authentication + Frontend Form)
 * ============================================================================
 * BACKEND PART: Lines 3-23 - Form processing and database authentication
 * FRONTEND PART: Lines 26-53 - HTML form and UI layout
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend logic (lines 3-23): DO NOT MODIFY without backend team
 * - Frontend HTML (lines 26-53): SAFE to modify for UI/UX changes
 * - Form fields must keep same 'name' attributes for backend compatibility
 * ============================================================================
 */

// ===== BACKEND: Database and Includes =====
require 'config/db.php';  // BACKEND: Load database connection
include 'includes/header.php';  // MIXED: Load header (contains frontend nav)

// ===== BACKEND: Login Form Processing =====
// معالجة بيانات تسجيل الدخول عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // BACKEND: Sanitize and retrieve form inputs
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    // BACKEND: Query database for user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // BACKEND: Verify password and create session
    if ($user && password_verify($password, $user['password'])) {
        // BACKEND: Password correct - create user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        setFlash("Logged in successfully!", "success");
        echo "<script>window.location.href='profile.php';</script>";
        exit;
    } else {
        // BACKEND: Invalid credentials - show error
        setFlash("Invalid credentials.", "danger");
    }
}
?>

<!-- ===== FRONTEND: Login Page HTML ===== -->
<!-- يمكن تعديل التصميم والستايل هنا بحرية -->
<div class="container">
  <!-- BACKEND: Display flash messages (success/error) -->
  <?php include 'includes/flash.php'; ?>
  
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start">
    <!-- FRONTEND: Login Form Section -->
    <div style="flex:1">
      <h2 style="color:var(--neon)">Login</h2>
      
      <!-- FRONTEND: Login Form -->
      <!-- ملاحظة مهمة: يجب الحفاظ على name attributes للحقول -->
      <form id="loginFormPHP" method="POST" action="" style="margin-top:12px">
        <!-- FRONTEND: Email Input -->
        <label style="color:#ccc; display:block; margin-bottom:5px;">Email</label>
        <input name="email" class="input" placeholder="Email" required><br><br>
        
        <!-- FRONTEND: Password Input -->
        <label style="color:#ccc; display:block; margin-bottom:5px;">Password</label>
        <input name="password" class="input" placeholder="Password" type="password" required><br><br>
        
        <!-- FRONTEND: Submit Button -->
        <button class="btn" type="submit">Login</button>
      </form>
      
      <!-- FRONTEND: Registration Link -->
      <p style="margin-top:12px">Don't have account? <a href="register.php" style="color:var(--neon)">Sign Up</a></p>
    </div>
    
    <!-- FRONTEND: Preview Card Section -->
    <!-- يمكن تعديل هذا الجزء أو إزالته حسب التصميم -->
    <div style="width:360px">
      <h3 style="color:var(--neon)">Quick Preview</h3>
      <div class="card" style="padding:12px;margin-top:8px">
        <img src="img/meal_1.jpg" style="width:100%;border-radius:8px">
        <h4 style="margin-top:8px;color:var(--neon)">Sample Workout</h4>
        <p style="color:#cfc7dd">Example workout with video.</p>
      </div>
    </div>
  </div>
</div>

<!-- BACKEND: Include footer (contains JS) -->
<?php include 'includes/footer.php'; ?>


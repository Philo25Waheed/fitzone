<?php
/*
 * ============================================================================
 * MIXED FILE - Registration Page (Backend User Creation + Frontend Form)
 * ============================================================================
 * BACKEND PART: Lines 3-27 - Form processing, validation, and database insertion
 * FRONTEND PART: Lines 30-59 - HTML registration form and UI layout
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend logic (lines 3-27): DO NOT MODIFY without backend team
 * - Frontend HTML (lines 30-59): SAFE to modify for UI/UX changes
 * - Form fields must keep same 'name' attributes for backend compatibility
 * ============================================================================
 */

// ===== BACKEND: Database and Includes =====
require '../backend/config/db.php';  // BACKEND: Load database connection
include '../backend/includes/header.php';  // MIXED: Load header (contains frontend nav)

// ===== BACKEND: Registration Form Processing =====
// معالجة بيانات التسجيل عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // BACKEND: Sanitize and retrieve form inputs
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    // BACKEND: Check if email already exists in database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // BACKEND: Email already registered - show error
        setFlash("Email already registered.", "danger");
    } else {
        // BACKEND: Email is unique - proceed with registration
        
        // BACKEND: Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // BACKEND: Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$name, $email, $hashed_password])) {
             // BACKEND: Registration successful - redirect to login
             setFlash("Registered successfully! Please login.", "success");
             echo "<script>window.location.href='login.php';</script>";
             exit;
        } else {
             // BACKEND: Database insertion failed
             setFlash("Registration failed.", "danger");
        }
    }
}
?>

<!-- ===== FRONTEND: Registration Page HTML ===== -->
<!-- يمكن تعديل التصميم والستايل هنا بحرية -->
<div class="container">
  <!-- BACKEND: Display flash messages (success/error) -->
  <?php include '../backend/includes/flash.php'; ?>
  
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start">
    <!-- FRONTEND: Registration Form Section -->
    <div style="flex:1">
      <h2 style="color:var(--neon)">Register</h2>
      
      <!-- FRONTEND: Registration Form -->
      <!-- ملاحظة مهمة: يجب الحفاظ على name attributes للحقول -->
      <form id="registerFormPHP" method="POST" action="" style="margin-top:12px">
        <!-- FRONTEND: Name Input -->
        <label style="color:#ccc; display:block; margin-bottom:5px;">Name</label>
        <input name="name" class="input" placeholder="Full Name" required><br><br>

        <!-- FRONTEND: Email Input -->
        <label style="color:#ccc; display:block; margin-bottom:5px;">Email</label>
        <input name="email" class="input" placeholder="Email" type="email" required><br><br>
        
        <!-- FRONTEND: Password Input -->
        <label style="color:#ccc; display:block; margin-bottom:5px;">Password</label>
        <input name="password" class="input" placeholder="Password" type="password" required><br><br>
        
        <!-- FRONTEND: Submit Button -->
        <button class="btn" type="submit">Sign Up</button>
      </form>
      
      <!-- FRONTEND: Login Link -->
      <p style="margin-top:12px">Already have an account? <a href="login.php" style="color:var(--neon)">Login</a></p>
    </div>
    
    <!-- FRONTEND: Preview Card Section -->
    <!-- يمكن تعديل هذا الجزء أو إزالته حسب التصميم -->
    <div style="width:360px">
      <h3 style="color:var(--neon)">Join the Community</h3>
      <div class="card" style="padding:12px;margin-top:8px">
        <img src="img/hero_v2.jpeg" style="width:100%;border-radius:8px">
        <h4 style="margin-top:8px;color:var(--neon)">Track Your Progress</h4>
        <p style="color:#cfc7dd">Get access to exclusive workouts and meal plans.</p>
      </div>
    </div>
  </div>
</div>

<!-- BACKEND: Include footer (contains JS) -->
<?php include '../backend/includes/footer.php'; ?>


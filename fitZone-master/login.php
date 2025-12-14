<?php
/*
 * ============================================================================
 * MIXED FILE - Login Page (Backend + Frontend)
 * ============================================================================
 * Backend: User authentication and session creation
 * Frontend: Neon cyberpunk login form
 * ============================================================================
 */

$page_title = 'Login - FitZone';
require_once 'backend/includes/header.php';

// Redirect if already logged in
redirectIfLoggedIn('index.php');

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Query database for user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Verify password and create session
    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        loginUser($user);
        setFlash("Welcome back, " . htmlspecialchars($user['name']) . "!", "success");
        header("Location: index.php");
        exit;
    } else {
        // Invalid credentials
        setFlash("Invalid email or password.", "danger");
    }
}
?>

<div class="container">
  <?php include 'backend/includes/flash.php'; ?>
  
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;">
    <div style="flex:1;min-width:300px;">
      <h2 style="color:var(--neon)">Login</h2>
      <p style="color:#cfc7dd;margin-top:8px;">Welcome back to FitZone!</p>
      
      <form method="POST" action="" style="margin-top:20px">
        <input name="email" class="input" placeholder="Email Address" type="email" required>
        <br><br>
        
        <input name="password" class="input" placeholder="Password" type="password" required>
        <br><br>
        
        <button class="btn" type="submit" style="width:100%;">Login</button>
      </form>
      
      <p style="margin-top:16px;text-align:center;color:#cfc7dd;">
        Don't have an account? 
        <a href="register.php" style="color:var(--neon);font-weight:600;">Sign Up</a>
      </p>
    </div>
    
    <div style="width:360px;min-width:300px;">
      <h3 style="color:var(--neon)">Quick Preview</h3>
      <div class="card" style="padding:12px;margin-top:8px;">
        <img src="img/meal_1.jpg" style="width:100%;border-radius:8px" alt="Sample workout">
        <h4 style="margin-top:8px;color:var(--neon)">Sample Workout</h4>
        <p style="color:#cfc7dd">Example workout with video demonstration.</p>
      </div>
    </div>
  </div>
</div>

<?php include 'backend/includes/footer.php'; ?>

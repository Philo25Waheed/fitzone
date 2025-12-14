<?php
/*
 * ============================================================================
 * MIXED FILE - Registration Page (Backend + Frontend)
 * ============================================================================
 * Backend: User registration processing and database insertion
 * Frontend: Neon cyberpunk registration form
 * ============================================================================
 */

$page_title = 'Register - FitZone';
require_once 'backend/includes/header.php';

// Redirect if already logged in
redirectIfLoggedIn('index.php');

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $weight = !empty($_POST['weight']) ? floatval($_POST['weight']) : null;
    $height = !empty($_POST['height']) ? floatval($_POST['height']) : null;
    $goal = !empty($_POST['goal']) ? sanitize($_POST['goal']) : null;
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        setFlash("Passwords do not match!", "danger");
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            setFlash("Email already registered. Please login.", "danger");
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, weight, height, goal) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$name, $email, $hashed_password, $weight, $height, $goal])) {
                setFlash("Account created successfully! Please login.", "success");
                header("Location: login.php");
                exit;
            } else {
                setFlash("Registration failed. Please try again.", "danger");
            }
        }
    }
}
?>

<div class="container">
  <?php include 'backend/includes/flash.php'; ?>
  
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;">
    <div style="flex:1;min-width:300px;">
      <h2 style="color:var(--neon)">Create Your Account</h2>
      <p style="color:#cfc7dd;margin-top:8px;">Join FitZone and start your fitness journey today!</p>
      
      <form method="POST" action="" style="margin-top:20px">
        <input name="name" class="input" placeholder="Full Name" required>
        <br><br>
        
        <input name="email" class="input" placeholder="Email Address" type="email" required>
        <br><br>
        
        <input name="password" class="input" placeholder="Password (min 6 characters)" type="password" minlength="6" required>
        <br><br>
        
        <input name="confirmPassword" class="input" placeholder="Confirm Password" type="password" minlength="6" required>
        <br><br>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <input name="weight" class="input" placeholder="Weight (kg)" type="number" step="0.1">
          <input name="height" class="input" placeholder="Height (cm)" type="number" step="0.1">
        </div>
        <br>
        
        <select name="goal" class="input" style="width:100%;">
          <option value="">Select Your Goal</option>
          <option value="lose_weight">Lose Weight</option>
          <option value="build_muscle">Build Muscle</option>
          <option value="stay_fit">Stay Fit</option>
          <option value="increase_strength">Increase Strength</option>
        </select>
        <br><br>
        
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
          <input type="checkbox" required style="width:auto;">
          <label style="color:#cfc7dd;font-size:14px;">I agree to the Terms & Conditions</label>
        </div>
        
        <button class="btn" type="submit" style="width:100%;">Create Account</button>
      </form>
      
      <p style="margin-top:16px;text-align:center;color:#cfc7dd;">
        Already have an account? 
        <a href="login.php" style="color:var(--neon);font-weight:600;">Login here</a>
      </p>
    </div>
    
    <div style="width:360px;min-width:300px;">
      <h3 style="color:var(--neon)">Why Join FitZone?</h3>
      
      <div class="card" style="padding:16px;margin-top:12px;background:linear-gradient(135deg, rgba(177,59,255,0.1), rgba(255,62,229,0.05));">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
          <div style="width:48px;height:48px;background:var(--neon);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#000;">💪</div>
          <div>
            <h4 style="margin:0;color:var(--neon);font-size:16px;">Expert Training Plans</h4>
            <p style="margin:4px 0 0 0;color:#cfc7dd;font-size:14px;">Customized workouts</p>
          </div>
        </div>
      </div>
      
      <div class="card" style="padding:16px;margin-top:12px;background:linear-gradient(135deg, rgba(255,62,229,0.1), rgba(177,59,255,0.05));">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
          <div style="width:48px;height:48px;background:var(--neon);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#000;">🥗</div>
          <div>
            <h4 style="margin:0;color:var(--neon);font-size:16px;">Nutrition Guidance</h4>
            <p style="margin:4px 0 0 0;color:#cfc7dd;font-size:14px;">Healthy meal plans</p>
          </div>
        </div>
      </div>
      
      <div class="card" style="padding:16px;margin-top:12px;background:linear-gradient(135deg, rgba(177,59,255,0.1), rgba(255,62,229,0.05));">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
          <div style="width:48px;height:48px;background:var(--neon);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#000;">📊</div>
          <div>
            <h4 style="margin:0;color:var(--neon);font-size:16px;">Progress Tracking</h4>
            <p style="margin:4px 0 0 0;color:#cfc7dd;font-size:14px;">Monitor your journey</p>
          </div>
        </div>
      </div>
      
      <div class="card" style="padding:16px;margin-top:12px;background:linear-gradient(135deg, rgba(255,62,229,0.1), rgba(177,59,255,0.05));">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
          <div style="width:48px;height:48px;background:var(--neon);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#000;">🏆</div>
          <div>
            <h4 style="margin:0;color:var(--neon);font-size:16px;">Achievement System</h4>
            <p style="margin:4px 0 0 0;color:#cfc7dd;font-size:14px;">Stay motivated daily</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'backend/includes/footer.php'; ?>

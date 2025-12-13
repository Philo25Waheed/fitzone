<?php
// register.php
require 'config/db.php';
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        setFlash("Email already registered.", "danger");
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password])) {
             setFlash("Registered successfully! Please login.", "success");
             echo "<script>window.location.href='login.php';</script>";
             exit;
        } else {
             setFlash("Registration failed.", "danger");
        }
    }
}
?>

<div class="container">
  <?php include 'includes/flash.php'; ?>
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start">
    <div style="flex:1">
      <h2 style="color:var(--neon)">Register</h2>
      <form id="registerFormPHP" method="POST" action="" style="margin-top:12px">
        <label style="color:#ccc; display:block; margin-bottom:5px;">Name</label>
        <input name="name" class="input" placeholder="Full Name" required><br><br>

        <label style="color:#ccc; display:block; margin-bottom:5px;">Email</label>
        <input name="email" class="input" placeholder="Email" type="email" required><br><br>
        
        <label style="color:#ccc; display:block; margin-bottom:5px;">Password</label>
        <input name="password" class="input" placeholder="Password" type="password" required><br><br>
        
        <button class="btn" type="submit">Sign Up</button>
      </form>
      <p style="margin-top:12px">Already have an account? <a href="login.php" style="color:var(--neon)">Login</a></p>
    </div>
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
<?php include 'includes/footer.php'; ?>

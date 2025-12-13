<?php
// login.php
require 'config/db.php';
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        setFlash("Logged in successfully!", "success");
        echo "<script>window.location.href='profile.php';</script>";
        exit;
    } else {
        setFlash("Invalid credentials.", "danger");
    }
}
?>

<div class="container">
  <?php include 'includes/flash.php'; ?>
  <div class="panel" style="display:flex;gap:18px;align-items:flex-start">
    <div style="flex:1">
      <h2 style="color:var(--neon)">Login</h2>
      <!-- Changed ID to avoid JS interception -->
      <form id="loginFormPHP" method="POST" action="" style="margin-top:12px">
        <label style="color:#ccc; display:block; margin-bottom:5px;">Email</label>
        <input name="email" class="input" placeholder="Email" required><br><br>
        
        <label style="color:#ccc; display:block; margin-bottom:5px;">Password</label>
        <input name="password" class="input" placeholder="Password" type="password" required><br><br>
        
        <button class="btn" type="submit">Login</button>
      </form>
      <p style="margin-top:12px">Don't have account? <a href="register.php" style="color:var(--neon)">Sign Up</a></p>
    </div>
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
<?php include 'includes/footer.php'; ?>

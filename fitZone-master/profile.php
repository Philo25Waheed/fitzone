<?php
/*
 * ============================================================================
 * MIXED FILE - Profile Page (Backend + Frontend)
 * ============================================================================
 * Backend: Load user data, handle profile updates
 * Frontend: User profile dashboard with neon design
 * ============================================================================
 */

$page_title = 'Profile - FitZone';
require_once 'backend/includes/header.php';

// Require login to access this page
requireLogin('login.php');

// Get user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([getUserId()]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weight = !empty($_POST['weight']) ? floatval($_POST['weight']) : null;
    $height = !empty($_POST['height']) ? floatval($_POST['height']) : null;
    $goal = !empty($_POST['goal']) ? sanitize($_POST['goal']) : null;
    
    $stmt = $pdo->prepare("UPDATE users SET weight = ?, height = ?, goal = ? WHERE id = ?");
    
    if ($stmt->execute([$weight, $height, $goal, getUserId()])) {
        setFlash("Profile updated successfully!", "success");
        header("Location: profile.php");
        exit;
    } else {
        setFlash("Failed to update profile.", "danger");
    }
}

// Calculate BMI if height and weight are available
$bmi = null;
if ($user['weight'] && $user['height']) {
    $height_m = $user['height'] / 100; // Convert cm to meters
    $bmi = round($user['weight'] / ($height_m * $height_m), 1);
}
?>

<div class="container">
  <?php include 'backend/includes/flash.php'; ?>
  
  <div class="panel">
    <h2 style="color:var(--neon)">Your Profile</h2>
    <p style="color:#cfc7dd;margin-top:8px;">Manage your fitness information</p>
    
    <div style="margin-top:24px;display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px;">
      <!-- User Info Card -->
      <div class="card" style="padding:20px;background:linear-gradient(135deg, rgba(177,59,255,0.1), rgba(255,62,229,0.05));">
        <h3 style="color:var(--neon);margin:0 0 8px 0;">Account Info</h3>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Member Since:</strong> <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
      </div>
      
      <!-- Stats Card -->
      <div class="card" style="padding:20px;background:linear-gradient(135deg, rgba(255,62,229,0.1), rgba(177,59,255,0.05));">
        <h3 style="color:var(--neon);margin:0 0 8px 0;">Your Stats</h3>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Weight:</strong> <?php echo $user['weight'] ? $user['weight'] . ' kg' : 'Not set'; ?></p>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Height:</strong> <?php echo $user['height'] ? $user['height'] . ' cm' : 'Not set'; ?></p>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>BMI:</strong> <?php echo $bmi ? $bmi : 'N/A'; ?></p>
        <p style="color:#cfc7dd;margin:4px 0;"><strong>Goal:</strong> <?php echo $user['goal'] ? ucwords(str_replace('_', ' ', $user['goal'])) : 'Not set'; ?></p>
      </div>
    </div>
    
    <h3 style="color:var(--neon);margin-top:32px;">Update Your Information</h3>
    <form method="POST" action="" style="margin-top:16px;max-width:600px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label style="color:#cfc7dd;display:block;margin-bottom:6px;">Weight (kg)</label>
          <input name="weight" class="input" type="number" step="0.1" value="<?php echo $user['weight'] ?? ''; ?>" placeholder="e.g., 75.5">
        </div>
        <div>
          <label style="color:#cfc7dd;display:block;margin-bottom:6px;">Height (cm)</label>
          <input name="height" class="input" type="number" step="0.1" value="<?php echo $user['height'] ?? ''; ?>" placeholder="e.g., 175">
        </div>
      </div>
      <br>
      
      <label style="color:#cfc7dd;display:block;margin-bottom:6px;">Fitness Goal</label>
      <select name="goal" class="input" style="width:100%;">
        <option value="">Select Your Goal</option>
        <option value="lose_weight" <?php echo $user['goal'] == 'lose_weight' ? 'selected' : ''; ?>>Lose Weight</option>
        <option value="build_muscle" <?php echo $user['goal'] == 'build_muscle' ? 'selected' : ''; ?>>Build Muscle</option>
        <option value="stay_fit" <?php echo $user['goal'] == 'stay_fit' ? 'selected' : ''; ?>>Stay Fit</option>
        <option value="increase_strength" <?php echo $user['goal'] == 'increase_strength' ? 'selected' : ''; ?>>Increase Strength</option>
      </select>
      <br><br>
      
      <button class="btn" type="submit">Update Profile</button>
    </form>
  </div>
</div>

<?php include 'backend/includes/footer.php'; ?>

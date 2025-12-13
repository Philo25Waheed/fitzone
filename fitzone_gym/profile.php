<?php
// profile.php
require 'config/db.php';
include 'includes/header.php';

if (!isLoggedIn()) {
    redirect("login.php");
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $goal = sanitize($_POST['goal']);
    
    $update = $pdo->prepare("UPDATE users SET weight=?, height=?, goal=? WHERE id=?");
    if ($update->execute([$weight, $height, $goal, $user_id])) {
        setFlash("Profile updated!", "success");
        // refresh user data
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>

<div class="container">
    <?php include 'includes/flash.php'; ?>
    
    <div class="panel" style="margin-bottom:20px">
        <h2 style="color:var(--neon)">My Profile</h2>
        <div style="display:flex; align-items:center; gap:20px; margin-top:20px;">
            <div style="width:80px; height:80px; border-radius:50%; background:var(--neon); display:flex; align-items:center; justify-content:center; color:black; font-weight:bold; font-size:24px;">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <div>
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p style="color:#aaa"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
    </div>

    <div class="panel">
        <h3 style="color:var(--neon)">Update Information</h3>
        <form method="POST" style="margin-top:15px">
            <input type="hidden" name="update_profile" value="1">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
                <div>
                    <label style="color:#cfc7dd">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" class="input">
                </div>
                <div>
                    <label style="color:#cfc7dd">Height (cm)</label>
                    <input type="number" step="0.1" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" class="input">
                </div>
            </div>
            <div style="margin-bottom:15px;">
                 <label style="color:#cfc7dd">Fitness Goal</label>
                 <input type="text" name="goal" value="<?php echo htmlspecialchars($user['goal']); ?>" class="input">
            </div>
            <button class="btn" type="submit">Save Changes</button>
        </form>
    </div>

</div>

<?php include 'includes/footer.php'; ?>

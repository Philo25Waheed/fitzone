<?php
/*
 * ============================================================================
 * MIXED FILE - Profile Page (Backend User Data + Frontend Display)
 * ============================================================================
 * BACKEND PART: Lines 3-28 - Authentication check, data fetching, and updates
 * FRONTEND PART: Lines 31-71 - HTML profile display and update form
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend logic (lines 3-28): DO NOT MODIFY without backend team
 * - Frontend HTML (lines 31-71): SAFE to modify for UI/UX changes
 * - Form fields must keep same 'name' attributes for backend compatibility
 * ============================================================================
 */

// ===== BACKEND: Database and Includes =====
require '../backend/config/db.php';  // BACKEND: Load database connection
include '../backend/includes/header.php';  // MIXED: Load header (contains frontend nav)

// ===== BACKEND: Authentication Check =====
// التحقق من تسجيل الدخول - إعادة توجيه إذا لم يكن مسجل
if (!isLoggedIn()) {
    redirect("login.php");
}

// ===== BACKEND: Fetch User Data from Database =====
// جلب بيانات المستخدم الحالي من قاعدة البيانات
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// ===== BACKEND: Handle Profile Update Form =====
// معالجة تحديث بيانات المستخدم
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // BACKEND: Get form data
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $goal = sanitize($_POST['goal']);
    
    // BACKEND: Update user data in database
    $update = $pdo->prepare("UPDATE users SET weight=?, height=?, goal=? WHERE id=?");
    
    if ($update->execute([$weight, $height, $goal, $user_id])) {
        // BACKEND: Update successful
        setFlash("Profile updated!", "success");
        
        // BACKEND: Refresh user data to show updated values
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>

<!-- ===== FRONTEND: Profile Page HTML ===== -->
<!-- يمكن تعديل التصميم والستايل هنا بحرية -->
<div class="container">
    <!-- BACKEND: Display flash messages (success/error) -->
    <?php include '../backend/includes/flash.php'; ?>
    
    <!-- FRONTEND: User Profile Display Section -->
    <div class="panel" style="margin-bottom:20px">
        <h2 style="color:var(--neon)">My Profile</h2>
        
        <!-- FRONTEND: User Avatar and Info -->
        <div style="display:flex; align-items:center; gap:20px; margin-top:20px;">
            <!-- FRONTEND: Avatar Circle with First Letter -->
            <div style="width:80px; height:80px; border-radius:50%; background:var(--neon); display:flex; align-items:center; justify-content:center; color:black; font-weight:bold; font-size:24px;">
                <!-- BACKEND: Display first letter of user's name -->
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            
            <!-- FRONTEND: User Name and Email Display -->
            <div>
                <!-- BACKEND: Display user's name from database -->
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <!-- BACKEND: Display user's email from database -->
                <p style="color:#aaa"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
    </div>

    <!-- FRONTEND: Profile Update Form Section -->
    <div class="panel">
        <h3 style="color:var(--neon)">Update Information</h3>
        
        <!-- FRONTEND: Profile Update Form -->
        <!-- ملاحظة مهمة: يجب الحفاظ على name attributes للحقول -->
        <form method="POST" style="margin-top:15px">
            <input type="hidden" name="update_profile" value="1">
            
            <!-- FRONTEND: Weight and Height Input Grid -->
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
                <!-- FRONTEND: Weight Input -->
                <div>
                    <label style="color:#cfc7dd">Weight (kg)</label>
                    <!-- BACKEND: Pre-fill weight from database -->
                    <input type="number" step="0.1" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" class="input">
                </div>
                
                <!-- FRONTEND: Height Input -->
                <div>
                    <label style="color:#cfc7dd">Height (cm)</label>
                    <!-- BACKEND: Pre-fill height from database -->
                    <input type="number" step="0.1" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" class="input">
                </div>
            </div>
            
            <!-- FRONTEND: Fitness Goal Input -->
            <div style="margin-bottom:15px;">
                 <label style="color:#cfc7dd">Fitness Goal</label>
                 <!-- BACKEND: Pre-fill goal from database -->
                 <input type="text" name="goal" value="<?php echo htmlspecialchars($user['goal']); ?>" class="input">
            </div>
            
            <!-- FRONTEND: Submit Button -->
            <button class="btn" type="submit">Save Changes</button>
        </form>
    </div>

</div>

<!-- BACKEND: Include footer (contains JS) -->
<?php include '../backend/includes/footer.php'; ?>


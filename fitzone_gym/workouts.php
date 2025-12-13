<?php
// workouts.php
require 'config/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM workouts");
$plans = $stmt->fetchAll();
?>

<div class="site-overlay container" style="padding-top:40px;min-height:70vh">

  <h1 class="h-title fade-slide">Training Programs</h1>
  <p class="h-sub fade-slide" style="margin-top:8px">Choose a training split to view detailed days & exercises.</p>

  <div class="panel fade-slide" style="margin-top:20px">
    <div class="splits-grid">
      <?php foreach($plans as $plan): ?>
      <div class="split-card fade-slide">
          <h3><?php echo htmlspecialchars($plan['title']); ?></h3>
          <p><?php echo htmlspecialchars($plan['description']); ?></p>
          <div style="margin-top:8px">
              <span class="btn" style="cursor:default"><?php echo htmlspecialchars($plan['level']); ?></span>
          </div>
      </div>
      <?php endforeach; ?>
      <!-- Fallback static links if DB is empty or for extra options -->
      <div class="split-card fade-slide"><h3>Bro Split (Static)</h3><p>Classic bodypart split.</p><div style="margin-top:8px"><a class="btn" href="training_bro.html">View</a></div></div>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>

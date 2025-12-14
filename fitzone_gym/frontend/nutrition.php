<?php
// nutrition.php
require '../backend/config/db.php';
include '../backend/includes/header.php';

$stmt = $pdo->query("SELECT * FROM recipes");
$recipes = $stmt->fetchAll();
?>

<div class="container">
  <div class="panel">
    <h2 style="color:var(--neon)">Healthy Meals</h2>
    <p>Select from our curated list of healthy meals.</p>
    <div class="meals-grid" style="margin-top:12px">
      
      <?php foreach($recipes as $recipe): ?>
      <div class="meal">
        <img src="img/<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['name']); ?>">
        <strong><?php echo htmlspecialchars($recipe['name']); ?></strong>
        <div style="color:#cfc7dd;margin-top:6px"><?php echo htmlspecialchars($recipe['calories']); ?> kcal</div>
        <p style="font-size:0.9em; color:#aaa;"><?php echo htmlspecialchars($recipe['description']); ?></p>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>

<?php include '../backend/includes/footer.php'; ?>

<?php
// services.php
include 'includes/header.php';
?>

<div class="container">
  <div class="panel">
    <h2 style="color:var(--neon)">Our Services</h2>
    <p>Choose your training program.</p>
  </div>

  <div class="panel" style="margin-top:12px">
    <h3 style="color:var(--neon)">Advanced Calorie & Macro Calculator</h3>
    <p>Calculate your daily needs for Bulking, Cutting, or Maintenance.</p>
    <form id="advCalForm" style="margin-top:12px;display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
      <input id="adv_weight" class="input" placeholder="Weight (kg)">
      <input id="adv_height" class="input" placeholder="Height (cm)">
      <input id="adv_age" class="input" placeholder="Age">
      <select id="adv_gender" class="input"><option value="male">Male</option><option value="female">Female</option></select>
      <select id="adv_activity" class="input"><option value="1.2">Sedentary</option><option value="1.375">Moderate</option><option value="1.55">High Activity</option></select>
      <select id="adv_goal" class="input"><option value="maintenance">Maintenance</option><option value="bulking">Bulking</option><option value="cutting">Cutting</option></select>
      <button class="btn" type="button" onclick="App.calcAdvanced()">Calculate</button>
    </form>
    <div id="advResults" class="calc-results neon-outline" style="margin-top:10px"></div>
  </div>

  <div class="panel" style="margin-top:18px">
    <h2 style="color:var(--neon)">Training Splits</h2>
    <p>We offer a variety of training splits to suit your schedule.</p>
    <div class="splits-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:12px;">
      <div class="split-card"><h3>Bro Split</h3><p>Classic split: Chest, Back, Shoulders, Legs, Arms.</p><a href="training_bro.html" class="btn">View Details</a></div>
      <div class="split-card"><h3>Full Body</h3><p>Whole body 3-4 times a week.</p><a href="training_full.html" class="btn">View Details</a></div>
      <div class="split-card"><h3>Push / Pull</h3><p>Push movements & Pull movements.</p><a href="training_pushpull.html" class="btn">View Details</a></div>
      <div class="split-card"><h3>Body Part Split</h3><p>Group big muscles and small muscles.</p><a href="training_bodypart.html" class="btn">View Details</a></div>
      <div class="split-card"><h3>Powerbuilding</h3><p>Strength + Hypertrophy.</p><a href="training_power.html" class="btn">View Details</a></div>
    </div>
  </div>

</div>
<div id="splitModal" class="modal-backdrop"><div class="modal" id="splitContent"></div></div>

<?php include 'includes/footer.php'; ?>

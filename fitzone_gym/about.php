<?php
// about.php
include 'includes/header.php';
?>

<!-- We reuse the specific styles from the original about page but in strict body context -->
<style>
  /* Local overrides if needed, but trying to use global style.css where possible. 
     The original about.html had specific styles we might want to preserve 
     if they are not in style.css. 
     Succinctly replicating key layout parts: */
  .hero-about {
    position:relative; margin-top:8px; border-radius:18px; overflow:hidden;
    display:flex; gap:36px; align-items:center; padding:44px;
    background: linear-gradient(180deg, rgba(10,10,10,0.55), rgba(10,10,10,0.55)), url('img/hero_v2.jpeg') center/cover no-repeat; /* Fallback image */
    min-height:50vh;
  }
  .hero-about h1 { font-size:56px; line-height:1; font-weight:900; color:var(--primary-yellow); text-shadow:0 0 18px rgba(231,255,42,0.55); margin:0 0 12px 0; }
  .about-content { margin-top:36px; display:flex; gap:28px; align-items:flex-start; flex-wrap:wrap; }
  .about-text { flex:1 1 500px; background:rgba(255,255,255,0.02); border-radius:14px; padding:26px; border:1px solid rgba(255,255,255,0.04); }
</style>

<div class="container">
    <!-- HERO -->
    <main class="hero-about" role="main">
      <div style="flex:1; z-index:2; color:white;">
        <h1 class="hero-title">About FitZone</h1>
        <p style="color:#cfc7dd; margin-bottom:22px; font-size:17px;">FitZone is your futuristic fitness companion, combining AI-driven tracking with premium training plans.</p>
        <a class="btn" href="contact.php">Contact Us</a>
      </div>
    </main>

    <!-- ABOUT DETAILS -->
    <section class="about-content">
      <div class="about-text">
        <h3 style="color:var(--neon)">Our Vision</h3>
        <p style="color:#aaa; line-height:1.7">We provide a smart space combining training, nutrition, and daily motivation. Our goal is for FitZone to be your main partner in reaching your best physical version.</p>
        
        <h3 style="color:var(--neon); margin-top:20px;">Our Goal</h3>
        <p style="color:#aaa; line-height:1.7">Providing workout tools, health calculators, healthy food options, and continuous challenges to help you progress step by step without complexity.</p>

        <h3 style="color:var(--neon); margin-top:20px;">The Team</h3>
        <p style="color:#aaa; line-height:1.7">A complete team of specialized trainers and developers responsible for developing the experience to offer a smoother journey.</p>
      </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<?php
/*
 * ============================================================================
 * MIXED FILE - Contact Page (Backend + Frontend)
 * ============================================================================
 * Backend: Contact form submission and database storage
 * Frontend: Neon cyberpunk contact form
 * ============================================================================
 */

$page_title = 'Contact - FitZone';
require_once 'backend/includes/header.php';

// Handle contact form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $message = sanitize($_POST['message']);
    
    // Insert contact message into database
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $message])) {
        setFlash("Message sent successfully! We'll get back to you soon.", "success");
        // Clear form by redirecting
        header("Location: contact.php");
        exit;
    } else {
        setFlash("Failed to send message. Please try again.", "danger");
    }
}
?>

<div class="container">
  <?php include 'backend/includes/flash.php'; ?>
  
  <div class="panel">
    <h2 style="color:var(--neon)">Contact Us</h2>
    <p style="color:#cfc7dd;margin-top:8px;">Have questions? We'd love to hear from you!</p>
    
    <form method="POST" action="" style="margin-top:20px;max-width:600px;">
      <input name="name" class="input" placeholder="Your Name" required>
      <br><br>
      
      <input name="email" class="input" placeholder="Email Address" type="email" required>
      <br><br>
      
      <textarea name="message" class="input" placeholder="Your Message" style="height:120px;resize:vertical;" required></textarea>
      <br><br>
      
      <button class="btn" type="submit">Send Message</button>
    </form>
  </div>
</div>

<?php include 'backend/includes/footer.php'; ?>

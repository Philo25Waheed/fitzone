<?php
/*
 * ============================================================================
 * MIXED FILE - Contact Page (Backend Message Storage + Frontend Form)
 * ============================================================================
 * BACKEND PART: Lines 3-17 - Save contact messages to database
 * FRONTEND PART: Lines 20-33 - HTML contact form
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend logic: DO NOT MODIFY without backend team
 * - Frontend HTML: SAFE to modify form design
 * - Keep form field names: name, email, message
 * ============================================================================
 */

// ===== BACKEND: Database and Includes =====
require 'config/db.php';
include 'includes/header.php';

// ===== BACKEND: Contact Form Processing =====
// حفظ رسالة التواصل في قاعدة البيانات
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // BACKEND: Sanitize inputs
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $message = sanitize($_POST['message']);

    // BACKEND: Insert message into database
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $message])) {
        setFlash("Message sent! We'll get back to you soon.", "success");
    } else {
        setFlash("Failed to send message.", "danger");
    }
}
?>


<div class="container">
  <?php include 'includes/flash.php'; ?>
  <div class="panel">
    <h2 style="color:var(--neon)">Contact Us</h2>
    <form id="contactFormPHP" method="POST" action="" style="margin-top:12px">
      <input name="name" class="input" placeholder="Your name" required><br><br>
      <input name="email" class="input" placeholder="Email" required><br><br>
      <textarea name="message" class="input" placeholder="Message" style="height:120px" required></textarea><br><br>
      <button class="btn" type="submit">Send Message</button>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

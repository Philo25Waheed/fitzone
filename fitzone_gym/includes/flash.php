<?php
// includes/flash.php
$flash = getFlash();
if($flash): ?>
    <div style="background: <?php echo $flash['type'] == 'success' ? '#2ecc71' : '#e74c3c'; ?>; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: white;">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

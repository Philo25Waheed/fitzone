<?php
/*
 * ============================================================================
 * MIXED FILE - Flash Messages Display (Backend Logic + Frontend HTML)
 * ============================================================================
 * Purpose: Display temporary success/error messages to users
 * 
 * IMPORTANT FOR FRONTEND TEAM:
 * - Backend: getFlash() function retrieves message
 * - Frontend: HTML/CSS for message display - SAFE to modify styling
 * ============================================================================
 */

// ===== BACKEND: Get Flash Message =====
// جلب الرسالة المؤقتة من الـ session
$flash = getFlash();

// ===== FRONTEND: Display Flash Message =====
// عرض الرسالة للمستخدم إذا كانت موجودة
if($flash): ?>
    <!-- FRONTEND: Message Box -->
    <!-- يمكن تعديل الستايل والألوان هنا -->
    <div style="background: <?php echo $flash['type'] == 'success' ? '#2ecc71' : '#e74c3c'; ?>; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: white;">
        <!-- BACKEND: Display message text -->
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>


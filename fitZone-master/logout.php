<?php
/*
 * ============================================================================
 * BACKEND FILE - Logout Handler
 * ============================================================================
 * Purpose: Destroy user session and redirect to home
 * ============================================================================
 */

require_once 'backend/includes/session.php';

// Logout user
logoutUser();

// Redirect to home
header("Location: index.php");
exit;
?>

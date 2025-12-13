<?php
// logout.php
require 'includes/functions.php';
session_start();
session_unset();
session_destroy();
session_start(); // Start new session to set flash
setFlash("Logged out successfully.", "info");
redirect("index.php");
?>

<?php
session_start();
require_once '../includes/functions.php';

// Perform logout
adminLogout();

// Redirect to login page
header('Location: index.php?logged_out=1');
exit;
?>
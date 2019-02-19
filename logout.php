<?php
session_start();
// Set login status to false
$_SESSION['LoggedIn'] = false;
// Add message to display on next screen
$_SESSION['message'] = "Logged out successfully!";
header('Location: index.php');
?>
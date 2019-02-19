<?php
session_start();
if (!isset($_SESSION['LoggedIn']) || !$_SESSION['LoggedIn'] == true)  {
header("Location: login.php");
exit(); // don’t proceed with the rest of this page
}
?>
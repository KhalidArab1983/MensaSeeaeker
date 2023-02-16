<?php
session_start();
// Unset session variables
$_SESSION = array();
// Destroy the session
session_destroy();
// Redirect to the login page
header("Location: a_login.php");
exit;
?>
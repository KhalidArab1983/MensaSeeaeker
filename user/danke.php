<?php
include('../conn/db_conn.php');

session_start();
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
}else{
    header("Location: u_login.php");
	exit;
}

?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
        
    </head>
    <body>
        <div style="text-align:center; margin-top:10%">
            <img class="mb-5" src="../images/logo.jpg">
            <h1>Vielen Dank für Ihre Bestellung</h1>
            <h3><a href="u_user_page.php">Zurück zur Hauptseite</a></h3>
        </div>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
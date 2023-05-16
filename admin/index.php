<?php

include('../conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
}else{
    header("Location: a_login.php");
	exit;
}
date_default_timezone_set("Europe/Berlin");

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
    <body>
        <?php include ('./nav_includes/navbar.php') ?>

        <hr class="height5">
        <div class="container">
            <h3>An Benutzerdaten vorgenommene Aktualisierungen:</h3>
            <div class="scrollView700">
                <?php include ('./index_includes/update_user_inc.php') ?>
            </div>

            <hr class="mt-5 mb-4 height5">
            
            <h3>An Gerichte vorgenommene Aktualisierungen:</h3>
            <div class="scrollView700">
                <?php include('./index_includes/update_meal_inc.php') ?>
            </div>
        </div>

        <div class="marginBottom80">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>

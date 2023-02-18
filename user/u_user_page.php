<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	header("Location: u_login.php");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
	<title>Mensa</title>
</head>
<body>

<div class="container-fluid">
        <h5>Herzlichen Wilkommen <?php echo $_SESSION['userName']; ?></h5>
    </div>
    <div class="container-fluid">
        <h3>Test 1</h3>
        <a href="">Test 1 klick</a>

        <h3>Test</h3>
        <a href="">Test klick</a>
    </div>

    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
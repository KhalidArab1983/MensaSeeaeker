<?php

include('./conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}


// include('./admin/insertUpdateMeals.php');



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
    
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-light p-4 w-100" style="display:inline-block;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                    <li class="nav-item item_besonder">
                        <a class="nav-link active" href="./index.php"><h6>Haupt Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/create_user.php"><h6>Neu Benutzer |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/a_user_page.php"><h6>Benutzer Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/a_logout.php"><h6>Abmelden</h6></a>
                    </li>
                </ul>
            </div>
        </div>

        <img class="logo" src="./images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
        <nav class="navbar navbar-dark bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h5 style="margin: 0;">Herzlich Willkommen <span style="color:green"><?php echo $_SESSION['adminName']; ?></span></h5>
            </div>
        </nav>

        <hr style="height: 5px">
        <div class="container-fluid">
            <div class="container-fluid">
                <h3>melde ein neue Benutzer</h3>
                <a href="./admin/create_user.php">Neu Benutzer melden</a>

                <h3>Benutzern bearbeiten</h3>
                <a href="./admin/a_user_page.php">Benutzer Seite</a>

                <h3>Bilder</h3>
                <a href="./admin/images.php">images</a>
            </div>
        </div>



        
    <div style="margin-bottom: 80px">
        
    </div>
    <footer class="fixed-bottom footer">
        <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
    </footer>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
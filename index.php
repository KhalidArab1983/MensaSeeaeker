<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	header("Location: a_login.php");
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
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_logout.php">Logout</a>
                    </li>
                </ul>
                <img src="./images/logo.png" alt="Seeäkerschule Logo" width=10%>
            </div>
        </div>
    </nav> -->
    
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link active" href="#"><h5>Home</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_login.php"><h5>Login</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_register.php"><h5>Register</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_logout.php"><h5>Logout</h5></a>
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
        </div>
    </nav>

    <hr style="height: 5px">
    
    <div class="container-fluid">
        <h5>Herzlichen Wilkommen <?php echo $_SESSION['userName']; ?></h5>
    </div>
    <div class="container-fluid">
        <h3>melde ein neue Benutzer</h3>
        <a href="./admin/create_user.php">Neu Benutzer melden</a>

        <h3>Benutzern bearbeiten</h3>
        <a href="./admin/a_user_page.php">Benutzer Seite</a>
    </div>

    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
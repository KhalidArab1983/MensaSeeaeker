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



if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (isset($_POST['button'])) {
        $button = $_POST['button'];
    }
    if (isset($_POST['adminColor'])) {
        $adminColor = $_POST['adminColor'];
    }
    if($button == 'save'){
        $sql = "UPDATE tbl_admin SET color_hex = '$adminColor' WHERE id = '{$admin_id}'";
        if(mysqli_query($conn, $sql)){
            header("Location: a_setting.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}

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
        <link rel="stylesheet" href="../css/fontawesome.css">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-light p-4 w-100" style="display:inline-block;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="../index.php"><h6>Haupt Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./create_user.php"><h6>Neu Benutzer |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./a_user_page.php"><h6>Benutzer Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link active" href="./a_setting.php"><h6>Einstellungen |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./a_logout.php"><h6>Abmelden</h6></a>
                    </li>
                    
                </ul>
            </div>
        </div>

        <img class="logo" src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
        <nav class="navbar navbar-dark bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h5 style="margin: 0;">Herzlich Willkommen <span style="color:<?php echo $adminColor;?>"><?php echo $_SESSION['adminName']; ?></span></h5>
            </div>
        </nav>

        <hr style="height: 5px">
        <div class="container">
            <div>
                <h3>Die Farbe des Adminnamens ändern:</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <input type="color" class="form-control" name="adminColor" id="adminColor" value="<?php echo $adminColor ?>">
                    <button type="submit" class="btn btn-warning" name="button" value="save">Speichern</button>
                </form>
            </div>

        </div>






        <div style="margin-bottom: 80px">
        
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
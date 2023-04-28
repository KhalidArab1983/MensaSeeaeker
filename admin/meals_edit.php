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

include('./insertUpdateMeals.php');


$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];


$conn->close();
?>



<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a class="nav-link active" href="./meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./a_setting.php"><h6>Einstellungen |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./a_logout.php"><h6>Abmelden</h6></a>
                    </li>
                </ul>
            </div>
        </div>
        <img src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
        <nav class="navbar navbar-dark bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h5 style="margin: 0;">Herzlich Willkommen <span style="color:<?php echo $adminColor;?>"><?php echo $_SESSION['adminName']; ?></span></h5>
            </div>
        </nav>
        <hr class="mb-5" style="height: 5px">

        <div class="container" style="display:flex">
            <div class="col-lg-3">

            </div>
            <div class="col-lg-6 text-center">
                <p class="text-center">Sie müssen eine Option aus der ersten Zeile auswählen, und dann können alle Informationen über das Essen geändert werden.</p>
                <form action="meals_edit.php" method="post" enctype="multipart/form-data">
                    <div class="mb-2">
                        <select class="form-control" name="id">
                            <?php
                            include('../conn/db_conn.php');
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                            while($row = mysqli_fetch_assoc($result)){
                                $option_name = $row['option_name'];
                                $day = $row['day'];
                                $data = $row['data'];
                                $price = $row['price'];
                                $option_id = $row['id'];
                                echo '<option value="' . $option_id . '">'.$option_id." - " . $option_name . ' - ' . $day. " - " . $price . '€</option>';
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" name="option_name"  placeholder="option name">
                    </div>
                    <div class="mb-1">
                        <select  class="form-control" name="day" placeholder="Days">
                            <option value="" selected>Wählen Sie einen Tag aus...</option>
                            <option>Montag</option>
                            <option>Dienstag</option>
                            <option>Mittwoch</option>
                            <option>Donnerstag</option>
                            <option>Freitag</option>
                        </select>
                    </div>
                    <div class="disFlex m-1">
                        <input class="form-control" type="date" name="date">
                        
                    </div>
                    <div class="mb-1">
                        <input type="text" class="form-control" name="price" placeholder="price">
                    </div>
                    <div class="mb-1">
                        <label style="font-size:12px">Wenn das Bild ausgewählt ist, müssen andere Felder ausgefüllt werden. Aber die anderen Felder können einzeln aktualisiert werden.</label>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="image">
                        <label style="font-size:12px">* Es sind nur PNG und JPG-Dateien erlaubt </label>
                    </div>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="insert">Eingeben</button>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="update">Aktualisieren</button>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="dateUpdate">Datum aktualisieren</button>
                </form>
            </div>
            <div class="col-lg-3">
                
            </div>
        </div>

        <div style="margin-bottom: 80px">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
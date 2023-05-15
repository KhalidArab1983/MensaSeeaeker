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

include('./meals_includes/insertUpdateMeals.php');


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
        <?php include ('./nav_includes/navbar.php') ?>

        <hr class="mb-5 height5">

        <div class="container disFlex">
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
                            <option class="colorRed">Feiertag</option>
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
                        <label class="font12">Wenn das Bild ausgewählt ist, müssen andere Felder ausgefüllt werden. Aber die anderen Felder können einzeln aktualisiert werden.</label>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="image">
                        <label class="font12">* Es sind nur PNG und JPG-Dateien erlaubt </label>
                    </div>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="insert">Eingeben</button>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="update">Aktualisieren</button>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="dateUpdate">Datum aktualisieren</button>
                    <button type="submit" class="btn btn-warning m-1" name="button" value="holiday">Feiertag einstellen</button>
                </form>
                <p class="mt-5 font15">HINWEIS: Was an jedem Sonntag gemacht werden muss: <br> 1. Wenn es einen Feiertag gab, muss wieder auf Wochentag zurückgestellt werden.<br>
                    Wählen Sie das gewünschte Datum des Tages aus seinem Feld und wählen Sie den Tag, für den es geändert werden soll, aus dem Feld Tage aus, 
                    dann klicken Sie auf "Feiertag einstellen".<br>
                    2. Müssen die Datums jede Woche für neue Woche geändert werden.<br> Wählen Sie den genauen Tag aus dem Tagefeld und dann das neue Datum aus, dann 
                    klicken auf "Datum aktualisieren".
                </p>
            </div>
            <div class="col-lg-3">
                
            </div>
        </div>

        <div class="marginBottom80">
            
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
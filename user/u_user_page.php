<?php
include('../conn/db_conn.php');

session_start();
$data = '';
$current_day = '';
$user_id = $_SESSION['loggedin'] ;
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	header("Location: u_login.php");
	exit;
}





// // Überprüfe, ob das Formular abgesendet wurde
// if (isset($_POST['submit'])) {
//     // Hole die ausgewählte Option
//     $montag = $_POST['montag'];
//     $dienstag = $_POST['dienstag'];
//     $mittwoch = $_POST['mittwoch'];
//     $donnerstag = $_POST['donnerstag'];
//     $freitag = $_POST['freitag'];

//     // Überprüfe, ob die ausgewählte Option gültig ist
//     $valid_montags = ['montag1', 'montag2', 'montag3', 'montag4'];
//     if (!in_array($montag, $valid_montags)) {
//         die('Ungültige Option ausgewählt!');
//     }

//     // Überprüfe, ob die Auswahlzeit gültig ist (vor 22 Uhr)
//     $current_time = time();
//     $selection_time = strtotime('today 22:00:00');
//     if ($current_time >= $selection_time) {
//         die('Auswahl ist nach 22:00 Uhr nicht mehr möglich!');
//     }

//     // Lösche die vorherige Auswahl aus der Datenbank
//     $query = "DELETE FROM tbl_bestellung WHERE user_id = $user_id";
//     mysqli_query($conn, $query);

//     // Füge die neue Auswahl in die Datenbank ein
//     $query = "INSERT INTO tbl_bestellung (user_id, montag, dienstag, mittwoch, donnerstag, freitag) VALUES ('$user_id', '$montag', '$dienstag', '$mittwoch', '$donnerstag', '$freitag')";
//     mysqli_query($conn, $query);
// }


include ('./u_kontoZustand.php');



$days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
$options = array("Snacks", "Tagesessen", "Salat", "Alternative-Menu");
// Get current date and time
date_default_timezone_set("Europe/Berlin");
$current_date = date("Y-m-d");
$current_time = date("H:i:s");

// Check if it's before 22:00
$cutoff_time = "22:00:00";
if ($current_time < $cutoff_time) {
    // Get the user ID (change this to the actual user ID)
    $user_id = 1;

    // Loop through the days and options to insert or update the selections in the database
    // $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
    // $options = array("Snacks", "Tagesessen", "Salat", "Alternative-Menu");

    foreach ($days as $day) {
        foreach ($options as $option) {
            // Check if the radio button for this day and option was selected
            if (isset($_POST[$day]) && $_POST[$day] == $option) {
                // Check if the user has already made a selection for this day
                $selected_option = null;
                $sql = "SELECT * FROM `tbl_bestellung` WHERE `user_id` = $user_id AND `day` = '$current_date'";
                $result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $selected_option = $row['option_id'];
                }else{
                    return null;
                }
            }
        }
    }


    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($days as $day) {
        if ($_POST[$day]) {
        $option = mysqli_real_escape_string($conn, $_POST[$day]);
        $sql = "INSERT INTO `tbl_bestellung` (`user_id`, `day`, `option_id`) VALUES ($user_id, '$day', '$option') ON DUPLICATE KEY UPDATE `option_id` = '$option'";
        mysqli_query($conn, $sql);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
    }

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
        <style>
        table, th, td {
            border: 1px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: lightgray;
        }
        .selected {
            background-color: yellow;
        }
    </style>
    </head>
    <body>
        <div class="nav_besonder">
            <h4 class="mt-3 ms-3">Herzlichen Wilkommen </h4>
            <h4 class="mt-3 ms-3" style="color:red;"><?php echo $_SESSION['userName'];?></h4>
            <a class="nav-link ms-2 mt-1 btn-outline-danger" href="./u_logout.php"><h3>Logout</h3></a>
            <div >
                <h3 class="mt-3"><input style="font-weight: bold; color: green; width: 280px" class="text-center" id="system-time" name="system-time" value="<?php echo date('H:i:s'); ?>" readonly></h3>
            </div>
            
        </div>
        <div>
            <img class="logo" src="../images/logo.png" alt="Seeäkerschule Logo" width=6% style="float:right;">
        </div>
        
        <div class="tab">
            <button class="tablinks active" data-tab="userData">Benutzer Daten</button>
            <button class="tablinks" data-tab="kontoZustand">Konto Zustand</button>
            <button class="tablinks" data-tab="essenBestellung">Essen Bestellung</button>
            <button class="tablinks" data-tab="tab4">Tab 4</button>
        </div>

        <div id="userData" class="tabcontent">
            <h3>Meine Daten</h3>
            <p>Das ist der Inhalt von Tab 1.</p>
        </div>

        <div id="kontoZustand" class="tabcontent">
            <h1>Bank Account Simulation</h1>

            <p>Ihr Kontostand ist: $<?php echo number_format($_SESSION['balance'], 2); ?></p>

            <h2>Geld einzahlen</h2>
            <form method="post">
                <label for="deposit_amount">Betrag:</label>
                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount">
                <button type="submit">einzahlen</button>
            </form>

            <h2>Geld auszahlen</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="withdrawal_amount">Betrag:</label>
                <input type="number" step="0.01" name="withdrawal_amount" id="withdrawal_amount">
                <button type="submit">auszahlen</button>
            </form>
        </div>

        <div id="essenBestellung" class="tabcontent">
            <h4>Essen bestellen</h4>
            <div style="float:left; width:60vw; margin-top:10px;">
                <form action="u_login.php" method="post">

                    <div class="mb-1" style="height:10vh">
                        <label for="option_name" style="width:115px; font-weight:bold">Montag:</label>
                        <select class="w-50 h-50" name="option_name" id="optionListMo"  onChange="chImageMo()">
                            <option value="none">auswählen</option>
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Montag'";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    $option_name = $row['option_name'];
                                    $data = $row['data'];
                                    $price = $row['price'];
                                    $option_id = $row['id'];
                                    echo '<option value="' . $option_id . '">' . $option_name . ' - ' . $price . '€</option>';
                                }
                            ?>
                        </select>
                        <br>
                        <!-- <input type="text"  id="monday" name="monday" value="<?php 
                            // if($date->format('N') < 6){ 
                            //     echo date('d.m.Y');} 
                        ?>"> -->
                    </div>

                    <div class="mb-1" style="height:10vh">
                        <label for="option_name" style="width:115px; font-weight:bold">Dienstag: </label>
                        <select class="w-50 h-50" name="option_name_Di" id="optionListDi"  onChange="chImageDi()">
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Dienstag'";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                            while($row = mysqli_fetch_assoc($result)){
                                $option_name = $row['option_name'];
                                $data = $row['data'];
                                $price = $row['price'];
                                $option_id = $row['id'];
                                echo '<option value="' . $option_id . '">' . $option_name . ' - ' . $price . '€</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <!-- <input type="text" id="tuesday" name="tuesday" value="<?php
                            // $date = new DateTime();
                            // $date->modify('+1 day');
                            // if($date->format('N') < 6){
                            //     echo date('d.m.Y');
                            // }
                            
                        ?>"> -->
                    </div>

                    <div class="mb-1" style="height:10vh">
                        <label for="option_name" style="width:115px; font-weight:bold">Mittwoch: </label>
                        <select class="w-50 h-50" name="option_name_Mi" id="optionListMi"  onChange="chImageMi()">
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Mittwoch'";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                            while($row = mysqli_fetch_assoc($result)){
                                $option_name = $row['option_name'];
                                $data = $row['data'];
                                $price = $row['price'];
                                $option_id = $row['id'];
                                echo '<option value="' . $option_id . '">' . $option_name . ' - ' . $price . '€</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <!-- <input type="text" id="wednesday" name="wednesday" value="<?php
                            // $date = new DateTime();
                            // $date->modify('+1 day');
                            // if($date->format('N') < 6){
                            //     echo date('d.m.Y');
                            // }
                        ?>"> -->
                    </div>

                    <div class="mb-1" style="height:10vh">
                        <label for="option_name" style="width:115px; font-weight:bold">Donnerstag: </label>
                        <select class="w-50 h-50" name="option_name_Do" id="optionListDo" onChange="chImageDo()">
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Donnerstag'";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                            while($row = mysqli_fetch_assoc($result)){
                                $option_name = $row['option_name'];
                                $data = $row['data'];
                                $price = $row['price'];
                                $option_id = $row['id'];
                                echo '<option value="' . $option_id . '">' . $option_name . ' - ' . $price . '€</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <!-- <input type="text" id="thursday" name="thursday" value="<?php
                            // $date = new DateTime();
                            // $date->modify('+1 day');
                            // if($date->format('N') < 6){
                            //     echo date('d.m.Y');
                            // }
                        ?>"> -->
                    </div>

                    <div class="mb-1" style="height:10vh">
                        <label for="option_name" style="width:115px; font-weight:bold">Freitag: </label>
                        <select class="w-50 h-50" name="option_name_Fr" id="optionListFr" onChange="chImageFr()">
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Freitag'";
                                $result = mysqli_query($conn, $sql);
                            // Include each result as an option tag in the drop-down list
                            while($row = mysqli_fetch_assoc($result)){
                                $option_name = $row['option_name'];
                                $data = $row['data'];
                                $price = $row['price'];
                                $option_id = $row['id'];
                                echo '<option value="' . $option_id . '">' . $option_name . ' - ' . $price . '€</option>';
                            }
                                mysqli_close($conn);
                            ?>
                        </select>
                        <br>
                        <!-- <input type="text" id="friday" name="friday" value="<?php
                            // $date = new DateTime();
                            // $date->modify('+1 day');
                            // if($date->format('N') < 6){
                            //     echo date('d.m.Y');
                            // }
                        ?>"> -->
                    </div>
                    <div class="text-center">
                        <input type="submit" name="submit" class="btn btn-primary w-25 me-5" value="Bestellen">
                    </div>
                </form>
            </div>
            <div>
            <div style="float:left; width:30vw; box-shadow: -4px 1px 4px #888; height:100vh; margin-top:10px">
                <div id="imageContainer">
                    
                </div>
                <div id="imageContainer1">

                </div>
                <div id="imageContainer2" >

                </div>
                <div id="imageContainer3">

                </div>
                <div id="imageContainer4">

                </div>
            </div>
        </div>

        <div id="tab4" class="tabcontent">
            
            
        </div>

        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
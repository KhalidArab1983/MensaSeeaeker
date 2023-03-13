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
// date_default_timezone_set('Europe/Berlin');

$stunden = 0;
$minuten = 0;
$sekunden = 0;
if (isset($_SESSION['sessionTime'])) {
    $sessionTime = strtotime($_SESSION['sessionTime']);
    $jetzt = time();
    $zeitunterschied = $jetzt - $sessionTime;
    $stunden = floor($zeitunterschied / 3600);
    $minuten = floor(($zeitunterschied - ($stunden * 3600)) / 60);
    $sekunden = $zeitunterschied - ($stunden * 3600) - ($minuten * 60);
} else {
    $_SESSION['sessionTime'] = date('H:i:s');
}



include ('./u_kontoZustand.php');
include ('./bestell_insert.php');
// include('./update_bestellstatus.php');

if(date('l') == 'Saturday'){
    $bestell_status = 0;
    $aktuellBestellStatus = 1;
    // Reset user's bestell_status at the start of every week
    // Add this task to a weekly cron job
    $sql = "UPDATE tbl_user SET bestell_status = ? WHERE bestell_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $bestell_status, $aktuellBestellStatus);
    $stmt->execute();
}

$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');


// // Check if user has already placed an order this week
// $sql = "SELECT COUNT(*) FROM tbl_bestellung WHERE user_id = ? AND day_datum >= ? AND day_datum <= ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sss", $user_id, $week_start, $week_end);
// $stmt->execute();
// $result = $stmt->get_result();
// $order_count = $result->fetch_row()[0];

// if ($order_count > 0) {
//     // User has already placed an order this week
//     // Check if user's bestell_status is set to '1'
//     $sql = "SELECT bestell_status FROM tbl_user WHERE user_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $user_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $bestell_status = $result->fetch_row()[0];
    
//     if ($bestell_status == 1) {
//         // User's order has been processed, disable the button
//         echo "<script>document.getElementById('bestellen').disabled = true;</script>";
//     } else {
//         // User has not placed an order yet, enable the button
//         echo "<script>document.getElementById('bestellen').disabled = false;</script>";
//     }
// } else {
//     // User has not placed an order yet, enable the button
//     echo "<script>document.getElementById('bestellen').disabled = false;</script>";
// }

// $current_day = date('l'); // Liefert den aktuellen Wochentag in Textform (z.B. "Monday")

// if ($current_day == 'Saturday') {
//     // Aktiviere den Bestellbutton für alle Tage
//     $bestell_status = 0;
//     // Reset user's bestell_status at the start of every week
//     // Add this task to a weekly cron job
//     $sql = "UPDATE tbl_user SET bestell_status = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $bestell_status);
//     $stmt->execute();
//     echo "<script>document.getElementById('bestellen').disabled = false;</script>";
// }



    //um die ganze Bestellungen für den Benutzer abzurufen
    $bestellSql = "SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum, o.price
                    FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                    WHERE user_id = ?";
    $bestell_stmt = mysqli_prepare($conn, $bestellSql);
    mysqli_stmt_bind_param($bestell_stmt, "s", $user_id);
    mysqli_stmt_execute($bestell_stmt);
    $result = mysqli_stmt_get_result($bestell_stmt);
    

    $bestellungen = array();

    while ($row = $result->fetch_assoc()){
        $bestellungen[] = $row;
    }

    //um die letzte Woche Bestellung abzurufen
    $letzteBestell = "(SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum, o.price
                        FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                        WHERE user_id = ? ORDER BY id DESC LIMIT 5) ORDER BY id ASC";
    $letzte_bestell_stmt = mysqli_prepare($conn, $letzteBestell);
    mysqli_stmt_bind_param($letzte_bestell_stmt, "s", $user_id);
    mysqli_stmt_execute($letzte_bestell_stmt);
    $result = mysqli_stmt_get_result($letzte_bestell_stmt);

    $letzte_bestellungen = array();

    while ($row = $result->fetch_assoc()){
        $letzte_bestellungen[] = $row;
    }


    // SQL-Abfrage ausführen, um die Spalte zu summieren
    $query = "SELECT SUM(o.price) as total 
    FROM 
        ( SELECT b.option_id 
        FROM tbl_bestellung b 
        WHERE b.user_id = $user_id 
        ORDER BY b.bestelldatum 
        DESC LIMIT 5 ) as b 
    JOIN tbl_option o ON o.id = b.option_id;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalPreis = $row['total'];

    if(isset($_POST['button']) && $_POST['button'] == 'bestellen'){
        $sql = "INSERT INTO tbl_bank (auszahlung) VALUES ($totalPreis)";
        mysqli_query($conn, $sql);
    }

    // um die Bestell Status abzurufen und es im Button zu benutzen ob 1 ist, dann deaktiviert der Button
    $bestellStatusSql = "SELECT bestell_status FROM tbl_user WHERE id = $user_id";
    $statusBestell = mysqli_query($conn, $bestellStatusSql);
    $statusRow = mysqli_fetch_assoc($statusBestell);
    $bestell_status = $statusRow['bestell_status'];
    
    
?>


<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <div class="nav_besonder">
            <h4 class="mt-3 ms-3">Herzlich Willkommen </h4>
            <h4 class="mt-3 ms-3" style="color:red;"><?php echo $_SESSION['userName'];?></h4>
            <a class="nav-link ms-2 mt-1 btn-outline-danger" href="./u_logout.php"><h3>Abmelden</h3></a>
            <div >
                <h3 class="m-3">
                    <input style="font-weight: bold; color: green; width: 280px" 
                            class="text-center" id="system-time" name="system-time" value="<?php echo date('H:i:s'); ?>" readonly>
                </h3>
            </div>
            <div>
                <p class="m-3"  id="sessionTime" style="font-size:20px">Seite aktualisieren um Sitzung Zeit zu zeigen:
                    <span style="font-size:20px; font-weight:bold; color: green"><?php echo "$stunden:$minuten:$sekunden";?></span>
                </p>
            </div>

            

        </div>
        <div>
            <img class="logo" src="../images/logo.png" alt="Seeäkerschule Logo" width=6% style="float:right;">
        </div>
        
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'essenBestellung')">Essen Bestellung</button>
            <button class="tablinks" onclick="openTab(event, 'userData')">Benutzer Daten</button>
            <button class="tablinks" onclick="openTab(event, 'kontoZustand')">Konto Zustand</button>
            <button class="tablinks" onclick="openTab(event, 'bestellendeEssen')">Bestellende Essen</button>
        </div>

        <div id="essenBestellung" class="tabcontent" style="display:block">
            <h4>Essen bestellen</h4>
            <div class="col-lg-8" style="float:left; margin-top:100px;">
                <form id="bestellForm" action="u_user_page.php" method="post">
                    <?php foreach($days as $day) { ?>
                        <div class="mb-1" style="height:10vh">
                            <label for="option_name" style="width:115px; font-weight:bold"><?php echo $day;?>:</label>
                            <select class="w-50 h-50" name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>" onChange="chImage<?php echo $day;?>()">
                                <!-- <option></option> -->
                                <?php
                                    // Send query to database to get School Classes
                                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '" .$day."'";
                                    $result = mysqli_query($conn, $sql);
                                    // Include each result as an option tag in the drop-down list
                                    while($row = mysqli_fetch_assoc($result)){
                                        $option_name = $row['option_name'];
                                        $data = $row['data'];
                                        $price = $row['price'];
                                        $option_id = $row['id'];
                                        echo '<option value="' . $option_id . '">' . $option_name . "-" . $price . '</option>';
                                    }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-warning h-50 mb-2" name="button" value="<?php echo $day;?>" ><h6 style="color:white;">Ändern</h6></button>
                            <br>
                            <label style="width:100px" id="monday" name="monday">
                                <?php 
                                    $sql = "SELECT date FROM tbl_option WHERE day = '". $day."'";
                                    $result = mysqli_query($conn, $sql);
                                    $row = mysqli_fetch_assoc($result);
                                    echo $row['date'];
                                ?>
                            </label>
                        </div>
                    <?php } ?>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-25 btn-bestellen" id="bestellen" name="button" value="bestellen" 
                                <?php if($bestell_status == 1){echo "disabled";}else{echo "undisabled";}?>>
                                Essen bestellen
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4" style="float:left; box-shadow: -4px 1px 4px #888; height:100vh; margin-top:10px">
                <div id="imageContainer1">
                    
                </div>
                <div id="imageContainer2">

                </div>
                <div id="imageContainer3" >

                </div>
                <div id="imageContainer4">

                </div>
                <div id="imageContainer5">

                </div>
            </div>
        </div>
        
        <div id="userData" class="tabcontent">
            <h3>Meine Daten</h3>
            <p>Das ist der Inhalt von Tab 1.</p>
                
        </div>

        <div id="kontoZustand" class="tabcontent">
            <h1>Bank Account Simulation</h1>

            <p>Ihr Kontostand ist: <?php echo number_format(($_SESSION['balance']) - 500); ?>€</p>

            <!-- <h2>Geld einzahlen <span style="font-size:20px; color:red">(Dieses Feld muss in Admin Seite scheinen und von hier ausblenden)</span></h2>
            <form method="post">
                <label for="deposit_amount">Betrag:</label>
                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount">
                <button type="submit">einzahlen</button>
            </form> -->

            <h2>Geld auszahlen <span style="font-size:20px; color:red">(Dieses Feld muss in Admin Seite scheinen und von hier ausblenden)</span></h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="withdrawal_amount">Betrag:</label>
                <input type="number" step="0.01" name="withdrawal_amount" id="withdrawal_amount">
                <button type="submit">auszahlen</button>
            </form>
        </div>
        
        <div id="bestellendeEssen" class="tabcontent">
            <div class="container">
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'lastWeek')">Nächste Woche</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'alleBestellungen')">Alle Bestellungen</button>
                </div>
            
                <div id="lastWeek" class="subtabcontent" style="display:block">
                    <div> 
                        <h3 class="m-3" style="float:left">Bestellende Essen für nächste Woche:</h3>
                        <h3 class="m-3" style="float:right">Benutzer-ID: <span style="color:red;"><?php echo "[". $user_id."]"?></span></h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                            <th>Bestell-ID</th>
                            <th>Gerichtsname</th>
                            <th>Preis</th>
                            <th>Gericht-ID</th>
                            <th>Der Tag</th>
                            <th>Datum des Tages</th>
                            <th>Bestell Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($letzte_bestellungen) > 0){
                                    foreach($letzte_bestellungen as $letzte_bestellung){
                                        echo '<tr>';
                                            echo '<td>'.$letzte_bestellung['id']. '</td>';
                                            echo '<td>'.$letzte_bestellung['option_name']. '</td>';
                                            echo '<td>'.$letzte_bestellung['price']. '€</td>';
                                            echo '<td>'.$letzte_bestellung['option_id']. '</td>';
                                            echo '<td>'.$letzte_bestellung['day'].'</td>';
                                            echo '<td>'.$letzte_bestellung['day_datum'].'</td>';
                                            echo '<td>'.$letzte_bestellung['bestelldatum'].'</td>';
                                        echo '</tr>';
                                    }
                                }else {
                                    echo '<h4 style="color:red; text-align:center;">Keine Bestellungen gefunden.</h4>';
                                }
                                // mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                    <h3 class="mt-3">
                        <?php
                            echo "Der Gesamtbetrag ist: ". $totalPreis. "€";
                        ?>
                    </h3>
                </div>
            
                <div id="alleBestellungen" class="subtabcontent" style="display:none">
                    <div>
                        <h3 class="m-3" style="float:left">Alle Bestellende Essen:</h3>
                        <h3 class="m-3" style="float:right">Benutzer-ID: <span style="color:red;"><?php echo "[". $user_id."]"?></span></h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                            <th>Bestell-ID</th>
                            <th>Gerichtsname</th>
                            <th>Preis</th>
                            <th>Gericht-ID</th>
                            <th>Der Tag</th>
                            <th>Datum des Tages</th>
                            <th>Bestell Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($bestellungen) > 0){
                                    foreach($bestellungen as $bestellung){
                                        echo '<tr>';
                                            echo '<td>'.$bestellung['id']. '</td>';
                                            echo '<td>'.$bestellung['option_name']. '</td>';
                                            echo '<td>'.$bestellung['price'].'€</td>';
                                            echo '<td>'.$bestellung['option_id']. '</td>';
                                            echo '<td>'.$bestellung['day'].'</td>';
                                            echo '<td>'.$bestellung['day_datum'].'</td>';
                                            echo '<td>'.$bestellung['bestelldatum'].'</td>';
                                        echo '</tr>';
                                    }
                                }else {
                                    echo '<h4 style="color:red; text-align:center;">Keine Bestellungen gefunden.</h4>';
                                }
                                mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
        
        <div style="margin-bottom: 80px">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
            
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
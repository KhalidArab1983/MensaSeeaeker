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
date_default_timezone_set("Europe/Berlin");

$week_count = date('W');



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



// include ('./u_kontoZustand.php');
include ('./bestell_insert.php');
include ('./bestell_update.php');
// include ('./total_preis.php');



$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
// $gesamtPreis = 0;
// global $gesamtPreis;

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
    $letzteBestell = "(SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.week_count, b.bestelldatum, o.price
                        FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                        WHERE user_id = ? AND b.week_count = ? ORDER BY id DESC LIMIT 5) ORDER BY id ASC";
    $letzte_bestell_stmt = mysqli_prepare($conn, $letzteBestell);
    mysqli_stmt_bind_param($letzte_bestell_stmt, "ss", $user_id, $week_count);
    mysqli_stmt_execute($letzte_bestell_stmt);
    $result = mysqli_stmt_get_result($letzte_bestell_stmt);
    
    $letzte_bestellungen = array();

    while ($row = $result->fetch_assoc()){
        $letzte_bestellungen[] = $row;
        
    }

    //SQL-Abfrage ausführen, um die Preise in der Spalte zu summieren
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



    // um der gesamte Betrag von Bestellungen in die Tabelle tbl_auszahlung hinzufügen
    // $current_time = date('Y-m-d H:i:s');
    if(isset($_POST['button']) && $_POST['button'] == 'bestellen'){
        $sql = "INSERT INTO tbl_auszahlung (auszahlung, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $totalPreis, $user_id);
        $stmt->execute();
    }

    // um der gesamte Betrag von Bestellungen aktualisieren, wenn der Benutzer während der Woche die Bestellungen ändert
    foreach ($days as $day) {
        if(isset($_POST['button']) && $_POST["button"] == $day){
            $sql = "UPDATE tbl_auszahlung SET auszahlung = ? WHERE user_id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $totalPreis, $user_id);
            $stmt->execute();
        }
    }
    // um die Bestell Status abzurufen und es im Button zu benutzen ob 1 ist, dann deaktiviert der Button
    $bestellStatusSql = "SELECT bestell_status FROM tbl_user WHERE id = $user_id";
    $statusBestell = mysqli_query($conn, $bestellStatusSql);
    $statusRow = mysqli_fetch_assoc($statusBestell);
    $bestell_status = $statusRow['bestell_status'];
    
    // um die Update Status für jeden Tag aus der Tabelle tbl_bestellstatus abzurufen und es im Button zu benutzen ob 1 ist, dann deaktiviert der Button
    $updateSql = "SELECT montag, dienstag, mittwoch, donnerstag, freitag FROM tbl_bestellstatus WHERE user_id = $user_id";
    $updateResult = mysqli_query($conn, $updateSql);
    $updateRow = mysqli_fetch_assoc($updateResult);
    $Montag = $updateRow['montag'];
    $Dienstag = $updateRow['dienstag'];
    $Mittwoch = $updateRow['mittwoch'];
    $Donnerstag = $updateRow['donnerstag'];
    $Freitag = $updateRow['freitag'];

    
    // Um die Einzahlungen mit Datums in der Tabelle anzeigen
    $einzahlungSql = "SELECT einzahlung, einzahlung_date FROM tbl_einzahlung WHERE user_id = $user_id";
    $result = mysqli_query($conn, $einzahlungSql);
    $einzahlungen = array();
    while($einzahlungRow = mysqli_fetch_assoc($result)){
        $einzahlungen[] = $einzahlungRow;
    }
    

    // Um die Auszahlungen mit Datums in der Tabelle anzeigen
    $auszahlungSql = "SELECT auszahlung, auszahlung_date FROM tbl_auszahlung WHERE user_id = $user_id";
    $result = mysqli_query($conn, $auszahlungSql);
    $auszahlungen = array();
    while($auszahlungRow = mysqli_fetch_assoc($result)){
        $auszahlungen[] = $auszahlungRow;
    }

    // Abfrage, um die Gesamte Einzahlungen anzugeben
    $kontoEinzahlSql = "SELECT SUM(einzahlung) AS einzahlung FROM tbl_einzahlung WHERE user_id = $user_id";
    $kontoEinzahlRes = mysqli_query($conn, $kontoEinzahlSql);
    $kontoEinzahlRow = mysqli_fetch_assoc($kontoEinzahlRes);
    $sumEinzahlung = $kontoEinzahlRow['einzahlung'];

    // // Abfrage, um die Gesamte Auszahlungen anzugeben
    $kontoAuszahlSql = "SELECT SUM(auszahlung) AS auszahlung FROM tbl_auszahlung WHERE user_id = $user_id";
    $kontoAuszahlRes = mysqli_query($conn, $kontoAuszahlSql);
    $kontoAuszahlRow = mysqli_fetch_assoc($kontoAuszahlRes);
    $sumAuszahlung = $kontoAuszahlRow['auszahlung'];

    // Kontostand zu berechnen
    $kontostand = $sumEinzahlung - $sumAuszahlung;
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
            <button class="tablinks" onclick="openTab(event, 'kontoZustand')">Kontostand</button>
            <button class="tablinks" onclick="openTab(event, 'bestellendeEssen')">Bestellende Essen</button>
        </div>

        <div id="essenBestellung" class="tabcontent" style="display:block">
            <h4>Essen bestellen</h4>
            <div class="col-lg-8" style="float:left; margin-top:100px;">
                <form id="bestellForm" action="u_user_page.php" method="POST">
                    <?php 
                        $gesamtPreis = 0;
                        $price = 0;
                        global $price;
                        global $gesamtPreis;
                        foreach($days as $day): ?>
                            <div class="mb-1" style="height:10vh">
                                <label for="option_name_<?php echo $day; ?>" style="width:115px; font-weight:bold"><?php echo $day;?>:</label>
                                <select class="w-50 h-50" name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>"  onChange="chImage<?php echo $day;?>()">
                                    <!-- <option></option> -->
                                    <?php 
                                        if($$day == 1){
                                            // Send query to database to get School Classes
                                            $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE price = 0.00 AND  day = '" .$day."'";
                                            $result = mysqli_query($conn, $sql);
                                            // Include each result as an option tag in the drop-down list
                                            $row = mysqli_fetch_assoc($result);
                                            $option_name = $row['option_name'];
                                            $data = $row['data'];
                                            $price = $row['price'];
                                            $option_id = $row['id'];
                                            echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';  
                                        }else{
                                            // Send query to database to get meals option
                                            $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '" .$day."'";
                                            $result = mysqli_query($conn, $sql);
                                            // Include each result as an option tag in the drop-down list
                                            while($row = mysqli_fetch_assoc($result)){
                                                $option_name = $row['option_name'];
                                                $data = $row['data'];
                                                $price = $row['price'];
                                                $option_id = $row['id'];
                                                echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                                                
                                            }
                                        }
                                        
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-warning h-50 mb-2" name="button" id="<?php echo $day;?>" value="<?php echo $day;?>"
                                        <?php 
                                            if($$day == 1){ echo "disabled";}
                                            // elseif($totalPreis > $kontostand){echo 'style="cursor: none; pointer-events: none;"';} 
                                        ?> >
                                        <h6 style="color:white;">Ändern</h6>
                                </button>
                                <br>
                                <label style="width:100px" id="monday" name="<?php echo $day; ?>">
                                    <?php 
                                        $sql = "SELECT date FROM tbl_option WHERE day = '". $day."'";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result);
                                        $day_datum = $row['date'];
                                        echo $day_datum;
                                    ?>
                                </label>
                            </div>
                    <?php endforeach; ?>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-25 btn-bestellen" id="bestellen" name="button" value="bestellen" onclick="unreichendeKontostand()"
                                <?php 
                                    if($bestell_status == 1){echo "disabled";}
                                ?>>
                                Essen bestellen
                        </button>
                    </div>
                    <?php
                        
                        echo $gesamtPreis;
                        var_dump($gesamtPreis);
                    ?>
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
            <h1>Banktransaktionen und Kontostand</h1>
            <div class="container">                        
                <h2 class="text-center">Ihr Kontostand ist: 
                    <?php
                        if ($kontostand < 40 && $kontostand > 18){
                            echo '<span style="color:orange; font-weight:bold">';
                                echo $kontostand; 
                            echo '€</span>';
                        }elseif($kontostand < 18){
                            echo '<span style="color:red; font-weight:bold">';
                                echo $kontostand; 
                            echo '€</span>';
                        }else{
                            echo '<span style="color:green; font-weight:bold">';
                                echo $kontostand; 
                            echo '€</span>';
                        }
                        
                    ?>
                </h2>
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'einzahlungen')">Einzahlungen</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'auszahlungen')">Auszahlungen</button>
                </div>  
                <div id="einzahlungen" class="subtabcontent">
                    <h3 class="mt-3">
                        <?php
                            echo "Die Gesamte Einzahlungen sind: ". $sumEinzahlung. "€";
                        ?>
                    </h3>
                    <table>
                        <thead>
                            <tr>
                            <th>Einzahlungen</th>
                            <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($einzahlungen) > 0){
                                    foreach($einzahlungen as $einzahl){
                                        echo '<tr>';
                                            echo '<td>'.$einzahl['einzahlung'].'€</td>';
                                            echo '<td>'.$einzahl['einzahlung_date'].'</td>';
                                        echo '</tr>';
                                        // mysqli_close($conn);
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="auszahlungen" class="subtabcontent" style="display:none">
                    <h3 class="mt-3">
                        <?php
                            echo "Die Gesamte Auszahlungen sind: ". $sumAuszahlung. "€";
                        ?>
                    </h3>
                    <table>
                        <thead>
                            <tr>
                            <th>Auszahlungen</th>
                            <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($auszahlungen) > 0){
                                    foreach($auszahlungen as $auszahl){
                                        echo '<tr>';
                                            echo '<td>'.$auszahl['auszahlung'].'€</td>';
                                            echo '<td>'.$auszahl['auszahlung_date'].'</td>';
                                        echo '</tr>';
                                        // mysqli_close($conn);
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div id="bestellendeEssen" class="tabcontent">
            <div class="container">
                <button onclick="printContent()">Seite drucken</button>
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'lastWeek')">Nächste Woche</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'alleBestellungen')">Alle Bestellungen</button>
                </div>
            
                <div id="lastWeek" class="subtabcontent">
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
                                            echo '<td>'.$letzte_bestellung['id'].'</td>';
                                            echo '<td>'.$letzte_bestellung['option_name'].'</td>';
                                            echo '<td>'.$letzte_bestellung['price']. '€</td>';
                                            echo '<td>'.$letzte_bestellung['option_id']. '</td>';
                                            echo '<td>'.$letzte_bestellung['day'].'</td>';
                                            echo '<td>'.$letzte_bestellung['day_datum'].'</td>';
                                            echo '<td>'.$letzte_bestellung['bestelldatum'].'</td>';
                                        echo '</tr>';
                                    }
                                }else {
                                    echo '<tr>';
                                        echo '<td><h5 style="color:red; text-align:center;">Keine Bestellungen für diese Woche gefunden.</h5></td>';
                                    echo '</tr>';
                                }
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
            
        <script>
            function printContent() {
                var content = document.getElementById("lastWeek");
                var pri = document.createElement("iframe");
                pri.style.visibility = "hidden";
                pri.style.position = "absolute";
                pri.style.top = "0";
                pri.style.left = "0";
                document.body.appendChild(pri);
                pri.contentWindow.document.open();
                pri.contentWindow.document.write(content.innerHTML);
                pri.contentWindow.document.close();
                pri.contentWindow.focus();
                pri.contentWindow.print();
            }


            if(kontostand < 25){
                alert('Ihr Guthaben ist sehr niedrig, bitte bald aufladen');
            }



            var kontostand = "<?php echo $kontostand; ?>";

            function unreichendeKontostand(event){
                if(totalPreis > kontostand){
                    event.preventDefault();
                    alert('Das Guthaben reicht nicht aus, um den Kauf abzuschließen');
                }
            }
            var btnBestellen = document.getElementById("bestellen");
            btnBestellen.addEventListener("click", unreichendeKontostand(event));

        </script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
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



// Um die Session Dauer zuzeigen
if(!isset($_SESSION['startzeit'])){
    $_SESSION['startzeit'] = time();
}
$vergangene_zeit = (time() - $_SESSION['startzeit']) / 60;
$formatierte_zeit = gmdate("H:i:s", $vergangene_zeit * 60);


$errors = [
    'currentPassError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => ''
];

$current_password = "";
$new_password = "";
$confirm_password = "";

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['adresse'])) {
        $adresseP = $_POST['adresse'];
    }
    if (isset($_POST['plz'])) {
        $plzP = $_POST['plz'];
    }
    if (isset($_POST['ort'])) {
        $ortP = $_POST['ort'];
    }
    if (isset($_POST['ortsteil'])) {
        $ortsteilP = $_POST['ortsteil'];
    }
    if (isset($_POST['phone'])) {
        $phoneP = $_POST['phone'];
    }
    if (isset($_POST['email'])) {
        $emailP = $_POST['email'];
    }
    if (isset($_POST['current_password'])) {
        $current_password = $_POST['current_password'];
    }
    if (isset($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
    }
    if (isset($_POST['confirm_password'])) {
        $confirm_password = $_POST['confirm_password'];
    }

    if(isset($_POST['passwordForm'])){
        if(empty($current_password)){
            $errors['currentPassError'] = "* Bitte geben Sie das aktuelles Passwort ein.";
        }
        if(empty($new_password)){
            $errors['newPassError'] = '* Bitte geben Sie das neues Passwort ein.';
        }
        if(empty($confirm_password)){
            $errors['confirmPassError'] = '* Bitte bestätigen Sie das neues Passwort.';
        }
        if(!array_filter($errors)){
            echo "success";
            // $errors['otherError'] = '* success';
            header("Location: u_user_page.php");
        }
    }
    if(isset($_POST['adresseForm'])){
        $sql = "UPDATE tbl_user SET plz = ?, email = ?, phone = ?, adresse = ?, ortsteil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $plzP, $emailP, $phoneP, $adresseP, $ortsteilP, $user_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: u_user_page.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        // mysqli_close($conn);
    }
}


// include ('./u_kontoZustand.php');
include ('./bestell_insert.php');
include ('./bestell_update.php');


$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');



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
    $sonntag = 'Sunday';
    $current_day_text = date('l');
    $woche_count = ($current_day_text == $sonntag)?$week_count = date('W') + 1 : $week_count = date('W'); 
    $letzteBestell = "(SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.week_count, b.bestelldatum, o.price
                        FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                        WHERE b.user_id = ? AND b.week_count = ? ORDER BY id DESC LIMIT 5) ORDER BY id ASC";
    $letzte_bestell_stmt = mysqli_prepare($conn, $letzteBestell);
    mysqli_stmt_bind_param($letzte_bestell_stmt, "ii", $user_id, $woche_count);
    mysqli_stmt_execute($letzte_bestell_stmt);
    $result = mysqli_stmt_get_result($letzte_bestell_stmt);
    $letzte_bestellungen = array();
    while ($row = $result->fetch_assoc()){
        $letzte_bestellungen[] = $row;
        
    }

    //SQL-Abfrage ausführen, um die Preise aus Datenbank in der Spalte zu summieren
    $query = "SELECT SUM(o.price) as total 
            FROM 
                (SELECT b.option_id 
                FROM tbl_bestellung b 
                WHERE b.user_id = $user_id 
                ORDER BY b.bestelldatum 
                DESC LIMIT 5 ) as b 
            JOIN tbl_option o ON o.id = b.option_id;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $gesamtPreis = $row['total'];



    // um der gesamte Betrag von Bestellungen in die Tabelle tbl_auszahlung hinzufügen
    if(isset($_POST['button']) && $_POST['button'] == 'bestellen'){
        $sql = "INSERT INTO tbl_auszahlung (auszahlung, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $gesamtPreis, $user_id);
        $stmt->execute();
    }

    // um der gesamte Betrag von Bestellungen aktualisieren, wenn der Benutzer während der Woche die Bestellungen ändert
    foreach ($days as $day) {
        if(isset($_POST['button']) && $_POST["button"] == $day){
            $sql = "UPDATE tbl_auszahlung SET auszahlung = ? WHERE user_id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $gesamtPreis, $user_id);
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





$sql = "SELECT * FROM tbl_user u  
        INNER JOIN tbl_ort o ON u.plz = o.plz
        WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$userName = $row['userName'];
$lastName = $row['lastName'];
$firstName = $row['firstName'];
$birthday = $row['birthday'];
$aktiv_ab = $row['aktiv_ab'];
$klasse = $row['klasse'];
$adresse = $row['adresse'];
$plz = $row['plz'];
$ort = $row['ort'];
$ortsteil = $row['ortsteil'];
$phone = $row['phone'];
$email = $row['email'];



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
                <p class="m-3"  id="startzeit" style="font-size:20px">Seite aktualisieren, um die Sitzungszeit anzuzeigen:
                    <span style="font-size:20px; font-weight:bold; color: green"><?php echo $formatierte_zeit; ?></span>
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
            <h3 style="text-decoration:underline">Essen bestellen:</h3>
            
            <div class="col-lg-8" style="float:left; margin-top:20px;">
                <div>
                    <div class="mb-3 text-center" style="display:flex; text-align:center">
                        <h2>Gesamt Preis: </h2>
                        <div class="ms-4" style="font-size:30px; font-weight:bold; color:blue" name="totalPrice" id="totalPrice">0.00€</div>
                    </div>
                    <form id="bestellForm" action="u_user_page.php" method="POST">
                        <?php foreach($days as $day): ?>
                                <div class="mb-1" style="height:10vh">
                                    <label for="option_name_<?php echo $day; ?>" style="width:115px; font-weight:bold"><?php echo $day;?>:</label>
                                    <select class="w-50 h-50" name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>" onChange="chImage<?php echo $day;?>(); calculateTotalPrice(this);">
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
                                                    $tag = $row['day'];
                                                    $price = $row['price'];
                                                    $option_id = $row['id'];
                                                    echo '<option value="' . $option_id. '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                                                    
                                                }
                                            }
                                            
                                        ?>
                                    </select>
                                    <button type="submit" class="btn btn-warning h-50 mb-2" name="button" id="<?php echo $day;?>" value="<?php echo $day;?>"
                                            <?php 
                                                if($$day == 1){ echo "disabled";}
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
                            <button type="submit" class="btn btn-primary w-25 btn-bestellen" id="bestellen" name="button" value="bestellen"
                                    <?php 
                                        // if($bestell_status == 1){echo "disabled";}
                                    ?>>
                                    Essen bestellen
                                    
                            </button>
                            <div name="guthaben" id="guthaben" class="error" ></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4" style="float:left; box-shadow: -4px 1px 4px #888; height:100vh; margin-top:10px">
                <div id="imageContainerMontag">
                    
                </div>
                <div id="imageContainerDienstag">

                </div>
                <div id="imageContainerMittwoch" >

                </div>
                <div id="imageContainerDonnerstag">

                </div>
                <div id="imageContainerFreitag">

                </div>
            </div>
        </div>

        <div id="userData" class="tabcontent">
            <h3 style="text-decoration:underline">Meine Daten:</h3>
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-sm-12 col-md-4 m-1">
                        <h4 class="mb-5">Allgemeine Daten:</h4>
                        <div class="form-group">
                            <label for="userName" style="font-weight:bold;">Benutzername:</label>
                            <input type="text" name="userName" id="userName" class="form-control" value="<?php echo $userName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="lastName" style="font-weight:bold;">Nachname:</label>
                            <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $lastName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="firstName" style="font-weight:bold;">Vorname:</label>
                            <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $firstName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="birthday" style="font-weight:bold;">Geburtsdatum:</label>
                            <input type="text" name="birthday" id="birthday" class="form-control" value="<?php echo $birthday; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="aktiv_ab" style="font-weight:bold;">Aktiv ab:</label>
                            <input type="text" name="aktiv_ab" id="aktiv_ab" class="form-control" value="<?php echo $aktiv_ab; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="klasse" style="font-weight:bold;">Klasse:</label>
                            <input type="text" name="klasse" id="klasse" class="form-control" value="<?php echo $klasse; ?>" readonly>
                        </div>
                        <p>* Die Daten in dieser Tabelle dienen nur zur Anzeige und können nicht geändert werden.</p>
                    </div>
                    <div class="card col-sm-12 col-md-3 m-1">
                        <h4 class="mb-5">Adresse:</h4>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">Adresse :</label>
                                <input type="text" name="adresse" id="adresse" class="form-control" value="<?php echo $adresse; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">PLZ :</label>
                                <input type="text" name="plz" id="plz" class="form-control" value="<?php echo $plz; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">Ort :</label>
                                <input type="text" name="ort" id="ort" class="form-control" value="<?php echo $ort; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">Ortsteil :</label>
                                <input type="text" name="ortsteil" id="ortsteil" class="form-control" value="<?php echo $ortsteil; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">Handy :</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo $phone; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:200px">Email-Adresse :</label>
                                <input type="text" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
                            </div>
                            <div class="form-group">                                    
                                <button type="submit" class="btn btn-warning m-2" name="adresseForm" value="save">Speichern</button>
                                <button type="reset" class="btn btn-warning m-2" name="button" value="cancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
                            </div>
                        </form>
                    </div>
                    <div class="card col-sm-12 col-md-4 m-1">
                        <h4 class="mb-5">Passwort:</h4>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:300px">bisheriges Passwort:</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" value="<?php echo $current_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['currentPassError'] ?></div>
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:300px">Neues Passwort:</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" value="<?php echo $new_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['newPassError'] ?></div>
                            </div>
                            <div class="form-group">                                    
                                <label style="font-weight:bold; width:300px">Neues Passwort bestätigen:</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['confirmPassError'] ?></div>
                            </div>
                            <div class="form-group">                                    
                                <button type="submit" class="btn btn-warning m-2" name="passwordForm" value="passSave">Speichern</button>
                                <button type="reset" class="btn btn-warning m-2" name="button" value="passCancel">Abrechen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="kontoZustand" class="tabcontent">
            <h3 style="text-decoration:underline">Banktransaktionen und Kontostand:</h3>
            <div class="container">                        
                <h2 class="text-center">Ihr Kontostand ist: 
                    <?php
                        if ($kontostand < 40 && $kontostand > 18){
                            echo '<span style="color:orange; font-weight:bold">';
                                echo number_format($kontostand, 2); 
                            echo '€</span>';
                        }elseif($kontostand < 18){
                            echo '<span style="color:red; font-weight:bold">';
                                echo number_format($kontostand, 2); 
                            echo '€</span>';
                        }else{
                            echo '<span style="color:green; font-weight:bold">';
                                echo number_format($kontostand, 2); 
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
                            echo "Der Gesamtbetrag ist: ". $gesamtPreis. "€";
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
            
            var btnMontag = document.getElementById('Montag');
            var btnDienstag = document.getElementById('Dienstag');
            var btnMittwoch = document.getElementById('Mittwoch');
            var btnDonnerstag = document.getElementById('Donnerstag');
            var btnFreitag = document.getElementById('Freitag');
            
            var kontostand = "<?php echo $kontostand; ?>";
            var btnBestellen = document.getElementById('bestellen');
            var guthabenError = document.getElementById('guthaben');
            var bestellStatus = "<?php echo $bestell_status; ?>"

            if(bestellStatus == 1){
                btnBestellen.disabled = true;
            }
            if(kontostand == 0){
                btnBestellen.disabled = true;
                btnBestellen.style.cursor = 'not-allowed';
                guthabenError.innerHTML = "Ihr aktuelles Guthaben ist 0.00€, bitte laden Sie es auf, um Ihren Einkauf abzuschließen.";
            }else{
                if(bestellStatus == 0){
                    btnBestellen.disabled = false;
                    btnBestellen.style.cursor = 'pointer';
                    guthabenError.innerHTML = "";
                }
            }


            // Define an object to store the prices for each day
            var prices = {
                Montag: 0,
                Dienstag: 0,
                Mittwoch: 0,
                Donnerstag: 0,
                Freitag: 0
            };
            // Define a function to calculate the total price based on the selected options
            function calculateTotalPrice() {
                totalPrice = 0;
                // Loop through each day
                for (var day in prices) {
                    // Get the selected option for this day
                    var selectList = document.getElementById("option_name_" + day);
                    var selectedOption = selectList.options[selectList.selectedIndex];
                    // Extract the price from the selected option
                    var price = parseFloat(selectedOption.text.split("-")[2].replace("€", ""));
                    // Update the price for this day in the prices object
                    prices[day] = price;
                    // Add the price to the total price
                    totalPrice += price;
                }
                // Update the total price display
                document.getElementById("totalPrice").innerText = totalPrice.toFixed(2) + "€";
                // Return the total price
                return totalPrice;
            }
            // Call the calculateTotalPrice function whenever a new option is selected
            var selectLists = document.querySelectorAll("select");
            for (var i = 0; i < selectLists.length; i++) {
                selectLists[i].addEventListener("change", function() {
                    totalPrice = calculateTotalPrice();
                    price = calculateTotalPrice();
                    // document.cookie = 'totalPrice='+totalPrice;

                    if(bestellStatus == 1){
                        btnBestellen.disabled = true;
                    }
                    if(totalPrice > kontostand){
                        btnBestellen.disabled = true;
                        btnBestellen.style = 'cursor: none; pointer-events: none;';
                        if(bestellStatus == 1){
                            guthabenError.innerHTML = "Das Guthaben reicht nicht aus, um den Kauf abzuschließen oder Bestellungen aktualisieren.";
                        }
                    }
                    else{
                        if(bestellStatus == 0){
                            btnBestellen.disabled = false;
                            btnBestellen.style = 'cursor: pointer; pointer-events: visible;';
                            guthabenError.innerHTML = "";
                        }
                    }
                    

                    

                    var statusMontag = "<?php echo $Montag; ?>";
                    var statusDienstag = "<?php echo $Dienstag; ?>";
                    var statusMittwoch = "<?php echo $Mittwoch; ?>";
                    var statusDonnerstag = "<?php echo $Donnerstag; ?>";
                    var statusFreitag = "<?php echo $Freitag; ?>";

                    // var days = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag"];
                    // var tage = [statusMontag, statusDienstag, statusMittwoch, statusDonnerstag, statusFreitag];
                    // for(var i = 0; i < days.length; i++){
                    //     for(var j = 0; j < tage.length; j++){
                    //         var btnUpdate = document.getElementById(days[i]);
                    //         var statusUpdate = tage[j];
                    //         if(statusUpdate == 1 || totalPrice > kontostand){
                    //             btnUpdate.disabled = true;
                    //         }
                    //         else{
                    //             if(statusUpdate == 0){
                    //                 btnUpdate.disabled = false;
                    //                 guthabenError.innerHTML = "";
                    //             }
                    //         }
                    //     }
                    // }
                        if(statusMontag == 1){
                            btnMontag.disabled = true;
                        }
                        if(totalPrice > kontostand){
                            btnMontag.disabled = true;
                        }else{
                            if(statusMontag == 0){
                                btnMontag.disabled = false;
                                guthabenError.innerHTML = "";
                            }
                        }


                        if(statusDienstag == 1){
                            btnDienstag.disabled = true;
                        }
                        if(totalPrice > kontostand){
                            btnDienstag.disabled = true;
                        }else{
                            if(statusDienstag == 0){
                                btnDienstag.disabled = false;
                                guthabenError.innerHTML = "";
                            }
                        }


                        if(statusMittwoch == 1){
                            btnMittwoch.disabled = true;
                        }
                        if(totalPrice > kontostand){
                            btnMittwoch.disabled = true;
                        }else{
                            if(statusMittwoch == 0){
                                btnMittwoch.disabled = false;
                                guthabenError.innerHTML = "";
                            }
                        }


                        if(statusDonnerstag == 1){
                            btnDonnerstag.disabled = true;
                        }
                        if(totalPrice > kontostand){
                            btnDonnerstag.disabled = true;
                        }else{
                            if(statusDonnerstag == 0){
                                btnDonnerstag.disabled = false;
                                guthabenError.innerHTML = "";
                            }
                        }


                        if(statusFreitag == 1){
                            btnFreitag.disabled = true;
                        }
                        if(totalPrice > kontostand){
                            btnFreitag.disabled = true;
                        }else{
                            if(statusFreitag == 0){
                                btnFreitag.disabled = false;
                                guthabenError.innerHTML = "";
                            }
                        }


                        // const days = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag"];

                        // days.forEach(day => {
                        // const btn = document.getElementById(day);
                        // const status = "<?php echo "$ + day + "; ?>";

                        // if (status == 1 || totalPrice > kontostand) {
                        //     btn.disabled = true;
                        // } else if (status == 0) {
                        //     btn.disabled = false;
                        //     guthabenError.innerHTML = "";
                        // }
                        // });

                });
            }

            if(kontostand < 25){
                alert('Ihr Guthaben ist sehr niedrig, bitte bald aufladen');
            }

        </script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
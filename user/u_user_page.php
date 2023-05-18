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
if($formatierte_zeit > '00:30:00'){
    header('Location: u_logout.php');
}


// Meine Daten Seite
$errors = [
    'currentPassError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => '',
    'passRegexError' => ''
];
$success = '';

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
        // Add password validation
        $password_pattern = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/';
        if(!preg_match($password_pattern, $new_password)){
            $errors['passRegexError'] = '* Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Kleinbuchstaben, einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten.';
        }
        if(empty($confirm_password)){
            $errors['confirmPassError'] = '* Bitte bestätigen Sie das neues Passwort.';
        }
        if(!array_filter($errors)){
            $current_password =  mysqli_real_escape_string($conn, $_POST['current_password']);
            $new_password =      mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirm_password =  mysqli_real_escape_string($conn, $_POST['confirm_password']);

            $currentPassHashed = hash('sha256', $current_password);
            $newPassHashed = hash('sha256', $new_password);

            $sql = "SELECT password FROM tbl_user WHERE id = $user_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $db_password = $row['password'];

            if($currentPassHashed == $db_password){
                if($new_password == $confirm_password){
                    $sql ="UPDATE tbl_user SET password = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $newPassHashed, $user_id);
                    if(mysqli_stmt_execute($stmt)){
                        $success = "Das Passwort wurde erfolgreich geändert.";
                        header("Location: u_user_page.php");
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                    }
                    
                }else{
                    $errors['otherError'] = "* Die Passwörter stimmen nicht überein!";
                }
            }else{
                $errors['otherError'] = "* Das aktuelles Passwort ist falsch";
            }
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

// Um Benutzerdaten abzurufen und in den Eingabefeldern anzuzeigen
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
// Ende Meine Daten Seite






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
                WHERE b.user_id = $user_id AND b.week_count = $woche_count
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
    $einzahlungSql = "SELECT einzahlung, einzahlung_date FROM tbl_einzahlung WHERE user_id = $user_id ORDER BY einzahlung_date DESC";
    $result = mysqli_query($conn, $einzahlungSql);
    $einzahlungen = array();
    while($einzahlungRow = mysqli_fetch_assoc($result)){
        $einzahlungen[] = $einzahlungRow;
    }
    

    // Um die Auszahlungen mit Datums in der Tabelle anzeigen
    $auszahlungSql = "SELECT auszahlung, auszahlung_date FROM tbl_auszahlung WHERE user_id = $user_id ORDER BY auszahlung_date DESC";
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <div class="nav_besonder">
            <div class="disFlex">
                <h4 class="mt-3 ms-3">Herzlich Willkommen: </h4>
                <h4 class="mt-3 ms-3 colorRed"><?php echo $_SESSION['userName'];?></h4>
            </div>
            
            <div class="disFlex">
                <a class="nav-link m-2 btn btn-warning" href="./u_logout.php"><h5>Abmelden</h5></a>
                <h3 class="m-3">
                    <input class="text-center watchDate" id="system-time" name="system-time" value="<?php echo date('H:i:s'); ?>" readonly>
                </h3>
            </div>
            <div>
                <p class="m-3 font20"  id="startzeit">Seite aktualisieren, um die Sitzungszeit anzuzeigen:
                    <span class="font20 fontBold colorGreen"><?php echo $formatierte_zeit; ?></span>
                </p>
            </div>
            
        </div>
        <div>
            <img class="logo floatRight" src="../images/logo.png" alt="Seeäckerschule Logo" width=6%>
        </div>
        
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'essenBestellung')">Essen Bestellung</button>
            <button class="tablinks" onclick="openTab(event, 'userData')">Benutzer Daten</button>
            <button class="tablinks" onclick="openTab(event, 'kontoZustand')">Kontostand</button>
            <button class="tablinks" onclick="openTab(event, 'bestellendeEssen')">Bestellende Essen</button>
            <div class="text-center">
                <label class="mt-2 font25 colorGreen">Kalender Woche - <?php echo $week_count; ?></label>
            </div>
        </div>

        <div id="essenBestellung" class="tabcontent disBlock">
            <h3 class="textDeco">Essen bestellen:</h3>
            
            <div class="col-ms-12 col-md-12 col-lg-8 mt-4 floatLeft">
                <div>
                    <div class="mb-3 text-center disFlex">
                        <h2>Gesamt Preis: </h2>
                        <div class="ms-4 font30 fontBold colorBlue" name="totalPrice" id="totalPrice">0.00€</div>
                    </div>
                    <form action="u_user_page.php" method="POST">
                        <?php include ('./u_user_page_includes/essenbestellung_inc.php'); ?>
                    </form>
                </div>
            </div>
            <div class="col-ms-12 col-md-12 col-lg-4 imageContainer">
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
            <h3 class="textDeco">Meine Daten:</h3>
            <div class="container-fluid">
                <div class="row">
                    <div class="card col-sm-12 col-md-12 col-lg-4 m-2">
                        <h4 class="mb-5">Allgemeine Daten:</h4>
                        <div class="form-group">
                            <label for="userName" class="fontBold">Benutzername:</label>
                            <input type="text" name="userName" id="userName" class="form-control tableRow" value="<?php echo $userName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="lastName" class="fontBold">Nachname:</label>
                            <input type="text" name="lastName" id="lastName" class="form-control tableRow" value="<?php echo $lastName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="firstName" class="fontBold">Vorname:</label>
                            <input type="text" name="firstName" id="firstName" class="form-control tableRow" value="<?php echo $firstName; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="birthday" class="fontBold">Geburtsdatum:</label>
                            <input type="text" name="birthday" id="birthday" class="form-control tableRow" value="<?php echo $birthday; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="aktiv_ab" class="fontBold">Aktiv ab:</label>
                            <input type="text" name="aktiv_ab" id="aktiv_ab" class="form-control tableRow" value="<?php echo $aktiv_ab; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="klasse" class="fontBold">Klasse:</label>
                            <input type="text" name="klasse" id="klasse" class="form-control tableRow" value="<?php echo $klasse; ?>" readonly>
                        </div>
                        <p class="para">* Die Daten in dieser Tabelle dienen nur zur Anzeige und können nicht geändert werden.</p>
                    </div>
                    <div class="card col-sm-12 col-md-12 col-lg-4 m-2">
                        <h4 class="mb-5">Adresse:</h4>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                            <div class="form-group">                                    
                                <label class="fontBold">Adresse :</label>
                                <input type="text" name="adresse" id="adresse" class="form-control tableRow" value="<?php echo $adresse; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">PLZ :</label>
                                <input type="text" name="plz" id="plz" class="form-control tableRow" value="<?php echo $plz; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Ort :</label>
                                <input type="text" name="ort" id="ort" class="form-control tableRow" value="<?php echo $ort; ?>" readonly>
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Ortsteil :</label>
                                <input type="text" name="ortsteil" id="ortsteil" class="form-control tableRow" value="<?php echo $ortsteil; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Handy :</label>
                                <input type="text" name="phone" id="phone" class="form-control tableRow" value="<?php echo $phone; ?>">
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Email-Adresse :</label>
                                <input type="text" name="email" id="email" class="form-control tableRow" value="<?php echo $email; ?>">
                            </div>
                            <p class="para">* Geben Sie die PLZ ein und das Ort wird automatisch geändert.</p>
                            <div class="form-group">                                    
                                <button type="submit" class="btn btn-warning m-2" name="adresseForm" value="save">Speichern</button>
                                <button type="reset" class="btn btn-warning m-2" name="button" value="cancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
                            </div>
                        </form>
                    </div>
                    <div class="card col-sm-12 col-md-12 col-lg-3 m-2">
                        <h4 class="mb-5">Passwort:</h4>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <div class="form-group">                                    
                                <label class="fontBold">bisheriges Passwort:</label>
                                <input type="password" name="current_password" id="current_password" class="form-control tableRow" value="<?php echo $current_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['currentPassError'] ?></div>
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Neues Passwort:</label>
                                <input type="password" name="new_password" id="new_password" class="form-control tableRow" value="<?php echo $new_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['newPassError'] ?></div>
                                <div class="form-text mb-3 error"><?php echo $errors['passRegexError'] ?></div>
                                
                            </div>
                            <div class="form-group">                                    
                                <label class="fontBold">Neues Passwort bestätigen:</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control tableRow" value="<?php echo $confirm_password; ?>">
                                <div class="form-text mb-3 error"><?php echo $errors['confirmPassError'] ?></div>
                                <div class="form-text mb-3 error"><?php echo $errors['otherError'] ?></div>
                            </div>
                            <div class="form-group">                                    
                                <button type="submit" class="btn btn-warning m-2" name="passwordForm" value="passSave">Speichern</button>
                                <button type="reset" class="btn btn-warning m-2" name="button" value="passCancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
                            </div>
                            <div class="form-text m-5 success"><?php echo $success ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="kontoZustand" class="tabcontent">
            <h3 class="textDeco">Banktransaktionen und Kontostand:</h3>
            <div class="container">                        
                <h2 class="text-center">Ihr Kontostand ist: 
                    <?php
                        if ($kontostand < 40 && $kontostand > 18){
                            echo '<span class="colorOrange fontBold">';
                                echo number_format($kontostand, 2); 
                            echo '€</span>';
                        }elseif($kontostand < 18){
                            echo '<span class="colorRed fontBold">';
                                echo number_format($kontostand, 2); 
                            echo '€</span>';
                        }else{
                            echo '<span class="colorGreen fontBold">';
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
                            echo "Die Gesamte Einzahlungen sind: "."<span class='colorBlue fontBold'>". $sumEinzahlung. "€</span>";
                        ?>
                    </h3>
                    <div class="scrollView500">
                        <table>
                            <thead class="topFix">
                                <tr>
                                    <th>Einzahlungen</th>
                                    <th>Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(count($einzahlungen) > 0){
                                        foreach($einzahlungen as $einzahl){
                                            echo '<tr class="tableRow">';
                                                echo '<td>'.$einzahl['einzahlung'].'€</td>';
                                                echo '<td>'.$einzahl['einzahlung_date'].'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="auszahlungen" class="subtabcontent disNone">
                    <h3 class="mt-3">
                        <?php
                            echo "Die Gesamte Auszahlungen sind: ". "<span class='colorRed fontBold'>" . $sumAuszahlung. "€</span>";
                        ?>
                    </h3>
                    <div class="scrollView500">
                        <table>
                            <thead class="topFix">
                                <tr>
                                <th>Auszahlungen</th>
                                <th>Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(count($auszahlungen) > 0){
                                        foreach($auszahlungen as $auszahl){
                                            echo '<tr class="tableRow">';
                                                echo '<td>'.$auszahl['auszahlung'].'€</td>';
                                                echo '<td>'.$auszahl['auszahlung_date'].'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="bestellendeEssen" class="tabcontent">
            <div class="container">
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'lastWeek')">Nächste Woche</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'alleBestellungen')">Alle Bestellungen</button>
                </div>
            
                <div id="lastWeek" class="subtabcontent disBlock">
                    <div> 
                        <h3 class="floatLeft">Bestellende Essen für nächste Woche:</h3>
                        <h3 class="text-center">Benutzer-ID: <span class="colorRed"><?php echo "[". $user_id."]"?></span></h3>
                    </div>
                    <div class="scrollView300">
                        <table>
                            <thead class="topFix">
                                <tr>
                                <th>Bestell-ID</th>
                                <th>Gerichtsname</th>
                                <th>Preis</th>
                                <th>Der Tag</th>
                                <th>Datum des Tages</th>
                                <th>Bestell Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(count($letzte_bestellungen) > 0){
                                        foreach($letzte_bestellungen as $letzte_bestellung){
                                            echo '<tr class="tableRow">';
                                                echo '<td>'.$letzte_bestellung['id'].'</td>';
                                                echo '<td>'.$letzte_bestellung['option_name'].'</td>';
                                                echo '<td>'.$letzte_bestellung['price']. '€</td>';
                                                echo '<td>'.$letzte_bestellung['day'].'</td>';
                                                echo '<td>'.$letzte_bestellung['day_datum'].'</td>';
                                                echo '<td>'.$letzte_bestellung['bestelldatum'].'</td>';
                                            echo '</tr>';
                                        }
                                    }else {
                                        echo '<tr>';
                                            echo '<td><h5 class="colorRed text-center">Keine Bestellungen für diese Woche gefunden.</h5></td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <h3 class="mt-3">
                        <?php
                            echo "Der Gesamtbetrag ist: ". "<span class='colorBlue fontBold'>" . $gesamtPreis. "€</span>";
                        ?>
                    </h3>
                </div>
            
                <div id="alleBestellungen" class="subtabcontent disNone">
                    <div>
                        <h3 class="floatLeft">Alle Bestellende Essen:</h3>
                        <h3 class="text-center">Benutzer-ID: <span class="colorRed"><?php echo "[". $user_id."]"?></span></h3>
                    </div>
                    <div class="scrollView700">
                        <table>
                            <thead class="topFix">
                                <tr>
                                <th>Bestell-ID</th>
                                <th>Gerichtsname</th>
                                <th>Preis</th>
                                <th>Der Tag</th>
                                <th>Datum des Tages</th>
                                <th>Bestell Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(count($bestellungen) > 0){
                                        foreach($bestellungen as $bestellung){
                                            echo '<tr class="tableRow">';
                                                echo '<td>'.$bestellung['id']. '</td>';
                                                echo '<td>'.$bestellung['option_name']. '</td>';
                                                echo '<td>'.$bestellung['price'].'€</td>';
                                                echo '<td>'.$bestellung['day'].'</td>';
                                                echo '<td>'.$bestellung['day_datum'].'</td>';
                                                echo '<td>'.$bestellung['bestelldatum'].'</td>';
                                            echo '</tr>';
                                        }
                                    }else {
                                        echo '<tr>';
                                            echo '<td><h5 class="colorRed text-center">Keine Bestellungen gefunden.</h5></td>';
                                        echo '</tr>';
                                    }
                                    mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
        
        <div class="spaceFooter">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
            
        <script>
            //um die Variablen global zu machen und dann in pric_validation.js Datei zu verwenden
            window.kontostand ="<?php echo $kontostand; ?>";
            window.bestellStatus = "<?php echo $bestell_status; ?>";
            window.statusMontag = "<?php echo $Montag; ?>";
            window.statusDienstag = "<?php echo $Dienstag; ?>";
            window.statusMittwoch = "<?php echo $Mittwoch; ?>";
            window.statusDonnerstag = "<?php echo $Donnerstag; ?>";
            window.statusFreitag = "<?php echo $Freitag; ?>";
        </script>
        <script src="../js/price_validation.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
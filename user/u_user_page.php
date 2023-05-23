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


include ('./function_includes/u_userdata_function_inc.php');
include ('./bestell_insert.php');
include ('./bestell_update.php');

$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');

include ('./function_includes/u_bestellungen_function_inc.php');
include ('./function_includes/u_lastweek_function_inc.php');
include ('./function_includes/totalprice_sum_inc.php');
include ('./function_includes/totalprice_insert_inc.php');
include ('./function_includes/totalprice_update_inc.php');







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
                <?php include ('./u_user_page_includes/u_user_data_inc.php');?>
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
                    <?php include ('./u_user_page_includes/u_kontostand_einzahlungen_inc.php'); ?>
                </div>
                <div id="auszahlungen" class="subtabcontent disNone">
                    <?php include ('./u_user_page_includes/u_kontostand_auszahlungen_inc.php'); ?>
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
                    <?php include ('./u_user_page_includes/u_lastweek_inc.php'); ?>
                </div>
            
                <div id="alleBestellungen" class="subtabcontent disNone">
                    <?php include ('./u_user_page_includes/u_allebestellungen_inc.php'); ?>
                </div>
            </div>
        </div>  
        
        <div class="spaceFooter">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
            
        <script>
            //um die Variablen global zu machen und dann in price_validation.js Datei zu verwenden
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
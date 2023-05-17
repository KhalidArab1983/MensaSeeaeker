<?php
include ('../conn/db_conn.php');


session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}

date_default_timezone_set("Europe/Berlin");

$sonntag = 'Sunday';
$current_day = date('l');
$week_count = date('W');



$userSql ="SELECT id FROM tbl_user";
$result = mysqli_query($conn, $userSql);
$userRow = mysqli_fetch_assoc($result);
$user_id = $userRow['id'];

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $einzahlungsbetrag = $_POST['einzahlungsbetrag'];
    $userEinzahlung = $_POST['userEinzahlung'];
    if(isset($_POST["button"]) && $_POST["button"] == "einzahlen"){
        $sql = "INSERT INTO tbl_einzahlung (einzahlung, user_id, admin_id) VALUES ($einzahlungsbetrag, $userEinzahlung, $admin_id)";
        if(mysqli_query($conn, $sql)){
            header("Location: a_user_page.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : "";
}else{
    $start_date = "";
    $end_date = "";
}


    

?>


<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <?php include ('./nav_includes/navbar.php') ?>
        <hr class="height5">

        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'bestellung')">Bestellungen</button>
            <button class="tablinks" onclick="openTab(event, 'kontoStand')">Kontostand</button>
            <button class="tablinks" onclick="openTab(event, 'userData')">Benutzer Daten</button>
        </div>
        <div id="bestellung" class="tabcontent disBlock">
            <div class="container">
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'userBestellung')">Bestellung des Benutzers</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'bestellungen')">Alle Bestellungen</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'lastWeekAllUser')">Nächste Woche</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'anzahlGerichte')">Gerichte Anzahl</button>
                </div>

                <div id="userBestellung" class="subtabcontent" >
                    <?php include ('./a_user_page_includes/user_bestellung_inc.php'); ?>
                </div>
                
                <div id="bestellungen" class="subtabcontent disNone">
                    <?php include ('./a_user_page_includes/bestellungen_inc.php'); ?>
                </div>

                <div id="lastWeekAllUser" class="subtabcontent disNone">
                    <?php include ('./a_user_page_includes/lastweekalluser_inc.php'); ?>
                </div>
                
                <div id="anzahlGerichte" class="subtabcontent disNone">
                    <?php include ('./a_user_page_includes/anzahlgerichte_inc.php'); ?>
                </div>
            </div>
        </div>
        <div id="kontoStand" class="tabcontent">
            <div class="container">
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'einzahlungen')">Einzahlungen</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'auszahlungen')">Auszahlungen</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'einzahlungenFiltern')">Einzahlungen des Benutzers</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'auszahlungenFiltern')">Auszahlungen des Benutzers</button>
                </div>
                <div id="einzahlungen" class="subtabcontent" > 
                    <?php include ('./a_user_page_includes/a_kontostand_einzahlungen_inc.php'); ?>
                </div>
                <div id="auszahlungen" class="subtabcontent disNone">            
                    <?php include ('./a_user_page_includes/a_kontostand_auszahlungen_inc.php'); ?>
                </div>
                <div id="einzahlungenFiltern" class="subtabcontent disNone">
                    <?php include ('./a_user_page_includes/a_kontostand_einzahlungfilter_inc.php'); ?>
                </div>
                <div id="auszahlungenFiltern" class="subtabcontent disNone">
                    <?php include ('./a_user_page_includes/a_kontostand_auszahlungfilter_inc.php'); ?>
                </div>
            </div>
        </div>
        <div id="userData" class="tabcontent">
            <?php include ('./a_user_page_includes/user_data_inc.php'); ?>
        </div>

        <div class="marginBottom80">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>


        <script>
            function filterOptionsEinzahlung() {
                var input = document.getElementById("userSearch");
                var filter = input.value.toUpperCase();
                var select = document.getElementById("userEinzahlung");
                var options = select.getElementsByTagName("option");
                for (var i = 0; i < options.length; i++) {
                    var optionText = options[i].text.toUpperCase();
                    if (optionText.indexOf(filter) > -1) {
                    options[i].style.display = "";
                    } else {
                    options[i].style.display = "none";
                    }
                }
            }

        </script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>

<?php
include ('../conn/db_conn.php');
// $current_date = date_format(new DateTime() ,'Y.m.d');
// // $result = date_format(new DateTime() ,'Y.m.d');
// echo $current_date;

session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}

date_default_timezone_set("Europe/Berlin");

$week_count = date('W');


$userSql ="SELECT id FROM tbl_user";
$result = mysqli_query($conn, $userSql);
$userRow = mysqli_fetch_assoc($result);
$user_id = $userRow['id'];




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
?>


<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="./a_user_page.php"><h6>Benutzer Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
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
                <h5 style="margin: 0;">Herzlich Willkommen <span style="color:green"><?php echo $_SESSION['adminName']; ?></span></h5>
            </div>
        </nav>
        <hr style="height: 5px">

        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'bestellung')">Bestellungen</button>
            <button class="tablinks" onclick="openTab(event, 'kontoStand')">Kontostand</button>
            <button class="tablinks" onclick="openTab(event, 'ddd')">ddd</button>
            <button class="tablinks" onclick="openTab(event, 'sss')">sss</button>
        </div>
        <div id="bestellung" class="tabcontent" style="display:block">
            <div class="container">
                <div class="tab">
                    <button class="subtablinks active" onclick="openSubTab(event, 'userBestellung')">Bestellung des Benutzers</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'bestellungen')">Alle Bestellungen</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'lastWeekAllUser')">Nächste Woche</button>
                    <button class="subtablinks" onclick="openSubTab(event, 'anzahlGerichte')">Gerichte Anzahl</button>
                </div>

                <div id="userBestellung" class="subtabcontent" >
                    <div class="container">
                        <form method="get" style="float:left; margin-right:40%">
                            <label for="user_id">Benutzername:</label>
                            <!-- <input type="text" name="user_id" id="user_id"> -->
                            <input type="text" name="userName" id="user_id"> 
                            <input type="submit" value="Suchen" >
                        </form>
                        <table>
                            <thead>
                                <tr>
                                <th>Bestell-ID</th>
                                <th>User-ID</th>
                                <th>User Name</th>
                                <th>Gerichtsname</th>
                                <th>GerichtID</th>
                                <th>Der Tag</th>
                                <th>Datum des Tages</th>
                                <th>Bestell Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                                    if (isset($_GET['userName'])) {
                                        // Benutzereingabe bereinigen
                                        $userName = trim(mysqli_real_escape_string($conn, $_GET['userName']));
                                        // Abrufen der Bestellungen für den angegebenen Benutzer
                                        $sql = "SELECT u.userName, b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                                FROM tbl_bestellung AS b LEFT JOIN tbl_user AS u ON u.id = b.user_id WHERE u.userName = '$userName' 
                                                ORDER BY b.bestelldatum DESC;";
                                        $result = mysqli_query($conn, $sql);
                                        if($result->num_rows > 0){
                                            // Ausgabe der Bestellungen in einer Tabelle
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                    echo '<td>' . $row['id'] . '</td>';
                                                    echo '<td>' . $row['user_id'] . '</td>';
                                                    echo '<td>' . $row['userName'] . '</td>';
                                                    echo '<td>' . $row['option_name'] . '</td>';
                                                    echo '<td>' . $row['option_id'] . '</td>';
                                                    echo '<td>' . $row['day'] . '</td>';
                                                    echo '<td>' . $row['day_datum'] . '</td>';
                                                    echo '<td>' . $row['bestelldatum'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }else{
                                            echo '<tr>';
                                                echo '<td>Keine Bestellungen für den Benutzer gefunden.</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="bestellungen" class="subtabcontent" style="display:none">
                    <div class="container">
                        <table>
                            <form method="POST">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="checkboxId" name="checkboxId">
                                            Bestell-ID
                                        </th>
                                        <th>User ID</th>
                                        <th>
                                            <input type="checkbox" id="checkboxUserName" name="checkboxUserName" >
                                            UserName
                                        </th>
                                        <th>Gerichtsname</th>
                                        <th>GerichtID</th>
                                        <th>Der Tag</th>
                                        <th>Datum des Tages</th>
                                        <th>
                                            <input type="checkbox" id="checkboxBestellDatum" name="checkboxBestellDatum" >
                                            Bestell Datum
                                        </th>
                                    </tr>
                                </thead>
                            </form>
                            <tbody>
                                <?php
                                    function isCheckboxChecked($checkboxName) {
                                        return isset($_POST[$checkboxName]) && $_POST[$checkboxName] == 'checked';
                                    }
                                    // Überprüfen, ob Checkboxen angeklickt wurden
                                    if (isCheckboxChecked('checkboxId')) {
                                        $sort_column = "b.id";
                                        $_POST['checkboxUserName'] = $isDisabled;
                                        $_POST['checkboxBestellDatum'] = $isDisabled;


                                    } elseif (isCheckboxChecked('checkboxUserName')) {
                                        $sort_column = "u.userName";
                                        $_POST['checkboxId'] = $isDisabled;
                                        $_POST['checkboxBestellDatum'] = $isDisabled;


                                    } elseif (isCheckboxChecked('checkboxBestellDatum')) {
                                        $sort_column = "b.bestelldatum";
                                        $_POST['checkboxId'] = $isDisabled;
                                        $_POST['checkboxUserName'] = $isDisabled;

                                        
                                    } else {
                                        $sort_column = "b.bestelldatum"; // Standard-Sortierspalte
                                    }
                                    // Abrufen aller Bestellungen aus der Datenbank
                                    $sql = "SELECT b.id, b.user_id, u.userName, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                            FROM tbl_bestellung AS b INNER JOIN tbl_user AS u ON u.id = b.user_id ORDER BY userName";
                                    $result = mysqli_query($conn, $sql);
                                    // Ausgabe der Bestellungen in einer Tabelle
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                            echo '<td>' . $row['id'] . '</td>';
                                            echo '<td>' . $row['user_id'] . '</td>';
                                            echo '<td>' . $row['userName'] . '</td>';
                                            echo '<td>' . $row['option_name'] . '</td>';
                                            echo '<td>' . $row['option_id'] . '</td>';
                                            echo '<td>' . $row['day'] . '</td>';
                                            echo '<td>' . $row['day_datum'] . '</td>';
                                            echo '<td>' . $row['bestelldatum'] . '</td>';
                                        echo '</tr>';
                                    }
                                    // mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="lastWeekAllUser" class="subtabcontent" style="display:none">
                    <div class="container">
                        <h3>die Bestellungen für alle Benutzer für nächste Woche:</h3>
                        <table>
                            <form method="POST">
                                <thead>
                                    <tr>
                                        <th>Bestell-ID</th>
                                        <!-- <th>User ID</th> -->
                                        <th>UserName</th>
                                        <th>Gerichtsname</th>
                                        <th>GerichtID</th>
                                        <th>Der Tag</th>
                                        <th>Datum des Tages</th>
                                        <th>Bestell Datum</th>
                                    </tr>
                                </thead>
                            </form>
                            <tbody>
                                <?php
                                    // Abrufen aller Bestellungen aus der Datenbank
                                    // $sql = "SELECT u.id, u.userName, b.id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum
                                    //         FROM tbl_user u JOIN (SELECT id, user_id, option_name, option_id, day, day_datum, bestelldatum
                                    //             FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num FROM tbl_bestellung) t
                                    //             WHERE t.row_num <= 5) b ON u.id = b.user_id ORDER BY b.bestelldatum DESC;";
                                    $sql = "SELECT u.id, u.userName, b.id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum
                                            FROM tbl_user u INNER JOIN tbl_bestellung b ON u.id = b.user_id
                                            WHERE b.week_count = $week_count";
                                    $result = mysqli_query($conn, $sql);
                                    // Ausgabe der Bestellungen in einer Tabelle
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                            echo '<td>' . $row['id'] . '</td>';
                                            // echo '<td>' . $row['user_id'] . '</td>';
                                            echo '<td>' . $row['userName'] . '</td>';
                                            echo '<td>' . $row['option_name'] . '</td>';
                                            echo '<td>' . $row['option_id'] . '</td>';
                                            echo '<td>' . $row['day'] . '</td>';
                                            echo '<td>' . $row['day_datum'] . '</td>';
                                            echo '<td>' . $row['bestelldatum'] . '</td>';
                                        echo '</tr>';
                                    }
                                    // mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="anzahlGerichte" class="subtabcontent" style="display:none">
                    <div class="container">
                        <h3>Anzahl der Bestellungen für nächste Woche:</h3>
                        <table>
                            <form method="POST">
                                <thead>
                                    <tr>
                                        <th>Option ID</th>
                                        <th>Gerichtsname</th>
                                        <th>Anzahl</th>
                                    </tr>
                                </thead>
                            </form>
                            <tbody>
                                <?php
                                    // Abrufen aller Bestellungen aus der Datenbank
                                    // $sql = "SELECT b.option_id, b.option_name, COUNT(*) AS anzahl
                                    //         FROM (
                                    //             SELECT option_id, user_id, option_name, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num
                                    //             FROM tbl_bestellung) AS b WHERE b.row_num <= 5 GROUP BY b.option_name ORDER BY `b`.`option_name` ASC;";

                                    $sql = "SELECT option_id, option_name, COUNT(*) AS anzahl
                                            FROM tbl_bestellung WHERE week_count = $week_count GROUP BY option_name ORDER BY option_name ASC";
                                    $result = mysqli_query($conn, $sql);
                                    // Ausgabe der Bestellungen in einer Tabelle
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                            echo '<td>' . $row['option_id'] . '</td>';
                                            echo '<td>' . $row['option_name'] . '</td>';
                                            echo '<td>' . $row['anzahl'] . '</td>';
                                        echo '</tr>';
                                    }
                                    // mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
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
                    <form method="post" class="col-12">
                        <div class="m-1 col-4" style="float:left">
                            <input type="number" class="form-control" style="float:left" step="0.01" name="einzahlungsbetrag" id="einzahlungsbetrag" placeholder="Der zu zahlende Betrag">
                        </div>
                        <div class="m-1 col-4" style="float:left">
                            <input type="text" name="userName" class="form-control" id="searchInput" onkeyup="filterOptions()" placeholder="nach einem Benutzer suchen...">
                        </div>
                        <div class="m-2 col-8">
                            <select class="form-control" name="userEinzahlung" id="userEinzahlung">
                                <option>Benutzer Name auswählen...</option>
                                <?php 
                                    $sql = "SELECT id, userName FROM tbl_user";
                                    $result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $user_id = $row['id'];
                                        $userName = $row['userName'];
                                        echo '<option value="' . $user_id . '">' . $user_id . "-" . $userName . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="button" class="btn btn-warning m-1" value="einzahlen">Einzahlen</button>
                        <!-- <button type="submit" name="button" class="btn btn-warning" value="filtern">Einzahlungen nach Benutzer filtern</button> -->
                    </form>
                    <h3>Einzahlungen</h3>
                    <table>
                        <form method="POST">
                            <thead>
                                <tr>
                                    <th>Benutzer Name</th>
                                    <th>Einzahlungen</th>
                                    <th>Einzahlungsdatum</th>
                                </tr>
                            </thead>
                        </form>
                        <tbody>
                            <?php
                                // Abrufen aller Bestellungen aus der Datenbank
                                $sql = "SELECT u.userName, e.einzahlung, e.einzahlung_date FROM tbl_einzahlung e
                                        INNER JOIN tbl_user u ON u.id = e.user_id";
                                $result = mysqli_query($conn, $sql);
                                // Ausgabe der Bestellungen in einer Tabelle
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                        echo '<td>' . $row['userName'] . '</td>';
                                        echo '<td>' . $row['einzahlung'] . '€</td>';
                                        echo '<td>' . $row['einzahlung_date'] . '</td>';
                                    echo '</tr>';
                                }
                                // mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="auszahlungen" class="subtabcontent" style="display:none">            
                    <h3>Auszahlungen</h3>
                    <table>
                        <form method="POST">
                            <thead>
                                <tr>
                                    <th>Benutzer Name</th>
                                    <th>Auszahlungen</th>
                                    <th>Bestellungsdatum</th>
                                </tr>
                            </thead>
                        </form>
                        <tbody>
                            <?php
                                // Abrufen aller Bestellungen aus der Datenbank
                                $sql = "SELECT u.userName, a.auszahlung, a.auszahlung_date FROM tbl_auszahlung a
                                        INNER JOIN tbl_user u ON u.id = a.user_id";
                                $result = mysqli_query($conn, $sql);
                                // Ausgabe der Bestellungen in einer Tabelle
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                        echo '<td>' . $row['userName'] . '</td>';
                                        echo '<td>' . $row['auszahlung'] . '€</td>';
                                        echo '<td>' . $row['auszahlung_date'] . '</td>';
                                    echo '</tr>';
                                }
                                // mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="einzahlungenFiltern" class="subtabcontent" style="display:none">
                    <div class="container">
                        <h3>Einzahlungen für einzelnen Benutzer</h3>
                        <form method="get" style="float:left; margin-right:40%">
                            <label for="user_id">Benutzername:</label>
                            <input type="text" name="userName" id="user_id"> 
                            <input type="submit" value="Suchen" >
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Benutzer Name</th>
                                    <th>Einzahlungen</th>
                                    <th>Einzahlungsdatum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                                    if (isset($_GET['userName'])) {
                                        // Benutzereingabe bereinigen
                                        $userName = trim(mysqli_real_escape_string($conn, $_GET['userName']));
                                        // Abrufen der Bestellungen für den angegebenen Benutzer
                                        $sql = "SELECT u.userName, e.einzahlung, e.einzahlung_date FROM tbl_einzahlung e
                                                LEFT JOIN tbl_user u ON u.id = e.user_id WHERE u.userName = '$userName'
                                                ORDER BY e.einzahlung_date DESC";
                                        $result = mysqli_query($conn, $sql);
                                        if($result->num_rows > 0){
                                            // Ausgabe der Bestellungen in einer Tabelle
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                    echo '<td>' . $row['userName'] . '</td>';
                                                    echo '<td>' . $row['einzahlung'] . '€</td>';
                                                    echo '<td>' . $row['einzahlung_date'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }else{
                                            echo '<tr>';
                                                echo '<td>Keine Einzahlungen für den Benutzer gefunden.</td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="auszahlungenFiltern" class="subtabcontent" style="display:none">
                    <div class="container">
                        <h3>Auszahlungen für einzelnen Benutzer</h3>
                        <form method="get" style="float:left; margin-right:40%">
                            <label for="user_id">Benutzername:</label>
                            <input type="text" name="userName" id="user_id"> 
                            <input type="submit" value="Suchen" >
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th>Benutzer Name</th>
                                    <th>Einzahlungen</th>
                                    <th>Einzahlungsdatum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                                    if (isset($_GET['userName'])) {
                                        // Benutzereingabe bereinigen
                                        $userName = trim(mysqli_real_escape_string($conn, $_GET['userName']));
                                        // Abrufen der Bestellungen für den angegebenen Benutzer
                                        $sql = "SELECT u.userName, a.auszahlung, a.auszahlung_date FROM tbl_auszahlung a
                                                LEFT JOIN tbl_user u ON u.id = a.user_id WHERE u.userName = '$userName'
                                                ORDER BY a.auszahlung_date DESC";
                                        $result = mysqli_query($conn, $sql);
                                        if($result->num_rows > 0){
                                            // Ausgabe der Bestellungen in einer Tabelle
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                    echo '<td>' . $row['userName'] . '</td>';
                                                    echo '<td>' . $row['auszahlung'] . '€</td>';
                                                    echo '<td>' . $row['auszahlung_date'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }else{
                                            echo '<tr>';
                                                echo '<td>Keine Auszahlungen für den Benutzer gefunden.</td>';
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
        <div id="ddd" class="tabcontent">
            dddddddddddddd
        </div>
        <div id="sss" class="tabcontent">
            sssssssssssss
        </div>


        <div style="margin-bottom: 80px">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>


        <script>
            function filterOptions() {
                var input = document.getElementById("searchInput");
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

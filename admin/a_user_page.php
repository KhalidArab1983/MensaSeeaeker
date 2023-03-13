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


if(isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
}

$isChecked = 'checked';
$isDisabled ='disabled'; 
?>


<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
        <style>
            
            /* CSS für die Tabs
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
                margin-top: 10px;
            }

            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                transition: 0.3s;
            }

            .tab button.active {
                background-color: #ccc;
            }
            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            } */
        </style>
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
            <button class="tablinks active" onclick="openTab(event, 'userBestellung')">Bestellung des Benutzers</button>
            <button class="tablinks" onclick="openTab(event, 'bestellungen')">Alle Bestellungen</button>
            <button class="tablinks" onclick="openTab(event, 'lastWeekAllUser')">Nächste Woche</button>
            <button class="tablinks" onclick="openTab(event, 'anzahlGerichte')">Gerichte Anzahl</button>
        </div>

        <div id="userBestellung" class="tabcontent" style="display:block">
            <div class="container">
                <form method="get">
                    <label for="user_id">Benutzername:</label>
                    <!-- <input type="text" name="user_id" id="user_id"> -->
                    <input type="text" name="userName" id="user_id"> 
                    <input type="submit" value="Suchen">
                </form>
                <table>
                    <thead>
                        <tr>
                        <th>Bestell-ID</th>
                        <th>User ID</th>
                        <th>UserName</th>
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
                                $userName = mysqli_real_escape_string($conn, $_GET['userName']);
                                // Abrufen der Bestellungen für den angegebenen Benutzer
                                $sql = "SELECT u.userName, b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                        FROM tbl_bestellung AS b INNER JOIN tbl_user AS u ON u.id = b.user_id WHERE u.userName = '$userName' 
                                        ORDER BY b.bestelldatum DESC;";
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
                            }else{
                                echo '<tr>';
                                    echo '<td>Keine Bestellungen für den Benutzer gefunden.</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="bestellungen" class="tabcontent">
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
                                    FROM tbl_bestellung AS b INNER JOIN tbl_user AS u ON u.id = b.user_id ORDER BY $sort_column";
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

        <div id="lastWeekAllUser" class="tabcontent">
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
                            $sql = "SELECT u.id, u.userName, b.id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum
                                    FROM tbl_user u
                                    JOIN (
                                        SELECT id, user_id, option_name, option_id, day, day_datum, bestelldatum
                                        FROM (
                                            SELECT *, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num
                                            FROM tbl_bestellung
                                        ) t
                                        WHERE t.row_num <= 5
                                    ) b ON u.id = b.user_id
                                    ORDER BY b.bestelldatum DESC;";
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
        
        <div id="anzahlGerichte" class="tabcontent">
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
                            $sql = "SELECT b.option_id, b.option_name, COUNT(*) AS anzahl
                                    FROM (
                                        SELECT option_id, user_id, option_name, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num
                                        FROM tbl_bestellung
                                        ) AS b
                                    WHERE b.row_num <= 5
                                    GROUP BY b.option_name  
                                    ORDER BY `b`.`option_name` ASC;";
                            $result = mysqli_query($conn, $sql);
                            // Ausgabe der Bestellungen in einer Tabelle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                    echo '<td>' . $row['option_id'] . '</td>';
                                    echo '<td>' . $row['option_name'] . '</td>';
                                    echo '<td>' . $row['anzahl'] . '</td>';
                                echo '</tr>';
                            }
                            mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
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

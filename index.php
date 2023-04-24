<?php

include('./conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}
date_default_timezone_set("Europe/Berlin");


$adminUpdateSql = "SELECT a.adminName, u.userName, u.klasse, u.plz, u.email, u.phone, u.adresse, u.ortsteil,
IF(u.userName = old.userName, 'Old', '') AS userName_status,
IF(u.klasse = old.klasse, 'Old', '') AS klasse_status,
IF(u.plz = old.plz, 'Old', '') AS plz_status,
IF(u.email <> old.email, 'Old', '') AS email_status,
IF(u.phone <> old.phone, 'Old', '') AS phone_status,
IF(u.adresse = old.adresse, 'Old', '') AS adresse_status,
IF(u.ortsteil = old.ortsteil, 'Old', '') AS ortsteil_status
FROM tbl_user u
INNER JOIN tbl_admin a ON u.admin_id_update = a.id
LEFT JOIN tbl_user AS old ON u.id = old.id;";
$result = mysqli_query($conn, $adminUpdateSql);
$aenderungen = array();
while($row = $result->fetch_assoc()){
    $aenderungen[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
	<title>Mensa</title>
</head>
<body>
    
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-light p-4 w-100" style="display:inline-block;">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                    <li class="nav-item item_besonder">
                        <a class="nav-link active" href="./index.php"><h6>Haupt Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/create_user.php"><h6>Neu Benutzer |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/a_user_page.php"><h6>Benutzer Seite |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
                    </li>

                    <li class="nav-item item_besonder">
                        <a class="nav-link" href="./admin/a_logout.php"><h6>Abmelden</h6></a>
                    </li>
                </ul>
            </div>
        </div>

        <img class="logo" src="./images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
        <nav class="navbar navbar-dark bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h5 style="margin: 0;">Herzlich Willkommen <span style="color:green"><?php echo $_SESSION['adminName']; ?></span></h5>
            </div>
        </nav>

        <hr style="height: 5px">
        <div class="container-fluid">
            <div style="overflow: auto; height: 400px;">
            <table>
                <thead>
                    <tr>
                    <th>Admin Name</th>
                    <th>User Name</th>
                    <th>User Name Status</th>
                    <th>Klasse</th>
                    <th>Klasse Status</th>
                    <th>PLZ</th>
                    <th>PLZ Status</th>
                    <th>email</th>
                    <th>email Status</th>
                    <th>Phone</th>
                    <th>Phone Status</th>
                    <th>Adresse</th>
                    <th>Adresse Status</th>
                    <th>Ortsteil</th>
                    <th>Ortsteil Status</th>
                    <!-- <th>Update By Admin</th> -->
                    </tr>
                </thead>
                <tbody>
                    
                    <?php 
                        if(count($aenderungen) > 0){
                            foreach($aenderungen as $update){
                                echo "<tr>";                        
                                    echo '<td>' . $update["adminName"] . '</td>';
                                    echo '<td>' . $update["userName"] . '</td>';
                                    echo '<td>' . $update["userName_status"] . '</td>';
                                    echo '<td>' . $update["klasse"] . '</td>';
                                    echo '<td>' . $update["klasse_status"] . '</td>';
                                    echo '<td>' . $update["plz"] . '</td>';
                                    echo '<td>' . $update["plz_status"] . '</td>';
                                    echo '<td>' . $update["email"] . '</td>';
                                    echo '<td>' . $update["email_status"] . '</td>';
                                    echo '<td>' . $update["phone"] . '</td>';
                                    echo '<td>' . $update["phone_status"] . '</td>';
                                    echo '<td>' . $update["adresse"] . '</td>';
                                    echo '<td>' . $update["adresse_status"] . '</td>';
                                    echo '<td>' . $update["ortsteil"] . '</td>';
                                    echo '<td>' . $update["ortsteil_status"] . '</td>';
                                    // echo '<td>' . $update["admin_id_update"] . '</td>';
                                echo "</tr>";
                            }
                        }else {
                            echo '<tr>';
                                echo '<td><h5 class="colorRed text-center">Keine Änderungen gefunden.</h5></td>';
                            echo '</tr>';
                        }
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
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>


<!-- // Speichern der ursprünglichen Benutzerdaten in einer Variable
$query = "SELECT * FROM tbl_user WHERE id = $user_id";
$result = mysqli_query($connection, $query);
$original_data = mysqli_fetch_assoc($result);

// Überprüfen, welche Felder geändert wurden und sie in der Tabelle tbl_user_changes speichern
foreach ($_POST as $key => $value) {
    if ($key != "user_id" && $key != "submit") {
        if ($value != $original_data[$key]) {
        $field_name = mysqli_real_escape_string($connection, $key);
        $old_value = mysqli_real_escape_string($connection, $original_data[$key]);
        $new_value = mysqli_real_escape_string($connection, $value);
        $admin_id = $_SESSION["admin_id"];
        $change_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO tbl_user_changes (user_id, field_name, old_value, new_value, admin_id, change_date) VALUES ($user_id, '$field_name', '$old_value', '$new_value', $admin_id, '$change_date')";
        mysqli_query($connection, $query);
        }
    }
} -->





<!-- // Verbinden mit der Datenbank
$connection = mysqli_connect("localhost", "username", "passwort", "datenbankname");

// Überprüfen, ob eine Verbindung hergestellt wurde
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// SELECT-Abfrage auf tbl_user_changes ausführen
$query = "SELECT * FROM tbl_user_changes WHERE user_id = $user_id ORDER BY change_date DESC";
$result = mysqli_query($connection, $query);

// Eine Tabelle ausgeben, um die Änderungen anzuzeigen
echo '<table>';
echo '<tr><th>Field</th><th>Old Value</th><th>New Value</th><th>Changed By</th><th>Change Date</th></tr>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['field_name'] . '</td>';
    echo '<td>' . $row['old_value'] . '</td>';
    echo '<td>' . $row['new_value'] . '</td>';
    echo '<td>' . $row['admin_id'] . '</td>';
    echo '<td>' . $row['change_date'] . '</td>';
    echo '</tr>';
}
echo '</table>';

// Schließen der Datenbankverbindung
mysqli_close($connection); -->
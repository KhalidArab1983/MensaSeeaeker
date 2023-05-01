<?php

include('../conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
}else{
    header("Location: a_login.php");
	exit;
}
date_default_timezone_set("Europe/Berlin");

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
    <body>
        <?php include ('./nav_includes/navbar.php') ?>

        <hr style="height: 5px">
        <div class="container">
            <h3>An Benutzerdaten vorgenommene Aktualisierungen:</h3>
            <div class="scrollView700">
                <?php            
                    // SELECT-Abfrage auf tbl_user_changes ausführen
                    $query = "SELECT a.adminName, a.color_hex, u.userName, c.id, c.field_name, c.old_value, c.new_value, c.change_date
                                FROM tbl_user_changes c
                                INNER JOIN tbl_admin a ON a.id = c.admin_id
                                INNER JOIN tbl_user u ON u.id = c.user_id
                                ORDER BY change_date DESC";
                    $result = mysqli_query($conn, $query);

                    // Eine Tabelle ausgeben, um die Änderungen anzuzeigen
                    echo '<table>';
                        echo '<thead class="topFix">
                                <tr>
                                    <th>Änderung ID</th>
                                    <th>Geändert durch Admin</th>
                                    <th>Geändert für Benutzer</th>
                                    <th>Feld Name</th>
                                    <th>Alte Wert</th>
                                    <th>Neue Wert</th>
                                    <th>Änderung Zeit</th>
                                </tr>
                            </thead>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            $adminFarbe = $row['color_hex'];
                            echo '<tr class="tableRow">';
                                echo '<td>' . $row['id'] . '</td>';
                                echo "<td style='color:{$adminFarbe}; font-weight:bold'>" . $row['adminName'] . "</td>";
                                echo '<td>' . $row['userName'] . '</td>';
                                echo '<td>' . $row['field_name'] . '</td>';
                                echo '<td class="colorGray">' . $row['old_value'] . '</td>';
                                echo '<td class="colorGreen">' . $row['new_value'] . '</td>';
                                echo '<td>' . $row['change_date'] . '</td>';
                            echo '</tr>';
                        }
                    echo '</table>';
                    
                ?>
            </div>

            <hr class="mt-5 mb-4" style="height: 5px">
            <h3>An Gerichte vorgenommene Aktualisierungen:</h3>
            <div class="scrollView700">
                <?php            
                    // SELECT-Abfrage auf tbl_user_changes ausführen
                    $query = "SELECT a.adminName, a.color_hex, o.option_name, c.id, c.field_name, c.old_value, c.new_value, c.change_date
                                FROM tbl_option_changes c
                                INNER JOIN tbl_admin a ON a.id = c.admin_id
                                INNER JOIN tbl_option o ON o.id = c.option_id
                                ORDER BY change_date DESC";
                    $result = mysqli_query($conn, $query);

                    // Eine Tabelle ausgeben, um die Änderungen anzuzeigen
                    echo '<table>';
                        echo '<thead class="topFix">
                                <tr>
                                    <th>Änderung ID</th>
                                    <th>Geändert durch Admin</th>
                                    <th>Geändertes Gericht</th>
                                    <th>Feld Name</th>
                                    <th>Alte Wert</th>
                                    <th>Neue Wert</th>
                                    <th>Änderung Zeit</th>
                                </tr>
                            </thead>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            $adminFarbe = $row['color_hex'];
                            echo '<tr class="tableRow">';
                                echo '<td>' . $row['id'] . '</td>';
                                echo "<td style='color:{$adminFarbe}; font-weight:bold'>" . $row['adminName'] . "</td>";
                                echo '<td>' . $row['option_name'] . '</td>';
                                echo '<td>' . $row['field_name'] . '</td>';
                                echo '<td class="colorGray">' . $row['old_value'] . '</td>';
                                echo '<td class="colorGreen">' . $row['new_value'] . '</td>';
                                echo '<td>' . $row['change_date'] . '</td>';
                            echo '</tr>';
                        }
                    echo '</table>';
                    // Schließen der Datenbankverbindung
                    mysqli_close($conn);
                ?>
            </div>

        </div>



        
        <div style="margin-bottom: 80px">
            
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>

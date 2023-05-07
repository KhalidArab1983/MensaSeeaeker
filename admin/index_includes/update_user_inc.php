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
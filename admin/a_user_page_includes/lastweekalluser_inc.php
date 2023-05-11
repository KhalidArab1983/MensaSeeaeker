<div class="container">
    <h3>die Bestellungen für alle Benutzer für nächste Woche:</h3>
    <div class="scrollView700">
        <table>
            <form method="POST">
                <thead class="topFix">
                    <tr>
                        <th>Bestell-ID</th>
                        <!-- <th>User ID</th> -->
                        <th>UserName</th>
                        <th>Gerichtsname</th>
                        <th>Der Tag</th>
                        <th>Datum des Tages</th>
                        <th>Bestell Datum</th>
                    </tr>
                </thead>
            </form>
            <tbody>
                <?php
                    $woche_count = ($current_day == $sonntag)?$week_count = date('W') + 1 : $week_count = date('W'); 
                    // Abrufen Bestellungen der Benutzern für nächste Woche aus der Datenbank
                    // $sql = "SELECT u.id, u.userName, b.id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum
                    //         FROM tbl_user u JOIN (SELECT id, user_id, option_name, option_id, day, day_datum, bestelldatum
                    //             FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num FROM tbl_bestellung) t
                    //             WHERE t.row_num <= 5) b ON u.id = b.user_id ORDER BY b.bestelldatum DESC;";
                    $sql = "SELECT u.id, u.userName, b.id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum
                            FROM tbl_user u INNER JOIN tbl_bestellung b ON u.id = b.user_id
                            WHERE b.week_count = $woche_count";
                    $result = mysqli_query($conn, $sql);
                    // Ausgabe der Bestellungen in einer Tabelle
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="tableRow">';
                            echo '<td>' . $row['id'] . '</td>';
                            // echo '<td>' . $row['user_id'] . '</td>';
                            echo '<td>' . $row['userName'] . '</td>';
                            echo '<td>' . $row['option_name'] . '</td>';
                            echo '<td>' . $row['day'] . '</td>';
                            echo '<td>' . $row['day_datum'] . '</td>';
                            echo '<td>' . $row['bestelldatum'] . '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
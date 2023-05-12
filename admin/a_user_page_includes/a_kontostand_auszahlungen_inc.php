<h3>Auszahlungen</h3>
<div class="scrollView700">                    
    <table>
        <form method="POST">
            <thead class="topFix">
                <tr>
                    <th>Benutzer Name</th>
                    <th>Auszahlungen</th>
                    <th>Auszahlungsdatum</th>
                </tr>
            </thead>
        </form>
        <tbody>
            <?php
                // Abrufen aller Bestellungen aus der Datenbank
                $sql = "SELECT u.userName, a.auszahlung, a.auszahlung_date FROM tbl_auszahlung a
                        INNER JOIN tbl_user u ON u.id = a.user_id ORDER BY a.auszahlung_date DESC";
                $result = mysqli_query($conn, $sql);
                // Ausgabe der Bestellungen in einer Tabelle
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr class="tableRow">';
                        echo '<td>' . $row['userName'] . '</td>';
                        echo '<td>' . $row['auszahlung'] . '€</td>';
                        echo '<td>' . $row['auszahlung_date'] . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</div>
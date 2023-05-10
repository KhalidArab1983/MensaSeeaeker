<div class="container">
    <form method="get">
        <div style="display:flex">
            <input type="text" class="form-control m-1" name="userNameBestell" id="user_id" placeholder="nach einem Benutzer suchen..."> 
            <button type="submit" name="button" class="btn btn-warning m-1" value="Suchen">Suchen</button>
        </div>
    </form>
    <div class="scrollView700">
        <table>
            <thead class="topFix">
                <tr>
                <th>Bestell-ID</th>
                <th>User-ID</th>
                <th>User Name</th>
                <th>Gerichtsname</th>
                <th>Der Tag</th>
                <th>Datum des Tages</th>
                <th>Bestell Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                    if (isset($_GET['userNameBestell'])) {
                        // Benutzereingabe bereinigen
                        $userNameBestell = trim(mysqli_real_escape_string($conn, $_GET['userNameBestell']));
                        // Abrufen der Bestellungen für den angegebenen Benutzer
                        $sql = "SELECT u.userName, b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                FROM tbl_bestellung AS b LEFT JOIN tbl_user AS u ON u.id = b.user_id WHERE u.userName = '$userNameBestell' 
                                ORDER BY b.bestelldatum DESC;";
                        $result = mysqli_query($conn, $sql);
                        if($result->num_rows > 0){
                            // Ausgabe der Bestellungen in einer Tabelle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr class="tableRow">';
                                    echo '<td>' . $row['id'] . '</td>';
                                    echo '<td>' . $row['user_id'] . '</td>';
                                    echo '<td>' . $row['userName'] . '</td>';
                                    echo '<td>' . $row['option_name'] . '</td>';
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
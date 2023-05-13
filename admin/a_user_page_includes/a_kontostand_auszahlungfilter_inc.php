<div class="container">
    <form method="get">
        <div class="col-4" style="display:flex">
            <input type="text" class="form-control m-1" name="userNameAuszahl" id="user_id" placeholder="nach einem Benutzer suchen..."> 
            <button type="submit" name="button" class="btn btn-warning m-1" value="auszahlungSuchen">Suchen</button>
        </div>
    </form>
    <h3>Auszahlungen für einzelnen Benutzer</h3>
    <div class="scrollView700">
        <table>
            <thead class="topFix">
                <tr>
                    <th>Benutzer Name</th>
                    <th>Auszahlungen</th>
                    <th>Auszahlungsdatum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                    if (isset($_GET['userNameAuszahl'])) {
                        // Benutzereingabe bereinigen
                        $userNameAuszahl = trim(mysqli_real_escape_string($conn, $_GET['userNameAuszahl']));
                        // Abrufen der Bestellungen für den angegebenen Benutzer
                        $sql = "SELECT u.userName, a.auszahlung, a.auszahlung_date FROM tbl_auszahlung a
                                LEFT JOIN tbl_user u ON u.id = a.user_id WHERE u.userName = '$userNameAuszahl'
                                ORDER BY a.auszahlung_date DESC";
                        $result = mysqli_query($conn, $sql);
                        if($result->num_rows > 0){
                            // Ausgabe der Bestellungen in einer Tabelle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr class="tableRow">';
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
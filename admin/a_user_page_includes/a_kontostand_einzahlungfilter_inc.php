<div class="container">
    <form method="get">
        <div class="col-4" style="display:flex">
            <input type="text" class="form-control m-1" name="userNameEinzahl" id="user_id" placeholder="nach einem Benutzer suchen..."> 
            <button type="submit" name="button" class="btn btn-warning m-1" value="einzahlungSuchen">Suchen</button>
        </div>
    </form>
    <h3>Einzahlungen für einzelnen Benutzer</h3>
    <div class="scrollView700">
        <table>
            <thead class="topFix">
                <tr>
                    <th>Benutzer Name</th>
                    <th>Einzahlungen</th>
                    <th>Einzahlungsdatum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Überprüfen, ob eine Suchanfrage gesendet wurde
                    if (isset($_GET['userNameEinzahl'])) {
                        // Benutzereingabe bereinigen
                        $userNameEinzahl = trim(mysqli_real_escape_string($conn, $_GET['userNameEinzahl']));
                        // Abrufen der Bestellungen für den angegebenen Benutzer
                        $sql = "SELECT u.userName, e.einzahlung, e.einzahlung_date FROM tbl_einzahlung e
                                LEFT JOIN tbl_user u ON u.id = e.user_id WHERE u.userName = '$userNameEinzahl'
                                ORDER BY e.einzahlung_date DESC";
                        $result = mysqli_query($conn, $sql);
                        if($result->num_rows > 0){
                            // Ausgabe der Bestellungen in einer Tabelle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr class="tableRow">';
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
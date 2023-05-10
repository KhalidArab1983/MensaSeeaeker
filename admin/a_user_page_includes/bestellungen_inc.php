<div class="container">
    <form method="get" class="">
        <div class="m-1" >
            <div>
                <div class="disFlex m-1">
                    <label class="width50">Von: </label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
                <div class="disFlex m-1">
                    <label class="width50">Bis: </label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-warning m-1" name="button">Bestellung Suchen</button>
                <button type="submit" class="btn btn-warning m-1" name="alleBestellungen">Alle Bestellungen</button>
            </div>
        </div>
    </form>
    <div class="scrollView700">
        <table>
            <thead class="topFix">
                <tr>
                    <th>Bestell-ID</th> 
                    <th>User ID</th>
                    <th>UserName</th>
                    <th>Gerichtsname</th>
                    <th>Der Tag</th>
                    <th>Datum des Tages</th>
                    <th>Bestell Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_GET['alleBestellungen'])){
                        echo '<tr>';
                            echo '<h4>Alle Bestellungen für alle Benutzer.</h4>';
                        echo '</tr>';
                        // Abrufen aller Bestellungen aus der Datenbank
                        $sql = "SELECT b.id, b.user_id, u.userName, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                FROM tbl_bestellung AS b INNER JOIN tbl_user AS u ON u.id = b.user_id 
                                ORDER BY b.bestelldatum";
                        $result = mysqli_query($conn, $sql);
                        // Ausgabe der Bestellungen in einer Tabelle
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr class="tableRow">';
                                // echo 'Alle Bestellungen';
                                echo '<td>' . $row['id'] . '</td>';
                                echo '<td>' . $row['user_id'] . '</td>';
                                echo '<td>' . $row['userName'] . '</td>';
                                echo '<td>' . $row['option_name'] . '</td>';
                                echo '<td>' . $row['day'] . '</td>';
                                echo '<td>' . $row['day_datum'] . '</td>';
                                echo '<td>' . $row['bestelldatum'] . '</td>';
                            echo '</tr>';
                        }
                    }
                    elseif(isset($_GET['button'])){
                        echo '<tr>';
                            echo "<h4>Die Bestellungen für alle Benutzer von <span style='color:blue'>{$start_date}</span> bis <span style='color:blue'>{$end_date}</span>.</h4>";
                        echo '</tr>';
                        // Abrufen aller Bestellungen aus der Datenbank
                        $sql = "SELECT b.id, b.user_id, u.userName, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum 
                                FROM tbl_bestellung AS b INNER JOIN tbl_user AS u ON u.id = b.user_id 
                                WHERE b.bestelldatum >= '{$start_date}' AND b.bestelldatum <= '{$end_date}' + INTERVAL 1 DAY
                                ORDER BY b.bestelldatum";
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
                                echo '<td>Keine Bestellungen für ausgewählte Datum gefunden.</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
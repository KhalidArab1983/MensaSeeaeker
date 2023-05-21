<div>
    <h3 class="floatLeft">Alle Bestellende Essen:</h3>
    <h3 class="text-center">Benutzer-ID: <span class="colorRed"><?php echo "[". $user_id."]"?></span></h3>
</div>
<div class="scrollView700">
    <table>
        <thead class="topFix">
            <tr>
            <th>Bestell-ID</th>
            <th>Gerichtsname</th>
            <th>Preis</th>
            <th>Der Tag</th>
            <th>Datum des Tages</th>
            <th>Bestell Datum</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(count($bestellungen) > 0){
                    foreach($bestellungen as $bestellung){
                        echo '<tr class="tableRow">';
                            echo '<td>'.$bestellung['id']. '</td>';
                            echo '<td>'.$bestellung['option_name']. '</td>';
                            echo '<td>'.$bestellung['price'].'€</td>';
                            echo '<td>'.$bestellung['day'].'</td>';
                            echo '<td>'.$bestellung['day_datum'].'</td>';
                            echo '<td>'.$bestellung['bestelldatum'].'</td>';
                        echo '</tr>';
                    }
                }else {
                    echo '<tr>';
                        echo '<td><h5 class="colorRed text-center">Keine Bestellungen gefunden.</h5></td>';
                    echo '</tr>';
                }
                mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>
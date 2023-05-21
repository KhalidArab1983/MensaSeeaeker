<div> 
    <h3 class="floatLeft">Bestellende Essen für nächste Woche:</h3>
    <h3 class="text-center">Benutzer-ID: <span class="colorRed"><?php echo "[". $user_id."]"?></span></h3>
</div>
<div class="scrollView300">
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
                if(count($letzte_bestellungen) > 0){
                    foreach($letzte_bestellungen as $letzte_bestellung){
                        echo '<tr class="tableRow">';
                            echo '<td>'.$letzte_bestellung['id'].'</td>';
                            echo '<td>'.$letzte_bestellung['option_name'].'</td>';
                            echo '<td>'.$letzte_bestellung['price']. '€</td>';
                            echo '<td>'.$letzte_bestellung['day'].'</td>';
                            echo '<td>'.$letzte_bestellung['day_datum'].'</td>';
                            echo '<td>'.$letzte_bestellung['bestelldatum'].'</td>';
                        echo '</tr>';
                    }
                }else {
                    echo '<tr>';
                        echo '<td><h5 class="colorRed text-center">Keine Bestellungen für diese Woche gefunden.</h5></td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</div>
<h3 class="mt-3">
    <?php
        echo "Der Gesamtbetrag ist: ". "<span class='colorBlue fontBold'>" . $gesamtPreis. "€</span>";
    ?>
</h3>
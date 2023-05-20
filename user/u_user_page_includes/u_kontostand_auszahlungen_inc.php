<h3 class="mt-3">
    <?php
        echo "Die Gesamte Auszahlungen sind: ". "<span class='colorRed fontBold'>" . $sumAuszahlung. "€</span>";
    ?>
</h3>
<div class="scrollView500">
    <table>
        <thead class="topFix">
            <tr>
            <th>Auszahlungen</th>
            <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(count($auszahlungen) > 0){
                    foreach($auszahlungen as $auszahl){
                        echo '<tr class="tableRow">';
                            echo '<td>'.$auszahl['auszahlung'].'€</td>';
                            echo '<td>'.$auszahl['auszahlung_date'].'</td>';
                        echo '</tr>';
                    }
                }
            ?>
        </tbody>
    </table>
</div>
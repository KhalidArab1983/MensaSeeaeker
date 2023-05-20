<h3 class="mt-3">
    <?php
        echo "Die Gesamte Einzahlungen sind: "."<span class='colorBlue fontBold'>". $sumEinzahlung. "€</span>";
    ?>
</h3>
<div class="scrollView500">
    <table>
        <thead class="topFix">
            <tr>
                <th>Einzahlungen</th>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(count($einzahlungen) > 0){
                    foreach($einzahlungen as $einzahl){
                        echo '<tr class="tableRow">';
                            echo '<td>'.$einzahl['einzahlung'].'€</td>';
                            echo '<td>'.$einzahl['einzahlung_date'].'</td>';
                        echo '</tr>';
                    }
                }
            ?>
        </tbody>
    </table>
</div>
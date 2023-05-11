<div class="container">
    <h3>Anzahl der Bestellungen für nächste Woche:</h3>
    <div class="scrollView300">
        <table>
            <form method="POST">
                <thead class="topFix">
                    <tr>
                        <!-- <th>Option ID</th> -->
                        <th>Gerichtsname</th>
                        <th>Anzahl</th>
                    </tr>
                </thead>
            </form>
            <tbody>
                <?php
                    $woche_count = ($current_day == $sonntag)?$week_count = date('W') + 1 : $week_count = date('W'); 
                    // Abrufen die Summe der Anzahl jedes Gerichts für die Woche
                    // $sql = "SELECT b.option_id, b.option_name, COUNT(*) AS anzahl
                    //         FROM (
                    //             SELECT option_id, user_id, option_name, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY bestelldatum DESC) AS row_num
                    //             FROM tbl_bestellung) AS b WHERE b.row_num <= 5 GROUP BY b.option_name ORDER BY `b`.`option_name` ASC;";

                    $sql = "SELECT option_name, COUNT(*) AS anzahl
                            FROM tbl_bestellung WHERE week_count = $woche_count GROUP BY option_name ORDER BY option_name ASC";
                    $result = mysqli_query($conn, $sql);
                    // Ausgabe der Bestellungen in einer Tabelle
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="tableRow">';
                            // echo '<td>' . $row['option_id'] . '</td>';
                            echo '<td>' . $row['option_name'] . '</td>';
                            echo '<td>' . $row['anzahl'] . '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
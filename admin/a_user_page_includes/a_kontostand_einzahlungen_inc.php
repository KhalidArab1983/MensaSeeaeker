<form method="post" class="col-12">
    <div class="m-1 disFlex">
        <input type="number" class="form-control m-1 floatLeft" step="0.01" name="einzahlungsbetrag" id="einzahlungsbetrag" placeholder="Der zu zahlende Betrag">
        <input type="text" name="userName" class="form-control m-1" id="userSearch" onkeyup="filterOptionsEinzahlung()" placeholder="nach einem Benutzer suchen...">
        <select class="form-control m-1" name="userEinzahlung" id="userEinzahlung">
            <option>Benutzer Name auswählen...</option>
            <?php 
                $sql = "SELECT id, userName FROM tbl_user";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $user_id = $row['id'];
                    $userName = $row['userName'];
                    echo '<option value="' . $user_id . '">' . $user_id . "-" . $userName . '</option>';
                }
            ?>
        </select>
        <button type="submit" name="button" class="btn btn-warning m-1" value="einzahlen">Einzahlen</button>
    </div>
</form>
<h3>Einzahlungen</h3>
<div class="scrollView700">
    <table>
        <form method="POST">
            <thead class="topFix">
                <tr>
                    <th>Benutzer Name</th>
                    <th>Einzahlungen</th>
                    <th>Einzahlungsdatum</th>
                </tr>
            </thead>
        </form>
        <tbody>
            <?php
                // Abrufen aller Bestellungen aus der Datenbank
                $sql = "SELECT u.userName, e.einzahlung, e.einzahlung_date FROM tbl_einzahlung e
                        INNER JOIN tbl_user u ON u.id = e.user_id ORDER BY e.einzahlung_date DESC";
                $result = mysqli_query($conn, $sql);
                // Ausgabe der Bestellungen in einer Tabelle
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr class="tableRow">';
                        echo '<td>' . $row['userName'] . '</td>';
                        echo '<td>' . $row['einzahlung'] . '€</td>';
                        echo '<td>' . $row['einzahlung_date'] . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</div>
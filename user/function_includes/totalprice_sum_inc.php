<?php 
//SQL-Abfrage ausführen, um die Preise aus Datenbank in der Spalte zu summieren
$query = "SELECT SUM(o.price) as total 
        FROM 
            (SELECT b.option_id 
            FROM tbl_bestellung b 
            WHERE b.user_id = $user_id AND b.week_count = $woche_count
            ORDER BY b.bestelldatum 
            DESC LIMIT 5 ) as b 
        JOIN tbl_option o ON o.id = b.option_id;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$gesamtPreis = $row['total'];
?>
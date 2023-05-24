<?php 
// Um die Einzahlungen mit Datums in der Tabelle anzeigen
$einzahlungSql = "SELECT einzahlung, einzahlung_date FROM tbl_einzahlung WHERE user_id = $user_id ORDER BY einzahlung_date DESC";
$result = mysqli_query($conn, $einzahlungSql);
$einzahlungen = array();
while($einzahlungRow = mysqli_fetch_assoc($result)){
    $einzahlungen[] = $einzahlungRow;
}

?>
<?php 
// Um die Auszahlungen mit Datums in der Tabelle anzeigen
$auszahlungSql = "SELECT auszahlung, auszahlung_date FROM tbl_auszahlung WHERE user_id = $user_id ORDER BY auszahlung_date DESC";
$result = mysqli_query($conn, $auszahlungSql);
$auszahlungen = array();
while($auszahlungRow = mysqli_fetch_assoc($result)){
    $auszahlungen[] = $auszahlungRow;
}

?>
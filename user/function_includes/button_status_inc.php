<?php 
// um die Bestell Status abzurufen und es im Button zu benutzen ob 1 ist, dann deaktiviert der Button
$bestellStatusSql = "SELECT bestell_status FROM tbl_user WHERE id = $user_id";
$statusBestell = mysqli_query($conn, $bestellStatusSql);
$statusRow = mysqli_fetch_assoc($statusBestell);
$bestell_status = $statusRow['bestell_status'];

// um die Update Status für jeden Tag aus der Tabelle tbl_bestellstatus abzurufen und es im Button zu benutzen ob 1 ist, dann deaktiviert der Button
$updateSql = "SELECT montag, dienstag, mittwoch, donnerstag, freitag FROM tbl_bestellstatus WHERE user_id = $user_id";
$updateResult = mysqli_query($conn, $updateSql);
$updateRow = mysqli_fetch_assoc($updateResult);
$Montag = $updateRow['montag'];
$Dienstag = $updateRow['dienstag'];
$Mittwoch = $updateRow['mittwoch'];
$Donnerstag = $updateRow['donnerstag'];
$Freitag = $updateRow['freitag'];

?>
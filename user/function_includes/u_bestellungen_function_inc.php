<?php 
//um die ganze Bestellungen für den Benutzer abzurufen
$bestellSql = "SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.bestelldatum, o.price
                FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                WHERE user_id = ?";
$bestell_stmt = mysqli_prepare($conn, $bestellSql);
mysqli_stmt_bind_param($bestell_stmt, "s", $user_id);
mysqli_stmt_execute($bestell_stmt);
$result = mysqli_stmt_get_result($bestell_stmt);
$bestellungen = array();
while ($row = $result->fetch_assoc()){
    $bestellungen[] = $row;
}


?>
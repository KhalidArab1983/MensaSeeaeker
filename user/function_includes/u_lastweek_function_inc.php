<?php 
//um die letzte Woche Bestellung abzurufen
$sonntag = 'Sunday';
$current_day_text = date('l');
$woche_count = ($current_day_text == $sonntag)?$week_count = date('W') + 1 : $week_count = date('W'); 
$letzteBestell = "(SELECT b.id, b.user_id, b.option_name, b.option_id, b.day, b.day_datum, b.week_count, b.bestelldatum, o.price
                    FROM tbl_bestellung b INNER JOIN tbl_option o ON o.id = b.option_id
                    WHERE b.user_id = ? AND b.week_count = ? ORDER BY id DESC LIMIT 5) ORDER BY id ASC";
$letzte_bestell_stmt = mysqli_prepare($conn, $letzteBestell);
mysqli_stmt_bind_param($letzte_bestell_stmt, "ii", $user_id, $woche_count);
mysqli_stmt_execute($letzte_bestell_stmt);
$result = mysqli_stmt_get_result($letzte_bestell_stmt);
$letzte_bestellungen = array();
while ($row = $result->fetch_assoc()){
    $letzte_bestellungen[] = $row;
    
}
?>
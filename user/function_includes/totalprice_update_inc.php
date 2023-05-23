<?php 
// um der gesamte Betrag von Bestellungen aktualisieren, wenn der Benutzer während der Woche die Bestellungen ändert
foreach ($days as $day) {
    if(isset($_POST['button']) && $_POST["button"] == $day){
        $sql = "UPDATE tbl_auszahlung SET auszahlung = ? WHERE user_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $gesamtPreis, $user_id);
        $stmt->execute();
    }
}
?>
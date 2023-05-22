<?php 
// um der gesamte Betrag von Bestellungen in die Tabelle tbl_auszahlung hinzufügen
if(isset($_POST['button']) && $_POST['button'] == 'bestellen'){
    $sql = "INSERT INTO tbl_auszahlung (auszahlung, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $gesamtPreis, $user_id);
    $stmt->execute();
}
?>
<?php
// Abfrage, um die Gesamte Einzahlungen anzugeben
$kontoEinzahlSql = "SELECT SUM(einzahlung) AS einzahlung FROM tbl_einzahlung WHERE user_id = $user_id";
$kontoEinzahlRes = mysqli_query($conn, $kontoEinzahlSql);
$kontoEinzahlRow = mysqli_fetch_assoc($kontoEinzahlRes);
$sumEinzahlung = $kontoEinzahlRow['einzahlung'];

// // Abfrage, um die Gesamte Auszahlungen anzugeben
$kontoAuszahlSql = "SELECT SUM(auszahlung) AS auszahlung FROM tbl_auszahlung WHERE user_id = $user_id";
$kontoAuszahlRes = mysqli_query($conn, $kontoAuszahlSql);
$kontoAuszahlRow = mysqli_fetch_assoc($kontoAuszahlRes);
$sumAuszahlung = $kontoAuszahlRow['auszahlung'];

// Kontostand zu berechnen
$kontostand = $sumEinzahlung - $sumAuszahlung;

?>
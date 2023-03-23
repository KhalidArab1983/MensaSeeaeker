<?php

    include('../conn/db_conn.php');

    // Überprüfen, ob die AJAX-Anfrage Daten übermittelt hat
    if (isset($_POST["totalPreis"])) {
        // Die übermittelten Daten verarbeiten
        $totalPreis = $_POST["totalPreis"];

        // Hier können Sie die $totalPreis Variable verwenden
        echo "Totalpreis ist: " . $totalPreis;

    } else {
    
        // echo "Keine Daten übermittelt";

    }

?>

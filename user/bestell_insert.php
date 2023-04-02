<?php 

include ('../conn/db_conn.php');

/* Cron-Job Funktion
Die Funktion, die automatisch das bestellstatus für alle Benutzer am Samstag auf 0 setzt, ist mit cron-job wie folgendes gemacht:
1. Öffnen Sie die Windows-Suche und suchen Sie nach "Task Scheduler(Aufgabenplanung)".
2. Klicken Sie auf "Task Scheduler(Aufgabenplanung) öffnen", um das Tool zu öffnen.
3. Klicken Sie auf "Aufgabe erstellen" im rechten Bereich des Fensters.
4. Geben Sie einen Namen für die Aufgabe ein, z.B. "Reset Bestellstatus".
5. Klicken Sie auf "Trigger hinzufügen".
6. Wählen Sie "Wöchentlich" als Auslöser und wählen Sie den Tag und die Uhrzeit aus, zu der die Aufgabe ausgeführt werden soll.
7. Klicken Sie auf "Aktion hinzufügen".
8. Wählen Sie "Programm starten" als Aktionstyp aus.
9. Geben Sie den vollständigen Pfad zur PHP-Datei in das Feld "Programm/Skript" ein, 
    gefolgt von einem Leerzeichen und dem Pfad zur PHP-Datei, z.B. "C:\xampp\php\php.exe C:\xampp\htdocs\meine-website\reset_bestellstatus.php".
10. Klicken Sie auf "OK", um die Aufgabe zu speichern.

Code in der reset_bestellstatus.php ist wie folgendes:

    $host = "localhost"; // Database host
    $username = "root"; // Database username
    $password = ""; // Database password
    $dbname = "mensa_seeaeker"; // Database name
    $db_port = "3306"; // Database Port


    // Create connection
    $conn = mysqli_connect($host, $username, $password, $dbname, $db_port);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // echo "Connected successfully";

    $samstag = 'Saturday';
    $current_day = date('l');
    $bestell_status = 0;
    $update_status = 0;
            

    // Aktiviere bestellButton für alle Benutzer am Sonntag
    $sql = "UPDATE tbl_user SET bestell_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bestell_status);
    $stmt->execute();

    // Aktiviere alle UpdateButtons für alle Benutzers am Sonntag
    $updateSql = "UPDATE tbl_bestellstatus SET montag = ?, dienstag = ?, mittwoch = ?, donnerstag = ?, freitag = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssss", $update_status, $update_status, $update_status, $update_status, $update_status);
    $updateStmt->execute();
    
*/



$samstag = 'Saturday';
$sonntag = 'Sunday';
$current_day = date('l');
$current_time = date('H:i:s');
$week_count = date('W');
$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["button"]) && $_POST["button"] == "bestellen"){
        //Optionen für jeden Tag durchlaufen
        foreach ($days as $day) {
            $option_name = $_POST['option_name_' . $day];
            $option_id = $_POST['option_name_' . $day];
            $date = $_POST['option_name_' . $day];
            if($current_day == $sonntag){
                $week_count = date('W') +1;
                $sql = "INSERT INTO tbl_bestellung (user_id, option_name, option_id, day, day_datum, week_count) 
                    VALUES (?, (SELECT option_name FROM tbl_option WHERE id = ?), ?, ?, (SELECT date FROM tbl_option WHERE id = ?),?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $user_id, $option_name, $option_id, $day, $date, $week_count);
                $stmt->execute();
                header("Location: danke.php");
            }else{
                $week_count = date('W');
                $sql = "INSERT INTO tbl_bestellung (user_id, option_name, option_id, day, day_datum, week_count) 
                    VALUES (?, (SELECT option_name FROM tbl_option WHERE id = ?), ?, ?, (SELECT date FROM tbl_option WHERE id = ?),?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $user_id, $option_name, $option_id, $day, $date, $week_count);
                $stmt->execute();
                header("Location: danke.php");
            }
            
        }
        $bestell_status = 1;
        // Update user's bestell_status to '1'
        $sql_status = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_status);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $bestell_status, $user_id);
            $stmt->execute();
        }
        
        // //um eine Datensatz für Update in der Tabelle tbl_bestellstatus für neuen Benutzer erstellen
        // $nullWert= 0;
        // $updateSql = "INSERT INTO tbl_bestellstatus (user_id, montag, dienstag, mittwoch, donnerstag, freitag) VALUES (?,?,?,?,?,?)";
        // $updateStmt = $conn->prepare($updateSql);
        // $updateStmt->bind_param("ssssss", $user_id, $nullWert, $nullWert, $nullWert, $nullWert, $nullWert);
        // $updateStmt->execute();
    }
    // header("Location: danke.php");

}

// echo "Gesamtpreis ist: $". number_format($GLOBALS['totalPreis'], 2)

?>


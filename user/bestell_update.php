<?php 
// include ('../conn/db_conn.php');


$current_day = date('w');
$current_time = date('H:i:s');
$bestell_status_deaktiv = 1;

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){

    //um eine Datensatz für Update in der Tabelle tbl_bestellstatus für neuen Benutzer erstellen
    $nullWert= 0;
    $updateSql = "INSERT INTO tbl_bestellstatus (user_id, montag, dienstag, mittwoch, donnerstag, freitag) VALUES (?,?,?,?,?,?)";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssss", $user_id, $nullWert, $nullWert, $nullWert, $nullWert, $nullWert);
    $updateStmt->execute();


    if($current_time >= '22:00:00' && $current_day == 0 || $current_day > 0 && $current_day <= 6){
        // Update Button für Montag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET montag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 1 || $current_day > 1 && $current_day <= 6){
        // Update Button für Dienstag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET dienstag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 2 || $current_day > 2 && $current_day <= 6){
        // Update Button für Mittwoch deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET mittwoch = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 3 || $current_day > 3 && $current_day <= 6){
        // Update Button für Donnerstag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET donnerstag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 4 || $current_day > 4 && $current_day <= 6){
        // Update Button für Freitag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET freitag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();

        $bestellSql= "UPDATE tbl_user SET bestell_status = ?";
        $stmt = $conn->prepare($bestellSql);
        $stmt->bind_param("s", $bestell_status_deaktiv);
        $stmt->execute();
    }
}


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
    foreach ($days as $day) {

        $bestellung_id_query = "SELECT id FROM tbl_bestellung WHERE day = '".$day."' ORDER BY id DESC LIMIT 5";
        $bestell_result = mysqli_query($conn, $bestellung_id_query);
        $bestell_id_row = mysqli_fetch_assoc($bestell_result);
        $bestellung_id = $bestell_id_row['id'];

        if(isset($_POST['button']) && $_POST["button"] == $day){

            $option_name = $_POST['option_name_' .$day];
            $option_id = $_POST['option_name_' .$day];
            // $date = $_POST['option_name_' .$day];
            $sql = "UPDATE tbl_bestellung INNER JOIN tbl_option ON tbl_bestellung.option_id = tbl_option.id
                    SET tbl_bestellung.option_name = (SELECT option_name FROM tbl_option WHERE id = $option_id), 
                    tbl_bestellung.option_id= $option_id WHERE tbl_bestellung.id =$bestellung_id";
            $result = mysqli_query($conn, $sql);
            header('Location: u_user_page.php');
        }
        
    }
    
    /*
    Es gab einen Konflikt zwischen der Methode GET und POST bezüglich des Codes zum Ändern des Passworts 
    in "Meine Daten" Seite und des Codes zum Update hier in dieser Datei, und nach dem Suchen und 
    Überprüfen lag der Konflikt an den folgenden zwei Zeilen in dieser Position wo diesen Kommentar geschrieben hat:
    header('Location: u_user_page.php');
    exit();
    */ 
    
}
?>
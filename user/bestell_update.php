<?php 
include ('../conn/db_conn.php');

$current_day = date('w');
$current_time = date('H:i:s');
$current_date = date('Y-m-d');
$bestell_status_deaktiv = 1;

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){
    if($current_time >= '22:00:00' && $current_day == 0 || $current_day > 0 && $current_day < 5){
        // Update Button für Montag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET montag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 1 || $current_day > 1 && $current_day < 5){
        // Update Button für Dienstag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET dienstag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 2 || $current_day > 2 && $current_day < 5){
        // Update Button für Mittwoch deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET mittwoch = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 3 || $current_day > 3 && $current_day < 5){
        // Update Button für Donnerstag deaktivieren
        $sql = "UPDATE tbl_bestellstatus SET donnerstag = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
        $stmt->execute();
    }
    if($current_time >= '22:00:00' && $current_day == 4 || $current_day > 4 && $current_day < 5){
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
        }
        
    }
    header('Location: u_user_page.php');
}
?>
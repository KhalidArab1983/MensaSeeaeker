<?php 
include ('../conn/db_conn.php');

$samstag = 'Saturday';
$sonntag = 'Sunday';
$current_day = date('l');
$current_time = date('H:i:s');
$current_date = date('Y-m-d');
$bestell_status_deaktiv = 2;
$bestell_status_aktiv = 0;

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){
    $tagen = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
    foreach($tagen as $tag){
        if($current_time >= '22:00:00' && $current_day == $tag){
            // Reset user's bestell_status at the start of every week
            $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
            $stmt->execute();
        }
        if($current_day == $samstag || $current_day == $sonntag){
            // Reset user's bestell_status at the start of every week
            $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $bestell_status_aktiv, $user_id);
            $stmt->execute();
        }

        
    }
}
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
    foreach ($days as $day) {

        $bestellung_id_query = "SELECT id FROM tbl_bestellung WHERE day = '".$day."' ORDER BY id DESC LIMIT 5";
        $bestell_result = mysqli_query($conn, $bestellung_id_query);
        $bestell_id_row = mysqli_fetch_assoc($bestell_result);
        $bestellung_id = $bestell_id_row['id'];
        echo $bestellung_id;
        if(isset($_POST['button']) && $_POST["button"] == $day){

            $option_name = $_POST['option_name_' .$day];
            $option_id = $_POST['option_name_' .$day];
            $date = $_POST['option_name_' .$day];
            $sql = "UPDATE tbl_bestellung INNER JOIN tbl_option ON tbl_bestellung.option_id = tbl_option.id
                    SET tbl_bestellung.option_name = (SELECT option_name FROM tbl_option WHERE id = $option_id), 
                    tbl_bestellung.option_id= $option_id WHERE tbl_bestellung.id =$bestellung_id";
            $result = mysqli_query($conn, $sql);
        }
        header('Location: u_user_page.php');
    }
    
}
?>
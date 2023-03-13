<?php 
// include ('../conn/db_conn.php');

// $samstag = 'Saturday';
// $sonntag = 'Sunday';
// $current_day = date('l');
// $current_time = date('H:i:s');
// $bestell_status_deaktiv = 2;
// $bestell_status_aktiv = 0;
// if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
//     if($current_time >= '22:00:00'){
//         // Reset user's bestell_status at the start of every week
//         $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ss", $bestell_status_deaktiv, $user_id);
//         $stmt->execute();
//     }
//     if($current_day == $samstag || $current_day == $sonntag){
//         // Reset user's bestell_status at the start of every week
//         $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ss", $bestell_status_aktiv, $user_id);
//         $stmt->execute();
//     }
//     if(isset($_POST['button'])){
//         $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
//         foreach ($days as $day) {
//             if($_POST["button"] == "$day"){
//                 $option_name = $_POST[$day];
//                 $option_id = $_POST[$day];
//                 // $date = $_POST[$day];
//                 $sql = "UPDATE tbl_bestellung SET option_name = ?, option_id = ? WHERE id = ?";
//                 $stmt = $conn->prepare($sql);
//                 $stmt->bind_param("sss", $option_name, $option_id, $user_id);
//                 $stmt->execute();
//             }
//             header("Location: u_user_page.php");
//         }
//     }
    
// }


?>
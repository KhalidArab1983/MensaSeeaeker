<?php 
include ('../conn/db_conn.php');


// $dateWoche = date_create();
// while(true){
//     $week = date_format($dateWoche, 'W');
        
//     $dateWoche->modify('+1 week');
// }



$samstag = 'Saturday';
$sonntag = 'Sunday';
$current_day = date('l');
// $woche_count = date('W');
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    // for ($i=0; $i < $woche_count ; $i++) { 
        if($current_day == $samstag || $current_day == $sonntag){
            // Aktiviere den Bestellbutton für Samstag und Sonntag
            $bestell_status = 0;
            // Reset user's bestell_status at the start of every week
            // Add this task to a weekly cron job
            $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $bestell_status, $user_id);
            $stmt->execute();
        }
        if(isset($_POST["button"]) && $_POST["button"] == "bestellen"){
            //Optionen für jeden Tag durchlaufen
            $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
            foreach ($days as $day) {
                $option_name = $_POST['option_name_' . $day];
                $option_id = $_POST['option_name_' . $day];
                $date = $_POST['option_name_' . $day];
                $sql = "INSERT INTO tbl_bestellung (user_id, option_name, option_id, day, day_datum) 
                        VALUES (?, (SELECT option_name FROM tbl_option WHERE id = ?), ?, ?, (SELECT date FROM tbl_option WHERE id = ?))";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $user_id, $option_name, $option_id, $day, $date);
                $stmt->execute();
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
        }
            
        header("Location: danke.php");
        // }
        // $i++;
    }
    
    // $date = date_create();
    // while(true){
    //     $week = date_format($date, 'W');
    //     $date->modify('+1 week');
    // }

    // foreach($date as $woche){
    //     echo $woche[$samstag];
    //     echo $woche[$sonntag];
    // }
    // $wochen = array();
    // for ($i=0; $i < $date; $i++) { 
    //     # code...
    // }
    

// if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST") {

//     if(isset($_POST["button"]) && $_POST["button"] == "bestellen"){
        
        
//         //Optionen für jeden Tag durchlaufen
//         $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
//         foreach ($days as $day) {
            
//             $option_name = $_POST['option_name_' . $day];
//             $option_id = $_POST['option_name_' . $day];
//             $date = $_POST['option_name_' . $day];
//             $sql = "INSERT INTO tbl_bestellung (user_id, option_name, option_id, day, day_datum) 
//                     VALUES (?, (SELECT option_name FROM tbl_option WHERE id = ?), ?, ?, (SELECT date FROM tbl_option WHERE id = ?))";
//             $stmt = $conn->prepare($sql);
//             $stmt->bind_param("sssss", $user_id, $option_name, $option_id, $day, $date);
//             $stmt->execute();
            

//         }
//         $current_day = date('N'); // Liefert den aktuellen Wochentag in Textform (z.B. "Monday")

//         if ($current_day == 6) {
//             // Aktiviere den Bestellbutton für alle Tage
//             $bestell_status = 0;
//             // Reset user's bestell_status at the start of every week
//             // Add this task to a weekly cron job
//             $sql = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
//             $stmt = $conn->prepare($sql);
//             $stmt->bind_param("ss", $bestell_status, $user_id);
//             $stmt->execute();
//         }else{
//             $bestell_status = 1;
//             // Update user's bestell_status to '1'
//             $sql_status = "UPDATE tbl_user SET bestell_status = ? WHERE id = ?";
//             $stmt = $conn->prepare($sql_status);
//             if (!$stmt) {
//                 echo "Error preparing statement: " . $conn->error;
//             } else {
//                 $stmt->bind_param("ss", $bestell_status, $user_id);
//                 $stmt->execute();
//             }
//         }
        
//         header("Location: danke.php");
//     }
// } 

?>


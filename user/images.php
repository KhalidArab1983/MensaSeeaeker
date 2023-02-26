<?php 
include('../conn/db_conn.php');


// // Set the time zone to your local time zone
// date_default_timezone_set('Europe/Berlin');
// // Create a new DateTime object
// $date = new DateTime();
// // Loop through the days of the week
// for ($i = 0; $i < 6; $i++) {
//     // Output the day of the week and the date in the format "l, d.m.Y"
//     if($date->format('N') < 6){
//         // echo $date->format('l, d.m.Y') . "<br>";
//     }
//     // Add one day to the date
//     $date->modify('+1 day');
// }


if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $option_name = $_POST['option_name'];
}else{
    $option_name = "";
}

if(isset($_GET['id'])){
    $option_id = $_GET['id'];

    // Datenbankabfrage ausführen, um das Bild für die übergebene ID abzurufen
    $sql = "SELECT data, option_name, day, price FROM tbl_option WHERE id = $option_id";
    $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
                $data = $row['data'];
                $option_name = $row['option_name'];
                $day = $row['day'];
                $price = $row['price'];
                echo '<div style="width:500px; display:flex;">';
                    echo '<img style="width:200px;" src="data:image/jpeg;base64,'.base64_encode($data).'">';
                    echo '<div style="display:block;">';
                        echo '<label class="ms-2">'.$day.": ".'</label>';
                        // if($date->format('N') < 6){
                        //     echo '<label class="ms-2">'.$date->format('d.m.Y').'</label><br>';
                        // }
                        echo '<label class="ms-2">'.$price."€".'</label><br>';
                        echo '<label class="ms-2">'.$option_name.'</label><br>';
                    echo '</div>'; 
                echo '</div>'; 
            }
        }else{
            echo "auswählen";
        }
        echo '<hr>';
    
}

// Verbindung zur Datenbank schließen
mysqli_close($conn);

?>
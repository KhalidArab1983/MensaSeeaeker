<?php 
include('../conn/db_conn.php');

// session_start();

// $gesamtPreisSession = 0;
// if (isset($_SESSION['gesamtPreis'])) {
//     $gesamtPreisSession = floatval($_SESSION['gesamtPreis']);
// }
// if (isset($_GET['value'])) {
//     $value = floatval($_GET['value']);
//     $gesamtPreisSession += $value;
//     $_SESSION['gesamtPreis'] = strval($gesamtPreisSession);
// }
// echo $gesamtPreisSession;

// if(isset($_SESSION['price'])){
//     $_SESSION["price"] = array_push($_SESSION['gesamtPreis'], $_GET['value']);
// }else{
//     $_SESSION["price"] = 0;
// }

// // $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');

// $days = ["Montag"=> $_GET['value'], "Dienstag"=>$_GET['value'], "Mittwoch"=>$_GET['value'], "Donnerstag"=>$_GET['value'], "Freitag"=>$_GET['value']];
// foreach($days as $day){

//     $_SESSION["gesamtPreis"] += $day;
//     // var_dump( $day);
// }
// // var_dump( $_SESSION['gesamtPreis']);
// echo $_SESSION["gesamtPreis"];

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
// $days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
// global $total_preis;
// $total_preis = 0;

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $option_name = $_POST['option_name'];
}else{
    $option_name = "";
}

if(isset($_GET['id'])){
    $option_id = $_GET['id'];
    // Datenbankabfrage ausführen, um das Bild für die übergebene ID abzurufen
    $sql = "SELECT data, option_name, day, date, price FROM tbl_option WHERE id = $option_id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = $row['data'];
        $option_name = $row['option_name'];
        $day = $row['day'];
        $date = $row['date'];
        $price = $row['price'];
        echo '<div style="width:500px; display:flex;">';
            echo '<img style="width:200px;" src="data:image/jpeg;base64,'.base64_encode($data).'">';
            echo '<div style="display:block;">';
                echo '<label class="ms-2">'.$day.": ".$date .'</label><br>';
                echo '<label class="ms-2">'.$price."€".'</label><br>';
                echo '<label class="ms-2">'.$option_name.'</label><br>';
            echo '</div>'; 
        echo '</div>'; 
    }
    echo '<hr>';
}
mysqli_close($conn);

?>
<?php 
include('../conn/db_conn.php');


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
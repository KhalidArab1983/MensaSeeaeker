
<!-- 
    include('../conn/db_conn.php');

    // Überprüfen, ob die AJAX-Anfrage Daten übermittelt hat
    if (isset($_POST["totalPreis"])) {
        // Die übermittelten Daten verarbeiten
        $totalPreis = $_POST["totalPreis"];

        // Hier können Sie die $totalPreis Variable verwenden
        echo "Totalpreis ist: " . $totalPreis;

    } else {
    
        echo "Keine Daten übermittelt";

    } -->





<?php
include('../conn/db_conn.php');
$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
    // Initialisiere die Variable totalPreis
    $totalPreis = 0;
    // Erstelle eine Session, um die Variable global zu speichern
    session_start();
    $_SESSION['totalPreis'] = $totalPreis;

    foreach($days as $day):
?>
    <div class="mb-1" style="height:10vh">
        <label for="option_name_<?php echo $day; ?>" style="width:115px; font-weight:bold"><?php echo $day;?>:</label>
        <select class="w-50 h-50" name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>" onChange="chImage<?php echo $day;?>()">
            <?php
                // if($$day == 1){
                //     $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE price = 0.00";
                //     $result = mysqli_query($conn, $sql);
                //     $row = mysqli_fetch_assoc($result);
                //     $option_name = $row['option_name'];
                //     $data = $row['data'];
                //     $price = $row['price'];
                //     $option_id = $row['id'];
                //     echo '<option value="' . $option_id . '">'. $option_name . "-" . $price . '€</option>';
                //     $totalPreis += $price;
                // }else{
                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '" .$day."'";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        $option_name = $row['option_name'];
                        $data = $row['data'];
                        $price = $row['price'];
                        $option_id = $row['id'];
                        echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                        
                    }
                    $totalPreis += $price;
                // }
            ?>
        </select>
    </div>
<?php
    endforeach;

    // Aktualisiere den Wert der Session-Variable
    $_SESSION['totalPreis'] = $totalPreis;
    echo number_format($totalPreis, 2);
?>


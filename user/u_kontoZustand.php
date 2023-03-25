<?php
include ('../conn/db_conn.php');
$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');
$totalPreis = 0;
global $totalPreis;    
?>

<!-- Gib das Formular aus -->
<form method="POST">
    <?php foreach($days as $day){ ?>
        <label for="option_name_<?php echo $day; ?>"></label>
        <select name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>"> 
            <?php
                // Send query to database to get meals option
                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '".$day."'";
                $result = mysqli_query($conn, $sql);
                // Include each result as an option tag in the drop-down list
                while($row = mysqli_fetch_assoc($result)){
                    $option_name = $row['option_name'];
                    $data = $row['data'];
                    $price = $row['price'];
                    $option_id = $row['id'];
                    echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                    if(isset($_POST['option_name_'.$day]) && $_POST['option_name_'.$day] == $option_id){
                        $totalPreis += $price;
                        
                    }
                }
                
                
            ?>
        </select><br>
    <?php }?>
    <input type="submit" value="Berechnen">
</form>

<!-- Gib den Gesamtpreis aus -->
<p>Gesamtpreis: $<?php echo number_format($totalPreis, 2); ?></p>
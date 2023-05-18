<?php foreach($days as $day): ?>
    <div class="mb-1 form_height">
        <div class="disGrid floatLeft">
            <label class="dayLabel" for="option_name_<?php echo $day; ?>"><?php echo $day;?>:</label>
            <label class="colorGreen" id="monday" name="<?php echo $day; ?>">
                <?php 
                    $sql = "SELECT date FROM tbl_option WHERE day = '". $day."'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if($result->num_rows > 0 ){
                        $day_datum = $row['date'];
                        echo $day_datum;
                    }else{
                        echo '0000-00-00';
                    }
                    
                ?>
            </label>
        </div>
        <select class="w-50 h-50 tableRow" name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>" onChange="chImage<?php echo $day;?>(); calculateTotalPrice(this);">
            <?php 
                $sqlHoliday = "SELECT * FROM tbl_option ";
                $resultHoliday = mysqli_query($conn, $sqlHoliday);
                $rowHoliday = mysqli_fetch_assoc($resultHoliday);
                $holiday = $rowHoliday['day'];
                if($holiday == "Feiertag"){
                    // Send query to database to get School Classes
                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = 'Feiertag'";
                    $result = mysqli_query($conn, $sql);
                    // Include each result as an option tag in the drop-down list
                    $row = mysqli_fetch_assoc($result);
                    $option_name = $row['option_name'];
                    $data = $row['data'];
                    $price = $row['price'];
                    $option_id = $row['id'];
                    echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';  
                }
                if($$day == 1){
                    // Send query to database to get School Classes
                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE price = 0.00 AND  day = '" .$day."'";
                    $result = mysqli_query($conn, $sql);
                    // Include each result as an option tag in the drop-down list
                    $row = mysqli_fetch_assoc($result);
                    $option_name = $row['option_name'];
                    $data = $row['data'];
                    $price = $row['price'];
                    $option_id = $row['id'];
                    echo '<option value="' . $option_id . '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';  
                }else{
                    // Send query to database to get meals option
                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '" .$day."'";
                    $result = mysqli_query($conn, $sql);
                    // Include each result as an option tag in the drop-down list
                    while($row = mysqli_fetch_assoc($result)){
                        $option_name = $row['option_name'];
                        $data = $row['data'];
                        $tag = $row['day'];
                        $price = $row['price'];
                        $option_id = $row['id'];
                        echo '<option value="' . $option_id. '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                    }
                }
            ?>
        </select>
        <button type="submit" class="btn btn-warning h-50 mb-2" name="button" id="<?php echo $day;?>" value="<?php echo $day;?>"
                <?php 
                    if($$day == 1){ echo "disabled";}
                ?> >
                <h6>Ändern</h6>
        </button>
        
    </div>
<?php endforeach; ?>

<div class="text-center">
    <button type="submit" class="btn btn-warning btn-bestellen" id="bestellen" name="button" value="bestellen">
        <h6>Essen bestellen</h6>
    </button>
    <div name="guthaben" id="guthaben" class="error" ></div>
</div>
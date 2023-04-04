<?php
include ('../conn/db_conn.php');
$days = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag');

// // Check if the form has been submitted
// if(isset($_POST['submit'])){
//     // Get the selected options for each day
//     $options = array();
//     foreach($_POST as $key => $value){
//         if(strpos($key, 'option_name_') === 0){
//             $day = substr($key, strlen('option_name_'));
//             $options[$day] = $value;
//         }
//     }
//     // Store the options in the session
//     $_SESSION['options'] = $options;
//     // Redirect the user to the confirmation page
//     header('location: u_kontoZustand.php');
//     exit;
// }

// // Check if the totalPrice has been submitted through WebSocket
// if(isset($_SESSION['totalPrice'])){
//     $totalPrice = number_format($_SESSION['totalPrice'], 2);
//     unset($_SESSION['totalPrice']);
// }else{
//     $totalPrice = isset($_COOKIE['totalPrice']) ? $_COOKIE['totalPrice'] : 0.00;
// }
// if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){
//     $selectList = $_GET["id"];
// // Update the variable in PHP
//     $totalPrice = $selectList;
// }


// echo $totalPrice;

?>


<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <div class="col-lg-12" style="margin-top:20px;"></div>
            <form id="bestellForm" action="u_user_page.php" method="POST">
                <?php foreach($days as $day): ?>
                <label for="option_name_<?php echo $day; ?>" style="width:115px; font-weight:bold"><?php echo $day;?>:</label>
                <select class=" " name="option_name_<?php echo $day; ?>" id="option_name_<?php echo $day; ?>" onChange="chImage<?php echo $day;?>(); document.getElementById('totalPrice').value = calculateTotalPrice(this);">
                    <?php 
                    $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option WHERE day = '" .$day."'";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        $option_name = $row['option_name'];
                        $data = $row['data'];
                        $tag = $row['day'];
                        $price = $row['price'];
                        $option_id = $row['id'];
                        echo '<option value="' . $option_id. '">'. $option_id ."-". $option_name . "-" . $price . '€</option>';
                    } 
                    ?>
                </select>
                <?php endforeach; ?>
                <input type="text" name="totalPrice" id="totalPrice" value="">
            </form>
        </div>
        <!-- <div name="totalPrice" id="totalPrice"></div> -->


        <!-- Load the WebSocket script -->
        

        <script>
                        // Define an object to store the prices for each day
            var prices = {
                Montag: 0,
                Dienstag: 0,
                Mittwoch: 0,
                Donnerstag: 0,
                Freitag: 0
            };

            // Define a variable to store the total price
            var totalPrice = 0;

            // Define a function to calculate the total price based on the selected options
            function calculateTotalPrice() {
                totalPrice = 0;
                // Loop through each day
                for (var day in prices) {
                    // Get the selected option for this day
                    var selectList = document.getElementById("option_name_" + day);
                    var container = document.getElementById("totalPrice");
                    var selectedOption = selectList.options[selectList.selectedIndex];
                    
                    // Extract the price from the selected option
                    var price = parseFloat(selectedOption.text.split("-")[2].replace("€", ""));
                    
                    // Update the price for this day in the prices object
                    prices[day] = price;
                    
                    // Add the price to the total price
                    totalPrice += price;

                }
                // Update the total price display
                
                // Return the total price
                return totalPrice;  
            }
            // Call the calculateTotalPrice function whenever a new option is selected
            var selectLists = document.querySelectorAll("select");
            for (var i = 0; i < selectLists.length; i++) {
                selectLists[i].addEventListener("change", function() {
                    totalPrice = calculateTotalPrice();
                    document.cookie = 'totalPrice='+totalPrice;
                    
                });
            }
        </script>
    </body>
</html>
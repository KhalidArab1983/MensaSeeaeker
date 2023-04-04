
<?php

include('../conn/db_conn.php');


if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $option_name = $_POST['option_name'];
}else{
    $option_name = "";
}

if(isset($_GET['id'])){

    $selectList = $_GET["id"];
    // Update the variable in PHP
    $totalPrice = $selectList;

    echo "gesamt: ". $totalPrice;
}
?>



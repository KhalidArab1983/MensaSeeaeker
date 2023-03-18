<?php 
// include ('../conn/db_conn.php');

$host = "localhost"; // Database host
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "mensa_seeaeker"; // Database name
$db_port = "3306"; // Database Port


// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $db_port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";

$samstag = 'Saturday';
$current_day = date('l');
$bestell_status = 0;
        
if($current_day == $samstag){
    // Aktiviere den Bestellbutton für Samstag
    
    // Reset user's bestell_status at the start of every week
    // Add this task to a weekly cron job
    $sql = "UPDATE tbl_user SET bestell_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bestell_status);
    $stmt->execute();
    
}

?>
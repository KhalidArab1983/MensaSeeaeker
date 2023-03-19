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
$update_status = 0;
        
if($current_day == $samstag){
    // Aktiviere Alle Buttons am Samstag

    $sql = "UPDATE tbl_user SET bestell_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bestell_status);
    $stmt->execute();

    $updateSql = "UPDATE tbl_bestellstatus SET montag = ?, dienstag = ?, mittwoch = ?, donnerstag = ?, freitag = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssss", $update_status, $update_status, $update_status, $update_status, $update_status);
    $updateStmt->execute();
    
}

?>
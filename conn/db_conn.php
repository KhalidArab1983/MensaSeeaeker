<?php
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
?>

<?php
$host = "f"; // Database host
$username = "s"; // Database username
$password = ""; // Database password
$dbname = "s"; // Database name
$db_port = "3"; // Database Port


// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $db_port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>

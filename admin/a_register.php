<?php 
include('../conn/db_conn.php');

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}else{
    $userName = "";
    $email = "";
    $password = "";
}

if (isset($_POST['submit'])){
    // $sql = "SELECT u.userName, u.password, k.schule, k.klasse FROM tbl_user AS u INNER JOIN tbl_klasse AS k ON u.Klasse_id = k.klasse_id;";
    $sql = "INSERT INTO tbl_admin (userName, email, password) VALUES ('$userName', '$email', '$password')";
    if(mysqli_query($conn, $sql)){
        header("Location: a_login.php");
    }else{
        echo "Error: " . "<br>" . mysqli_error($conn);
	}
	mysqli_close($conn);
    
    
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
	<title>Mensa</title>
</head>
<body>
    <form action="a_register.php" method="post">
        <label for="userName">Benutzername:</label>
		<input type="text" name="userName" id="userName" required>
		<br>
        <label for="email">Email:</label>
		<input type="email" name="email" id="email" required>
		<br>
		<label for="password">Passwort:</label>
		<input type="password" name="password" id="password" required>
		<br>
		<input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
<?php
include('../conn/db_conn.php');

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $userName  = $_POST['userName'];
    $password  = $_POST['password'];

    //Check if the email and password are valid
    $sql = "SELECT * FROM tbl_admin WHERE userName = '$userName' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        //Start a session for the user
        $_SESSION['userName'] = $userName;
        $_SESSION['loggedin'] = true;
        header("Location: ../index.php");
        exit;
    }else{
        echo "Invalid username or password";
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
    <form action="a_login.php" method="post">
        <label for="userName">Benutzername:</label>
		<input type="text" name="userName" id="userName" required>
		<br>
		<label for="password">Passwort:</label>
		<input type="password" name="password" id="password" required>
		<br>
		<input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
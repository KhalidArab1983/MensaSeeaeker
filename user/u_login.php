<?php 
include('../conn/db_conn.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $klasse = $_POST['klasse'];
    $userName  = $_POST['userName'];
    $email = $_POST['email'];
    $password  = $_POST['password'];


    //Check if the email and password are valid
    $sql = "SELECT id, userName FROM tbl_user WHERE userName = '$userName' AND klasse = '$klasse' AND email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $row= mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $userName = $row['userName'];
        //Start a session for the user
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['userName'] = $userName;
        $_SESSION['loggedin'] = true;
        header("Location: ./u_user_page.php");
        exit;
    }else{
        echo "Invalid username or password";
    }
    mysqli_close($conn);
}


?>

<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
        
    </head>
    <body>
        <div>
            <!-- <img src="../images/banner1.jpg" width=100%> -->
            <div>
                <div class="left_side">
                    <img class="u_login_bild" src="../images/u_login_bild.jpg" width="100%" height="100%" style="border:1px 1px 3px 2px solid black;">
                </div>
                <div class="right_side">
                    <div class="welcomme">
                        <img src="../images/logo.jpg" width="20%" class="mb-4">
                        <h3>Wilkommen bei Mensa</h3>
                        <p>Die Zugangsdaten für Ihr Konto erhalten Sie von Ihrer Einrichtung.</p>
                    </div>
                    <div class="form_login">
                        <form action="u_login.php" method="post">
                            <div class="form-floating m-4">
                                <input type="text" class="form-control" name="klasse" id="klasse" placeholder="Klasse" required>
                                <label for="klasse">Klasse</label>
                            </div>
                            <div class="form-floating m-4">
                                <input type="text" class="form-control" name="userName" id="userName" placeholder="Benutzername" required>
                                <label for="userName">Benutzername</label>
                            </div>
                            <div class="form-floating m-4">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email-Adresse" required>
                                <label for="email">Email-Adresse</label>
                            </div>
                            <div class="form-floating m-4">
                                <input type="password" class="form-control" name="password" id="password"  placeholder="Kennwort" required>
                                <label for="floatingPassword">Kennwort</label>
                            </div>
                            <div class="text-center">
                                <input type="submit" name="submit" class="btn btn-primary m-2 w-25" value="Anmelden">
                            </div>
                        </form>
                    </div>
                    <a href="forget_password.php"><h6 class="text-center mt-4" style="text-decoration:underline;">Passwort vergessen?</h6></a>
                </div>
            </div>
        </div>
        
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
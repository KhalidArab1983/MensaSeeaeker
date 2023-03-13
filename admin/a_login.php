<?php
include('../conn/db_conn.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $adminName  = $_POST['adminName'];
    $password  = $_POST['password'];

    //Check if the email and password are valid
    $sql = "SELECT * FROM tbl_admin WHERE adminName = '$adminName' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $admin_id = $row['id'];
        $adminName = $row['adminName'];

        //Start a session for the admin
        session_start();
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['adminName'] = $adminName;
        $_SESSION['loggedin'] = true;
        header("Location: ../index.php");
        exit;
    }else{
        echo "Invalid adminname or password";
    }
    mysqli_close($conn);
}


?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
<body>
    
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link active" href="#"><h6>Anmelden</h6></a>
                </li>
                
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_register.php"><h6>Register</h6></a>
                </li>
            </ul>
        </div>
    </div>
    <img src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
    <nav class="navbar navbar-dark bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <hr style="height: 5px">
    
    <div class="container-fluid">
        <div class="container form_width">
            <h4 class="text-center">Als Admin anmelden</h4>
            <form action="a_login.php" method="post">
                <div class="form-floating m-5">
                    <input type="text" class="form-control" name="adminName" id="adminName" placeholder="Admin Name" required>
                    <label for="adminName">Benutzername</label>
                </div>
                <div class="form-floating m-5">
                    <input type="password" class="form-control" name="password" id="password"  placeholder="Kennwort" required>
                    <label for="floatingPassword">Kennwort</label>
                </div>
                <div class="text-center">
                    <input type="submit" name="submit" class="btn btn-primary m-5 w-25" value="Login">
                </div>
            </form>
        </div>
    </div>

    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
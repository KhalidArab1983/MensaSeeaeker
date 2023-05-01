<?php
include('../conn/db_conn.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $adminName  = $_POST['adminName'];
    $password  = $_POST['password'];
}else{
    $adminName  = "";
    $password  = "";
}

$errors = [
    'adminNameError' => '',
    'passwordError' => '',
    'invalidError' => ''
];


if (isset($_POST['submit'])){
    if(empty($adminName)){
        $errors['adminNameError'] = '* Bitte geben Sie den Adminname ein';
    }
    if(empty($password)){
        $errors['passwordError'] = '* Bitte geben Sie das Passwort ein';
    }
    if(!array_filter($errors)){

        $hashed_password = hash('sha256', $password);

        //Check if the email and password are valid
        $sql = "SELECT * FROM tbl_admin WHERE adminName = '$adminName' AND password = '$hashed_password'";
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
            header("Location: ./index.php");
            exit;
        }else{
            $errors['invalidError'] = 'Überprüfen Sie den Adminnamen oder das Passwort';
        }
        mysqli_close($conn);
    }
}


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
    
    <?php include ('./nav_includes/login_navbar.php') ?>

    <hr style="height: 5px">
    
    <div class="container-fluid">
        <div class="container form_width">
            <h4 class="text-center">Als Admin anmelden</h4>
            <div class="form-text text-center error"><?php echo $errors['invalidError'] ?></div>
            <form action="a_login.php" method="post">
                <div class="form-floating m-5">
                    <input type="text" class="form-control" name="adminName" id="adminName" placeholder="Admin Name" value="<?php echo $adminName ?>">
                    <label for="adminName">Admin Name</label>
                    <div class="form-text error"><?php echo $errors['adminNameError'] ?></div>
                </div>
                <div class="form-floating m-5">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Kennwort" value="<?php echo $password ?>">
                    <label for="floatingPassword">Kennwort</label>
                    <div class="form-text error"><?php echo $errors['passwordError'] ?></div>
                    
                </div>
                
                <div class="text-center">
                    <input type="submit" name="submit" class="btn btn-warning m-5 w-25" value="Anmelden">
                </div>
            </form>
        </div>
        <h6 class="text-center mt-4 pass_vergessen"><a href="a_forget_password.php">Passwort vergessen?</a></h6>
    </div>

    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
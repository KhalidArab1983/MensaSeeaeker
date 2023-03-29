<?php 
include('../conn/db_conn.php');

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $adminName = $_POST['adminName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
}else{
    $adminName = "";
    $email = "";
    $password = "";
    $confirm = "";
}

$errors = [
    'adminNameError' => '',
    'emailError' => '',
    'passwordError' => '',
    'confirmError' => '',
    'otherError' => ''
];


if (isset($_POST['submit'])){
    if(empty($adminName)){
        $errors['adminNameError'] = '* Bitte geben Sie einen Adminname ein';
    }
    if(empty($email)){
        $errors['emailError'] = '* Bitte geben Sie die E-mail ein';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['emailError'] = '* Bitte geben Sie eine gültige Email-Adresse ein';
    }
    if(empty($password)){
        $errors['passwordError'] = '* Bitte geben Sie das Passwort ein';
    }
    if(empty($confirm)){
        $errors['confirmError'] = '* Bitte bestätigen Sie das Passwort';
    }elseif($password != $confirm){
        $errors['confirmError'] = 'Die Passwörter stimmen nicht überein!';
    }
    if(!array_filter($errors)){

        $adminName =     mysqli_real_escape_string($conn, $_POST['adminName']);
        $email =        mysqli_real_escape_string($conn, $_POST['email']);
        $password =        mysqli_real_escape_string($conn, $_POST['password']);
        $confirm =        mysqli_real_escape_string($conn, $_POST['confirm']);

        $sql = "INSERT INTO tbl_admin (adminName, email, password) VALUES ('$adminName', '$email', '$password')";
        if(mysqli_query($conn, $sql)){
            header("Location: a_login.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
<body>

    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_login.php"><h6>Anmelden</h6></a>
                </li>
                
                <li class="nav-item item_besonder">
                    <a class="nav-link active" href="#"><h6>Register</h6></a>
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
            <h4 class="text-center">Admin Konto erstellen</h4>
            <form action="a_register.php" method="post">
                <div class="form-floating m-5">
                    <input type="text" class="form-control" name="adminName" id="adminName" placeholder="Admin Name" value="<?php echo $adminName ?>">
                    <label for="adminName">Admin Name<i class="mdi mdi-near-me:"></i></label>
                    <div class="form-text error"><?php echo $errors['adminNameError'] ?></div>
                </div>
                <div class="form-floating m-5">
                    <input type="text" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?php echo $email ?>">
                    <label for="email">Email adresse</label>
                    <div class="form-text error"><?php echo $errors['emailError'] ?></div>
                </div>
                <div class="form-floating m-5">
                    <input type="password" class="form-control" name="password" id="password"  placeholder="Kennwort" value="<?php echo $password ?>">
                    <label for="password">Kennwort</label>
                    <div class="form-text error"><?php echo $errors['passwordError'] ?></div>
                </div>
                <div class="form-floating m-5">
                    <input type="password" class="form-control" name="confirm" id="confirm"  placeholder="Kennwort Bestätigung" value="<?php echo $confirm ?>">
                    <label for="confirm">Kennwort Bestätigung</label>
                    <div class="form-text error"><?php echo $errors['confirmError'] ?></div>
                </div>
                <div class="text-center">
                    <input type="submit" name="submit" class="btn btn-primary m-5 w-25" value="Register">
                </div>
            </form>
        </div>
    </div>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
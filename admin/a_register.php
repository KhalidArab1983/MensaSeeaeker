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

        $hashed_password = hash('sha256', $password);

        // Füge den neuen Eintrag in die Tabelle tbl_admin ein und rufe die zugehörige ID ab
        $sqlAdmin = "INSERT INTO tbl_admin (adminName, email, password) VALUES ('$adminName', '$email', '$hashed_password')";
        if(mysqli_query($conn, $sqlAdmin)){
            $admin_id = mysqli_insert_id($conn);
            // Füge den neuen Eintrag in die Tabelle tbl_adminupdate ein und verknüpfe ihn mit dem neuen Eintrag in der Tabelle tbl_admin
            $sqlAdminUpdate = "INSERT INTO tbl_adminupdate (admin_id_update, adminName) SELECT tbl_admin.id, '$adminName' FROM tbl_admin WHERE tbl_admin.id = $admin_id";
            if(mysqli_query($conn, $sqlAdminUpdate)){
                header("Location: a_login.php");
            }else{
                echo "Error: " . "<br>" . mysqli_error($conn);
            }
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

    <?php include ('./nav_includes/login_navbar.php') ?>
    
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
                    <input type="submit" name="submit" class="btn btn-warning m-5 w-25" value="Register">
                </div>
            </form>
        </div>
    </div>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
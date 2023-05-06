<?php 
include('../conn/db_conn.php');
include('./a_log_reg_includes/a_register_inc.php');


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
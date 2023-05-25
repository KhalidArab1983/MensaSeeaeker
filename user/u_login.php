<?php 
include('../conn/db_conn.php');
include('./login_includes/u_login_inc.php');

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
            <!-- <img class="u_login_bild" src="../images/banner1.jpg" width=100%> -->
            <div>
                <div class="col-ms-12 col-md-12 col-lg-6 left_side">
                    <img class="u_login_bild" src="../images/u_login_bild.jpg" width="100%" height="100%">
                </div>
                <div class="col-ms-12 col-md-12 col-lg-6 right_side">
                    <div class="welcomme">
                        <img src="../images/logo.jpg" width="20%" class="mb-4">
                        <h3>Wilkommen bei Mensa</h3>
                        <p>Die Zugangsdaten für Ihr Konto erhalten Sie von Ihrer Einrichtung.</p>
                    </div>
                    <div class="form_login">
                        <form action="u_login.php" method="post">
                            <div class="form-floating m-4">
                                <input type="text" class="form-control" name="klasse" id="klasse" placeholder="Klasse" value="<?php echo $klasse ?>">
                                <label for="klasse">Klasse</label>
                                <div class="form-text error"><?php echo $errors['klasseError'] ?></div>
                            </div>
                            <div class="form-floating m-4">
                                <input type="text" class="form-control" name="userName" id="userName" placeholder="Benutzername" value="<?php echo $userName ?>">
                                <label for="userName">Benutzername</label>
                                <div class="form-text error"><?php echo $errors['userNameError'] ?></div>
                            </div>
                            <div class="form-floating m-4">
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email-Adresse" value="<?php echo $email ?>">
                                <label for="email">Email-Adresse</label>
                                <div class="form-text error"><?php echo $errors['emailError'] ?></div>
                            </div>
                            <div class="form-floating m-4">
                                <input type="password" class="form-control" name="password" id="password"  placeholder="Kennwort" value="<?php echo $password ?>">
                                <label for="floatingPassword">Kennwort</label>
                                <div class="form-text error"><?php echo $errors['passwordError'] ?></div>
                            </div>
                            <div class="form-text error"><?php echo $errors['invalidError'] ?></div>
                            <div class="text-center">
                                <input type="submit" name="submit" class="btn btn-warning m-2" value="Anmelden">
                            </div>
                        </form>
                    </div>
                    <h6 class="text-center mt-4 pass_vergessen"><a href="forget_password.php">Passwort vergessen?</a></h6>
                </div>
            </div>
        </div>
        
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
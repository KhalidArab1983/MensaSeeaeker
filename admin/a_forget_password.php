<?php
include('../conn/db_conn.php');
include('./pass_change_include/a_forget_pass_inc.php');
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
        <div class="text-center container">
            <div class="welcomme">
                <img src="../images/logo.jpg" width="20%" class="mb-4">
                <h3>Passwort zurücksetzen</h3>
                <p>Geben Sie Ihre E-Mail-Adresse ein, mit der Sie sich registriert haben, um den Bestätigungscode zu erhalten.</p>
            </div>
            <div class="form_login">
                <form method="post">
                    <div class="form-floating m-4">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email-Adresse">
                        <label for="email">Email-Adresse</label>
                    </div>
                    <div class="form-text error"><?php echo $errors['emailExistError'] ?></div>
                    <div class="form-text error"><?php echo $errors['emailEmptyError'] ?></div>
                    <div class="form-text error"><?php echo $errors['mailerError'] ?></div>
                    <div class="text-center">
                        <button type="submit" name="send_code" class="btn btn-warning m-2">Code senden</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
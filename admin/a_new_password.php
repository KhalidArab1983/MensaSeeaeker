<?php
include ('../conn/db_conn.php');
include ('./pass_change_includes/a_new_pass_inc.php');
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
                <p>Geben Sie den Code ein, der an Ihre E-Mail gesendet wurde, geben Sie ein neues Passwort ein<br> und bestätigen Sie es dann.</p>
            </div>
            <div class="form_login">
                <form method="post">
                    <div class="form-floating m-4">
                        <input type="number" class="form-control" id="code" name="code" placeholder="Bestätigungscode" value="<?php echo $code ?>">
                        <label for="code">Bestätigungscode</label>
                        <div class="form-text error"><?php echo $errors['codeError'] ?></div>
                    </div>

                    <div class="form-floating m-4">
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Neues Passwort" value="<?php echo $new_password ?>">
                        <label for="new_password">Neues Passwort</label>
                        <div class="form-text error"><?php echo $errors['newPassError'] ?></div>
                    </div>

                    <div class="form-floating m-4">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Passwort bestätigen" value="<?php echo $confirm_password ?>">
                        <label for="confirm_password">Passwort bestätigen</label>
                        <div class="form-text error"><?php echo $errors['confirmPassError'] ?></div>
                        <div class="form-text error"><?php echo $errors['otherError'] ?></div>
                    </div>
                    <button type="submit"  class="btn btn-warning m-2" name="confirm_code" >Bestätigen</button>
                </form>
            </div>
        </div>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
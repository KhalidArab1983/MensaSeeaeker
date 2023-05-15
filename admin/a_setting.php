<?php

include('../conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}
date_default_timezone_set("Europe/Berlin");

include ('./a_setting_includes/colorpass_change_inc.php');

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];

?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <title>Mensa</title>
    </head>
    <body>
        <?php include ('./nav_includes/navbar.php') ?>

        <hr style="height: 5px">
        <div class="container">
            <div class="card m-2">
                <h3>Die Farbe des Adminnamens ändern:</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <input type="color" class="form-control" name="adminColor" id="adminColor" value="<?php echo $adminColor ?>">
                    <button type="submit" class="btn btn-warning" name="button" value="save">Speichern</button>
                </form>
            </div>
                
            <div>
                <hr class="m-2 height5 colorBlue">
            </div>
            <div class="card m-2">
                <h4 class="mb-5">Passwort ändern:</h4>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <div class="form-group">                                    
                        <label class="fontBold">bisheriges Passwort:</label>
                        <input type="password" name="current_password" id="current_password" class="form-control tableRow" value="<?php echo $current_password; ?>">
                        <div class="form-text mb-3 error"><?php echo $errors['currentPassError'] ?></div>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold">Neues Passwort:</label>
                        <input type="password" name="new_password" id="new_password" class="form-control tableRow" value="<?php echo $new_password; ?>">
                        <div class="form-text mb-3 error"><?php echo $errors['newPassError'] ?></div>
                        <div class="form-text mb-3 error"><?php echo $errors['passRegexError'] ?></div>
                        
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold">Neues Passwort bestätigen:</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control tableRow" value="<?php echo $confirm_password; ?>">
                        <div class="form-text mb-3 error"><?php echo $errors['confirmPassError'] ?></div>
                        <div class="form-text mb-3 error"><?php echo $errors['otherError'] ?></div>
                    </div>
                    <div class="form-group">                                    
                        <button type="submit" class="btn btn-warning m-2" name="button" value="passSave">Speichern</button>
                        <button type="reset" class="btn btn-warning m-2" name="button" value="passCancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
                    </div>
                    <div class="form-text m-5 success"><?php echo $success ?></div>
                </form>
            </div>
        </div>


        <div class="marginBottom80">
        
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>

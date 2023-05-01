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

$errors = [
    'currentPassError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => '',
    'passRegexError' => ''
];
$success = '';

$current_password = "";
$new_password = "";
$confirm_password = "";

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST['button'])) {
        $button = $_POST['button'];
    }
    if (isset($_POST['adminColor'])) {
        $adminColor = $_POST['adminColor'];
    }
    if (isset($_POST['current_password'])) {
        $current_password = $_POST['current_password'];
    }
    if (isset($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
    }
    if (isset($_POST['confirm_password'])) {
        $confirm_password = $_POST['confirm_password'];
    }
    if($button == 'save'){
        $sql = "UPDATE tbl_admin SET color_hex = '$adminColor' WHERE id = '{$admin_id}'";
        if(mysqli_query($conn, $sql)){
            header("Location: a_setting.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }


    if($button == 'passSave'){
        if(empty($current_password)){
            $errors['currentPassError'] = "* Bitte geben Sie das aktuelles Passwort ein.";
        }
        if(empty($new_password)){
            $errors['newPassError'] = '* Bitte geben Sie das neues Passwort ein.';
        }
        // Add password validation
        $password_pattern = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/';
        if(!preg_match($password_pattern, $new_password)){
            $errors['passRegexError'] = '* Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Kleinbuchstaben, einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten.';
        }
        if(empty($confirm_password)){
            $errors['confirmPassError'] = '* Bitte bestätigen Sie das neues Passwort.';
        }
        if(!array_filter($errors)){
            $current_password =  mysqli_real_escape_string($conn, $_POST['current_password']);
            $new_password =      mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirm_password =  mysqli_real_escape_string($conn, $_POST['confirm_password']);

            $currentPassHashed = hash('sha256', $current_password);
            $newPassHashed = hash('sha256', $new_password);

            $sql = "SELECT password FROM tbl_admin WHERE id = $admin_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $db_password = $row['password'];

            if($currentPassHashed == $db_password){
                if($new_password == $confirm_password){
                    $sql ="UPDATE tbl_admin SET password = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $newPassHashed, $admin_id);
                    if(mysqli_stmt_execute($stmt)){
                        $success = "Das Passwort wurde erfolgreich geändert.";
                        header("Location: a_setting.php");
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                    }
                    
                }else{
                    $errors['otherError'] = "* Die Passwörter stimmen nicht überein!";
                }
            }else{
                $errors['otherError'] = "* Das aktuelles Passwort ist falsch";
            }
        }
    }
}

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
                <hr class="m-2" style="height: 5px; color:blue">
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
                        <button type="reset" class="btn btn-warning m-2" name="button" value="passCancel">Abrechen</button>
                    </div>
                    <div class="form-text m-5 success"><?php echo $success ?></div>
                </form>
            </div>
        </div>


        <div style="margin-bottom: 80px">
        
        </div>
        <footer class="fixed-bottom footer">
            <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
        </footer>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>

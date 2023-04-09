<?php
include ('../conn/db_conn.php');


if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
}else{
    $current_password = "";
    $new_password = "";
    $confirm_password = "";
}

$errors = [
    'currentPassError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => ''
];
// $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
// $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
// $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';


if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['button']) && $_POST['button'] == 'passSave'){
        if(empty($current_password)){
            $errors['currentPassError'] = "* Bitte geben Sie das aktuelles Passwort ein.";
        }
        if(empty($new_password)){
            $errors['newPassError'] = '* Bitte geben Sie das neues Passwort ein.';
        }
        if(empty($confirm_password)){
            $errors['confirmPassError'] = '* Bitte bestätigen Sie das neues Passwort.';
        }
        if(!array_filter($errors)){
            // echo "success";
            $errors['otherError'] = '* success';
            header("Location: u_kontoZustand.php");
        }
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
        <div class="card col-sm-12 col-md-4 m-1">
            <h4 class="mb-5">Passwort</h4>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <div class="form-group">                                    
                    <label style="font-weight:bold; width:400px">bisheriges Passwort:</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" value="<?php echo $current_password; ?>">
                    <div class="form-text mb-3 error"><?php echo $errors['currentPassError'] ?></div>
                    
                </div>
                <div class="form-group">                                    
                    <label style="font-weight:bold; width:400px">Neues Passwort:</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <div class="form-text mb-3 error"><?php echo $errors['newPassError'] ?></div>
                </div>
                <div class="form-group">                                    
                    <label style="font-weight:bold; width:400px">Neues Passwort bestätigen:</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <div class="form-text mb-3 error"><?php echo $errors['confirmPassError'] ?></div>
                    <div class="form-text mb-3 error"><?php echo $errors['otherError'] ?></div>
                </div>
                <div class="form-group">                                    
                    <button type="submit" class="btn btn-warning m-2" name="button" value="passSave">Speichern</button>
                    <button type="submit" class="btn btn-warning m-2" name="button" value="passCancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
                </div>
            </form>
        </div>
    </body>
</html>
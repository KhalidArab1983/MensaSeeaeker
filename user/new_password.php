<?php
include ('../conn/db_conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $code = $_POST['code'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
}else{
    $code = "";
    $new_password = "";
    $confirm_password = "";
}
$errors = [
    'codeError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => ''
];
// Prüfen, ob das Formular gesendet wurde
if(isset($_POST['confirm_code'])) {
    // Bestätigungscode und neue Passwörter aus dem Formular erhalten
    
    if(empty($code)){
        $errors['codeError'] = '* Bitte geben Sie den Code ein, den Sie per E-Mail erhalten haben.';
    }
    // Add password validation here
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if(!preg_match($password_pattern, $new_password)){
        $errors['newPassError'] = '* Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Kleinbuchstaben, einen Großbuchstaben, eine Zahl und ein Sonderzeichen enthalten.';
    }
    if(empty($new_password)){
        $errors['newPassError'] = '* Bitte geben Sie das Passwort ein.';
    }
    if(empty($confirm_password)){
        $errors['confirmPassError'] = '* Bitte bestätigen Sie das Passwort.';
    }
    if(!array_filter($errors)){
        
        $code =              mysqli_real_escape_string($conn, $_POST['code']);
        $new_password =      mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password =  mysqli_real_escape_string($conn, $_POST['confirm_password']);

        // Hash the password
        $hashed_password = hash('sha256', $new_password);

        // Hier wird der Code einfach aus der Session-Variable gelesen
        session_start();
        $saved_code = $_SESSION['forgot_password_code'];
        $saved_email = $_SESSION['forgot_password_email'];

        $sql = "SELECT email FROM tbl_user WHERE email = '$saved_email'";
        $result = $conn->query($sql);

        if($code == $saved_code) {
            // Code korrekt, Passwörter überprüfen und aktualisieren
            if($new_password == $confirm_password) {
                // Passwörter stimmen überein, aktualisieren in der Datenbank oder einer Datei
                if($result->num_rows > 0){
                    $sql ="UPDATE tbl_user SET password = ? WHERE email = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $saved_email);
                    if(mysqli_stmt_execute($stmt)){
                        header("Location: u_login.php");
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                    }
                    // Session-Variable für den Code und die E-Mail-Adresse löschen
                    unset($_SESSION['forgot_password_code']);
                    unset($_SESSION['forgot_password_email']);
                }else{
                    $errors['otherError'] = "Die eingegebene Email-Adresse Existiert nicht";
                }
            } else {
                // Passwörter stimmen nicht überein, Fehlermeldung anzeigen
                $errors['otherError'] = "Die Passwörter stimmen nicht überein!";
            }
        } else {
            // Code falsch, Fehlermeldung anzeigen
            $errors['otherError'] = "Der eingegebene Code ist falsch!";
        }
    }
}

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
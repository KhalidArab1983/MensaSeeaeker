<?php 

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
    $password_pattern = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/';
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

        $sql = "SELECT email FROM tbl_admin WHERE email = '$saved_email'";
        $result = $conn->query($sql);

        if($code == $saved_code) {
            // Code korrekt, Passwörter überprüfen und aktualisieren
            if($new_password == $confirm_password) {
                // Passwörter stimmen überein, aktualisieren in der Datenbank oder einer Datei
                if($result->num_rows > 0){
                    $sql ="UPDATE tbl_admin SET password = ? WHERE email = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $saved_email);
                    if(mysqli_stmt_execute($stmt)){
                        header("Location: a_login.php");
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
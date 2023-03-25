<?php
include('../conn/db_conn.php');
// Benötigte Dateien einbinden
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('../vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('../vendor/phpmailer/phpmailer/src/Exception.php');

require '../vendor/autoload.php';

$errors = [
    'emailExistError' => '',
    'emailEmptyError' => '',
];
// Prüfen, ob das Formular gesendet wurde
if(isset($_POST['send_code'])) {
    // E-Mail-Adresse des Benutzers aus dem Formular erhalten
    $email = $_POST['email'];
    if(empty($email)){
        $errors['emailEmptyError'] = '* Bitte geben Sie die E-mail ein';
    }
    if(!array_filter($errors)){
        $email =    mysqli_real_escape_string($conn, $_POST['email']);
    
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if($user){
            // Generieren eines zufälligen Codes
            $code = rand(100000, 999999);

            // Speichern des Codes in der Datenbank oder einer Datei
            // Hier wird der Code einfach in einer Session-Variable gespeichert
            session_start();
            $_SESSION['forgot_password_code'] = $code;
            $_SESSION['forgot_password_email'] = $email;

            // Senden des Codes per E-Mail
            // E-Mail-Einstellungen konfigurieren
            $mail = new PHPMailer();
            try {
                // $mail->SMTPDebug = 2; 
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // SMTP-Host deines E-Mail-Providers
                $mail->Port = 587;  // Port deines SMTP-Servers
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'rojdaf8@gmail.com';  // Deine E-Mail-Adresse
                $mail->Password = 'covyppaonfovaywk';  // Dein E-Mail-Passwort
                $mail->setFrom('rojdaf8@gmail.com', 'noReply');  // Absender-Adresse und Name
                $mail->addAddress($email);  // Empfänger-Adresse
                // $mail->addAttachment('../images/logo.jpg');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Bestätigungscode zum Zurücksetzen des Passworts';  // Betreff der E-Mail
                $mail->Body = "<h3>Ihr Bestätigungscode zum Zurücksetzen des Passworts lautet:</h3><h1 style='color:blue'> $code</h1>";  // Inhalt der E-Mail

                // E-Mail senden
                $mail->send();
                echo 'Email sent successfully';
                header('Location: new_password.php');
            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        }else{
            // E-Mail-Adresse existiert nicht in der Datenbank
            $errors['emailExistError'] = "Die E-Mail-Adresse $email existiert nicht in der Datenbank.";
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
                <p>Geben Sie Ihre E-Mail-Adresse ein, mit der Sie sich registriert haben, um den Bestätigungscode zu erhalten.</p>
            </div>
            <div class="form_login">
                <form method="post">
                    <div class="form-floating m-4">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email-Adresse">
                        <label for="email">Email-Adresse</label>
                    </div>
                    <div class="form-text error"><?php echo $errors['emailExistError'] ?></div>
                    
                    <div class="text-center">
                        <button type="submit" name="send_code" class="btn btn-warning m-2 w-25">Code senden</button>
                    </div>
                    <div class="form-text error"><?php echo $errors['emailEmptyError'] ?></div>
                </form>
            </div>
        </div>

        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/script.js"></script>
    </body>
</html>
<?php 

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
    'mailerError' => '',
];
// Prüfen, ob das Formular gesendet wurde
if(isset($_POST['send_code'])) {
    // E-Mail-Adresse des Benutzers aus dem Formular erhalten
    $email = $_POST['email'];
    if(empty($email)){
        $errors['emailEmptyError'] = '* Bitte geben Sie Ihre E-Mail-Adresse ein, um den Code zu erhalten.';
    }
    if(!array_filter($errors)){
        $email =    mysqli_real_escape_string($conn, $_POST['email']);
    
        $stmt = $conn->prepare("SELECT * FROM tbl_admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if($user){
            // Generieren eines zufälligen Codes
            $code = rand(100000, 999999);

            // Hier wird der Code einfach in einer Session-Variable gespeichert
            session_start();
            $_SESSION['forgot_password_code'] = $code;
            $_SESSION['forgot_password_email'] = $email;

            // Senden des Codes per E-Mail
            // E-Mail-Einstellungen konfigurieren
            $mail = new PHPMailer();
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // SMTP-Host deines E-Mail-Providers
                $mail->Port = 587;  // Port deines SMTP-Servers
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'rojdaf8@gmail.com';  // Deine E-Mail-Adresse
                $mail->Password = 'mawvchdvumwunbgp';  // Dein E-Mail-Passwort
                $mail->setFrom('rojdaf8@gmail.com', 'noReply');  // Absender-Adresse und Name
                $mail->addAddress($email);  // Empfänger-Adresse
                // $mail->addAttachment('../images/logo.jpg');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Bestätigungscode zum Zurücksetzen des Passworts';  // Betreff der E-Mail
                $mail->Body = "<h3>Ihr Bestätigungscode zum Zurücksetzen des Passworts lautet:</h3><h1 class='code'> $code</h1>";  // Inhalt der E-Mail

                // E-Mail senden
                $mail->send();
                echo 'Email sent successfully';
                header('Location: a_new_password.php');
            } catch (Exception $e) {
                $errors['mailerError'] = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            }
        }else{
            // E-Mail-Adresse existiert nicht in der Datenbank
            $errors['emailExistError'] = "Die E-Mail-Adresse <span class='not_exist_email'> $email</span> ist nicht registriert.";
        }
    }
}

?>
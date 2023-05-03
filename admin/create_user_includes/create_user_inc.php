<?php 
// Benötigte Dateien einbinden
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('../vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('../vendor/phpmailer/phpmailer/src/Exception.php');

require '../vendor/autoload.php';


$aktiv_ab = date('d.m.Y');
$error = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (isset($_POST['userName1'])) {
        $userName1 = $_POST['userName1'];
    }
    if (isset($_POST['klasse'])) {
        $klasse = $_POST['klasse'];
    }
    if (isset($_POST['schule'])) {
        $schule = $_POST['schule'];
    }
    if (isset($_POST['plz'])) {
        $plz = $_POST['plz'];
    }
    if (isset($_POST['ort'])) {
        $ort = $_POST['ort'];
    }
    if (isset($_POST['userName'])) {
        $userName = $_POST['userName'];
    }
    if (isset($_POST['firstName'])) {
        $firstName = $_POST['firstName'];
    }
    if (isset($_POST['lastName'])) {
        $lastName = $_POST['lastName'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['phone'])) {
        $phone = $_POST['phone'];
    }
    if (isset($_POST['adresse'])) {
        $adresse = $_POST['adresse'];
    }
    if (isset($_POST['ortsteil'])) {
        $ortsteil = $_POST['ortsteil'];
    }
    if (isset($_POST['birthday'])) {
        $birthday = $_POST['birthday'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (isset($_POST['button'])) {
        $button = $_POST['button'];
    }
    if (isset($_POST['id'])) {
        $user_id = $_POST['id'];
    }
    if (isset($_POST['admin_id'])) {
        $admin_id = $_POST['admin_id'];
    }

    if(isset($_POST['klasse_submitted'])){
        $sql= "INSERT INTO tbl_klasse (schule, klasse, admin_id) VALUES ('$schule', '$klasse','{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    if(isset($_POST['ort_submitted'])){
        $sql= "INSERT INTO tbl_ort (plz, ort, admin_id) VALUES ('$plz', '$ort', '{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    // Hash the password
    $hashed_password = hash('sha256', $password);

    if($button == "insert"){
        $sql = "INSERT INTO tbl_user (klasse, plz, userName, firstName, lastName, email, phone, adresse, ortsteil, birthday, aktiv_ab, password, admin_id)
                VALUES ('$klasse', '$plz', '$userName', '$firstName', '$lastName', '$email', '$phone', '$adresse', '$ortsteil', '$birthday', '$aktiv_ab', '$hashed_password', '{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            $mail = new PHPMailer();
            try {
                // $mail->SMTPDebug = 2; 
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
                $mail->Subject = 'Zugangsdaten';  // Betreff der E-Mail
                $mail->Body = "<h3>Ihre Zugangsdaten für die Essensbestellplattform der Seeäckerschule lauten wie folgt:</h3><br>
                                klasse: <h3 style='color:blue'> $klasse</h3><br>
                                Benutzername: <h3 style='color:blue'> $userName</h3><br>
                                Email: <h3 style='color:blue'> $email</h3><br>
                                passwort: <h3 style='color:blue'> $password</h3><br>
                                Geben Sie die Daten im Anmeldeformular über den folgenden Link ein <a>abowisam.com</a><br>
                                Bitte ändern Sie Ihr Passwort sofort nach der ersten Anmeldung.";  // Inhalt der E-Mail
                // E-Mail senden
                $mail->send();
                echo 'Email sent successfully';
                header("Location: create_user.php");
            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    if($button == "update"){
        if(empty($user_id) || $user_id == "Benutzer Name auswählen..."){
            $error = "Wählen Sie bitte einen Benutzer aus!";
        }else{
            if(empty($klasse)) {
                $query = "SELECT klasse FROM tbl_user WHERE id= '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $klasse = $row['klasse'];
            }
            if(empty($plz)) {
                $query = "SELECT plz FROM tbl_user WHERE id= '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $plz = $row['plz'];
            }
            if(empty($userName)) {
                $query = "SELECT userName FROM tbl_user WHERE id= '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $userName = $row['userName'];
            }
            if(empty($firstName)) {
                $query = "SELECT firstName FROM tbl_user WHERE id= '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $firstName = $row['firstName'];
            }
            if(empty($lastName)) {
                $query = "SELECT lastName FROM tbl_user WHERE id= '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $lastName = $row['lastName'];
            }
            if(empty($email)){
                $query = "SELECT email FROM tbl_user WHERE id = '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $email = $row['email'];
            }
            if(empty($phone)){
                $query = "SELECT phone FROM tbl_user WHERE id = '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $phone = $row['phone'];
            }
            if(empty($adresse)){
                $query = "SELECT adresse FROM tbl_user WHERE id = '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $adresse = $row['adresse'];
            }
            if(empty($ortsteil)){
                $query = "SELECT ortsteil FROM tbl_user WHERE id = '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $ortsteil = $row['ortsteil'];
            }
            if(empty($password)){
                $query = "SELECT password FROM tbl_user WHERE id = '{$user_id}'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $password = $row['password'];
            }
            
            // Speichern der ursprünglichen Benutzerdaten in einer Variable
            $query = "SELECT * FROM tbl_user WHERE id = $user_id";
            $result = mysqli_query($conn, $query);
            $original_data = mysqli_fetch_assoc($result);
            // Überprüfen, welche Felder geändert wurden und sie in der Tabelle tbl_user_changes speichern
            foreach ($_POST as $key => $value) {
                if ($key != "user_id" && $key != "button") {
                    if ($value != $original_data[$key]) {
                        $field_name = mysqli_real_escape_string($conn, $key);
                        $old_value = mysqli_real_escape_string($conn, $original_data[$key]);
                        $new_value = mysqli_real_escape_string($conn, $value);
                        $change_date = date("Y-m-d H:i:s");

                        // Überprüft, ob die Felder leer sind, sodass sie übersprungen werden und die Abfrage nicht ausgeführt wird.
                        if(!empty($new_value)){
                            $queryChange = "INSERT INTO tbl_user_changes (admin_id, user_id, field_name, old_value, new_value, change_date) VALUES ($admin_id, $user_id, '$field_name', '$old_value', '$new_value', '$change_date')";
                            mysqli_query($conn, $queryChange);
                        }
                    }
                }
            }
            $sql = "UPDATE tbl_user SET klasse = ?, plz = ?, firstName = ?, lastName = ?, email = ?, phone = ?, adresse = ?, ortsteil = ?, admin_id_update = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $klasse, $plz, $firstName, $lastName, $email, $phone, $adresse, $ortsteil, $admin_id, $user_id);
            if(mysqli_stmt_execute($stmt)){
                header("Location: create_user.php");
            }else{
                echo "Error: " . "<br>" . mysqli_error($conn);
            }
        }
    }
    if($button == "delete"){
        $sql = "DELETE u, b, s, e, a, c FROM tbl_user u
                LEFT JOIN tbl_bestellung b ON u.id = b.user_id
                LEFT JOIN tbl_bestellstatus s ON u.id = s.user_id
                LEFT JOIN tbl_einzahlung e ON u.id = e.user_id
                LEFT JOIN tbl_auszahlung a ON u.id = a.user_id
                LEFT JOIN tbl_user_changes c ON u.id = c.user_id
                WHERE u.id = '{$user_id}'";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];

?>
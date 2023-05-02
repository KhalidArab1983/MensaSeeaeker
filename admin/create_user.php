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


<!DOCTYPE html>
<html lang="de">
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

    <div class="container-fluid">
        <div class="container-fluid text-center">
            <div>
                <div class="mb-3 col-12" style="float: left;">
                    <!-- Form to add a new school class -->
                    <div>
                        <h4>Neue Klasse einfügen</h4>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="klasse" id="klasse" placeholder="Klasse" required>
                            <!-- <label for="klasse">Klasse</label> -->
                        </div>
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="schule" id="schule" placeholder="Schule" required>
                            <!-- <label for="schule">Schule</label> -->
                        </div>
                        <div>
                            <input type="hidden" name="klasse_submitted" value="1">
                            <input type="submit" class="btn btn-warning" value="Klasse Erstellen">
                        </div>
                    </form>
                </div>

                <div class="mb-3 col-12">
                    <!-- Form to add a new postal code and city -->
                    <div>
                        <h4>Neues Ort einfügen</h4>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="plz" id="plz" placeholder="PLZ"  onkeyup="sucheStadt()" required>
                            <!-- <label for="plz">PLZ</label> -->
                        </div>
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="ort" id="ort" placeholder="Ort" required>
                            <!-- <label for="ort">Ort</label> -->
                        </div>
                        <div>
                            <input type="hidden" name="ort_submitted" value="1">
                            <input type="submit" class="btn btn-warning" value="Ort Erstellen">
                        </div>
                    </form>
                </div>
            </div>

            <hr style="height: 10px;">

            <!-- Form to add a new student -->
            <div class="createUserForm">
                <h4>SchülerInnen Hinzufügen, Aktualisieren oder Löschen </h4>
                <span class="tooltips" onclick="showHint()">info?</span>
                <div class="hint" id="hint">
                    1. Suchen Sie nach dem Benutzer, indem Sie seinen Namen in das erste Feld eingeben.(Sie können einen beliebigen Teil des Benutzernamentextes eingeben)<br>
                    2. Wählen Sie dann den Benutzer aus der Dropdown-Liste im nächsten Feld aus.<br>
                    2.1. Um den ausgewählten Benutzer und alle seine Daten zu löschen, klicken Sie auf die Schaltfläche „Löschen“.<br>
                    2.2. Um die Benutzerdaten zu aktualisieren, füllen Sie die zu ändernden Felder aus und klicken Sie auf die Schaltfläche „Aktualisieren“.<br>
                    3. Das dritte Feld zur Auswahl der Klasse aus der Dropdown-Liste.<br>
                    4. Das vierte Feld zur Auswahl der Stadt aus der Dropdown-Liste.<br>
                    5. Die Felder Benutzername und Passwort werden automatisch ausgefüllt.<br>
                    6. Um einen neuen Benutzer hinzuzufügen, füllen Sie alle erforderlichen Felder aus und klicken Sie auf die Schaltfläche "Hinzufügen".<br>
                    7. Um diesen Dialog wieder auszublenden, klicken Sie erneut auf das Wort „info?“.
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <div class="mb-1">
                        <input type="text" class="form-control" id="searchInput" onkeyup="filterOptions()" placeholder="nach einem Benutzer suchen...">
                    </div>
                    <div class="mb-1">
                        <select name="id" class="form-control" id="optionList">
                            <option>Benutzer Name auswählen...</option>
                            <?php
                                // Send query to database to get users
                                $sql = "SELECT id, userName FROM tbl_user";
                                $result = mysqli_query($conn, $sql);
                                // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['id']."'>" .$row['id']. "_".$row['userName'] . "</option>";
                                }
                            ?>
                        </select>
                        <div class="form-text error"><?php echo $error;?></div>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="klasse">Klasse:</label> -->
                        <select class="form-control" name="klasse" id="klasse">
                            <option></option>
                            <?php
                                // Send query to database to get School Classes
                                $sql = "SELECT * FROM tbl_klasse";
                                $result = mysqli_query($conn, $sql);
                                // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['klasse']."'>" . $row['klasse'] . "</option>";
                                }
                                mysqli_close($conn);
                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="plz">PLZ:</label> -->
                        <select class="form-control" name="plz" id="plz">
                            <option></option>
                            <?php 
                                include ('../conn/db_conn.php');
                                // Send query to database to get postal code and cities
                                $sql = "SELECT * FROM tbl_ort";
                                $result = mysqli_query($conn, $sql);
                                // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['plz']."'>" . $row['plz'] . " - " . $row['ort'] . "</option>";
                                }
                                mysqli_close($conn);
                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="userName">Benutzername:</label> -->
                        <input type="text" class="form-control" name="userName" id="userName" placeholder="* Benutzername wird automatisch ausgefüllt" readonly>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="firstName">Vorname:</label> -->
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="* Vorname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="lastName">Nachname:</label> -->
                        <input type="text" class="form-control" name="lastName" id="lastName"  placeholder="* Nachname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="email">Email:</label> -->
                        <input type="email" class="form-control" name="email" id="email" placeholder="* Email Adresse">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="phone">Handy:</label> -->
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="* Handynummer">
                    </div>
                    
                    <div class="mb-1">
                        <!-- <label for="adresse">Anschrift:</label> -->
                        <input type="text" class="form-control" name="adresse" id="adresse" placeholder="* Straße, Haus Nr.">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="ortsteil">Ortsteil:</label> -->
                        <input type="text" class="form-control" name="ortsteil" id="ortsteil" placeholder="* Ortsteil">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="birthday">Geburtsdatum:</label> -->
                        <input type="text" class="form-control" name="birthday" id="birthday"  placeholder="* 01.01.1900" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="password">Kennwort:</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="* Kennwort wird automatisch ausgefüllt" readonly>
                    </div>
                    <button type="submit" class="btn btn-warning" name="button" value="insert">Hinzufügen</button>
                    <button type="submit" class="btn btn-warning" name="button" value="update">Aktualisieren</button>
                    <button type="submit" class="btn btn-warning" name="button" value="delete"  onclick="return confirm('Möchten Sie diesen Benutzer wirklich löschen?')">Löschen</button>
                </form>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 80px">
    
    </div>
    <footer class="fixed-bottom footer">
        <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
    </footer>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
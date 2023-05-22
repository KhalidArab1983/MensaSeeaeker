<?php

// Meine Daten Seite
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

    if (isset($_POST['adresse'])) {
        $adresseP = $_POST['adresse'];
    }
    if (isset($_POST['plz'])) {
        $plzP = $_POST['plz'];
    }
    if (isset($_POST['ort'])) {
        $ortP = $_POST['ort'];
    }
    if (isset($_POST['ortsteil'])) {
        $ortsteilP = $_POST['ortsteil'];
    }
    if (isset($_POST['phone'])) {
        $phoneP = $_POST['phone'];
    }
    if (isset($_POST['email'])) {
        $emailP = $_POST['email'];
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

    if(isset($_POST['passwordForm'])){
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

            $sql = "SELECT password FROM tbl_user WHERE id = $user_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $db_password = $row['password'];

            if($currentPassHashed == $db_password){
                if($new_password == $confirm_password){
                    $sql ="UPDATE tbl_user SET password = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $newPassHashed, $user_id);
                    if(mysqli_stmt_execute($stmt)){
                        $success = "Das Passwort wurde erfolgreich geändert.";
                        header("Location: u_user_page.php");
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
    if(isset($_POST['adresseForm'])){
        $sql = "UPDATE tbl_user SET plz = ?, email = ?, phone = ?, adresse = ?, ortsteil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $plzP, $emailP, $phoneP, $adresseP, $ortsteilP, $user_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: u_user_page.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        // mysqli_close($conn);
    }
}

// Um Benutzerdaten abzurufen und in den Eingabefeldern anzuzeigen
$sql = "SELECT * FROM tbl_user u  
        INNER JOIN tbl_ort o ON u.plz = o.plz
        WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$userName = $row['userName'];
$lastName = $row['lastName'];
$firstName = $row['firstName'];
$birthday = $row['birthday'];
$aktiv_ab = $row['aktiv_ab'];
$klasse = $row['klasse'];
$adresse = $row['adresse'];
$plz = $row['plz'];
$ort = $row['ort'];
$ortsteil = $row['ortsteil'];
$phone = $row['phone'];
$email = $row['email'];
// Ende Meine Daten Seite

?>
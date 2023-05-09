<?php 

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

?>
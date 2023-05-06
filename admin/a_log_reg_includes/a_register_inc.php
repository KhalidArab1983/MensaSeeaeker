<?php 
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $adminName = $_POST['adminName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
}else{
    $adminName = "";
    $email = "";
    $password = "";
    $confirm = "";
}

$errors = [
    'adminNameError' => '',
    'emailError' => '',
    'passwordError' => '',
    'confirmError' => '',
    'otherError' => ''
];


if (isset($_POST['submit'])){
    if(empty($adminName)){
        $errors['adminNameError'] = '* Bitte geben Sie einen Adminname ein';
    }
    if(empty($email)){
        $errors['emailError'] = '* Bitte geben Sie die E-mail ein';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['emailError'] = '* Bitte geben Sie eine gültige Email-Adresse ein';
    }
    if(empty($password)){
        $errors['passwordError'] = '* Bitte geben Sie das Passwort ein';
    }
    if(empty($confirm)){
        $errors['confirmError'] = '* Bitte bestätigen Sie das Passwort';
    }elseif($password != $confirm){
        $errors['confirmError'] = 'Die Passwörter stimmen nicht überein!';
    }
    if(!array_filter($errors)){


        $adminName =     mysqli_real_escape_string($conn, $_POST['adminName']);
        $email =        mysqli_real_escape_string($conn, $_POST['email']);
        $password =        mysqli_real_escape_string($conn, $_POST['password']);
        $confirm =        mysqli_real_escape_string($conn, $_POST['confirm']);

        $hashed_password = hash('sha256', $password);

        // Füge den neuen Eintrag in die Tabelle tbl_admin ein und rufe die zugehörige ID ab
        $sqlAdmin = "INSERT INTO tbl_admin (adminName, email, password) VALUES ('$adminName', '$email', '$hashed_password')";
        if(mysqli_query($conn, $sqlAdmin)){
            $admin_id = mysqli_insert_id($conn);
            // Füge den neuen Eintrag in die Tabelle tbl_adminupdate ein und verknüpfe ihn mit dem neuen Eintrag in der Tabelle tbl_admin
            $sqlAdminUpdate = "INSERT INTO tbl_adminupdate (admin_id_update, adminName) SELECT tbl_admin.id, '$adminName' FROM tbl_admin WHERE tbl_admin.id = $admin_id";
            if(mysqli_query($conn, $sqlAdminUpdate)){
                header("Location: a_login.php");
            }else{
                echo "Error: " . "<br>" . mysqli_error($conn);
            }
        }
        
        mysqli_close($conn);
    }
}
?>
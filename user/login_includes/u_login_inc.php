<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $klasse = $_POST['klasse'];
    $userName  = $_POST['userName'];
    $email = $_POST['email'];
    $password  = $_POST['password'];
}else{
    $klasse = "";
    $userName  = "";
    $email = "";
    $password  = "";
}
$errors = [
    'klasseError' => '',
    'userNameError' => '',
    'emailError' => '',
    'passwordError' => '',
    'invalidError' => ''
];


if (isset($_POST['submit'])){
    if(empty($klasse)){
        $errors['klasseError'] = '* Bitte geben Sie die Klasse ein';
    }
    if(empty($userName)){
        $errors['userNameError'] = '* Bitte geben Sie den Benutzername ein';
    }
    if(empty($email)){
        $errors['emailError'] = '* Bitte geben Sie die E-mail ein';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['emailError'] = '* Bitte geben Sie eine gültige Email-Adresse ein';
    }
    if(empty($password)){
        $errors['passwordError'] = '* Bitte geben Sie das Passwort ein';
    }
    if(!array_filter($errors)){

        // Hash the password
        $hashed_password = hash('sha256', $password);

        //Check if the email and password are valid
        $sql = "SELECT * FROM tbl_user WHERE userName = '$userName' AND klasse = '$klasse' AND email = '$email' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            $row= mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            $userName = $row['userName'];


            //Start a session for the user
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['userName'] = $userName;
            $_SESSION['loggedin'] = true;
            header("Location: ./u_user_page.php");
            exit;

        }else{
            $errors['invalidError'] = 'Überprüfen Sie die Eintragsinformationen, die Klasse, den Benutzernamen, die E-Mail-Adresse oder das Passwort';
        }
        mysqli_close($conn);
    }
}


?>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Get email and password from the Form data
    $adminName  = $_POST['adminName'];
    $password  = $_POST['password'];
}else{
    $adminName  = "";
    $password  = "";
}

$errors = [
    'adminNameError' => '',
    'passwordError' => '',
    'invalidError' => ''
];


if (isset($_POST['submit'])){
    if(empty($adminName)){
        $errors['adminNameError'] = '* Bitte geben Sie den Adminname ein';
    }
    if(empty($password)){
        $errors['passwordError'] = '* Bitte geben Sie das Passwort ein';
    }
    if(!array_filter($errors)){

        $hashed_password = hash('sha256', $password);

        //Check if the email and password are valid
        $sql = "SELECT * FROM tbl_admin WHERE adminName = '$adminName' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $admin_id = $row['id'];
            $adminName = $row['adminName'];

            //Start a session for the admin
            session_start();
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['adminName'] = $adminName;
            $_SESSION['loggedin'] = true;
            header("Location: ./index.php");
            exit;
        }else{
            $errors['invalidError'] = 'Überprüfen Sie den Adminnamen oder das Passwort';
        }
        mysqli_close($conn);
    }
}


?>
<?php
include ('../conn/db_conn.php');


$errors = [
    'currentPassError' => '',
    'newPassError' => '',
    'confirmPassError' => '',
    'otherError' => ''
];

$current_password = "";
$new_password = "";
$confirm_password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
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


    if(isset($_POST['passwordForm'])){
        if(empty($current_password)){
            $errors['currentPassError'] = "* Bitte geben Sie das aktuelles Passwort ein.";
        }
        if(empty($new_password)){
            $errors['newPassError'] = '* Bitte geben Sie das neues Passwort ein.';
        }
        if(empty($confirm_password)){
            $errors['confirmPassError'] = '* Bitte bestätigen Sie das neues Passwort.';
        }
        if(!array_filter($errors)){
            // echo "success";
            $errors['otherError'] = '* success';
            header("Location: u_kontoZustand.php");
        }
    }
    if(isset($_POST['adresseForm'])){
        $sql = "UPDATE tbl_user SET plz = ?, email = ?, phone = ?, adresse = ?, ortsteil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $plzP, $emailP, $phoneP, $adresseP, $ortsteilP, $user_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: u_kontoZustand.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        // mysqli_close($conn);
    }
}

var_dump($_SERVER["REQUEST_METHOD"]);

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
?>

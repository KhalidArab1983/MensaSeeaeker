<?php 
$sonntag = 'Sunday';
$current_day = date('l');
$week_count = date('W');



$userSql ="SELECT id FROM tbl_user";
$result = mysqli_query($conn, $userSql);
$userRow = mysqli_fetch_assoc($result);
$user_id = $userRow['id'];

$sql = "SELECT color_hex FROM tbl_admin WHERE id = '{$admin_id}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminColor = $row['color_hex'];


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $einzahlungsbetrag = $_POST['einzahlungsbetrag'];
    $userEinzahlung = $_POST['userEinzahlung'];
    if(isset($_POST["button"]) && $_POST["button"] == "einzahlen"){
        $sql = "INSERT INTO tbl_einzahlung (einzahlung, user_id, admin_id) VALUES ($einzahlungsbetrag, $userEinzahlung, $admin_id)";
        if(mysqli_query($conn, $sql)){
            header("Location: a_user_page.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER["REQUEST_METHOD"] == "GET"){
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : "";
}else{
    $start_date = "";
    $end_date = "";
}
?>
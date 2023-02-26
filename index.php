<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	header("Location: a_login.php");
	exit;
}


include('./conn/db_conn.php');

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $option_name = $_POST['option_name'];
    $day = $_POST['day'];
    $price = $_POST['price'];
    $option_id = $_POST['id'];
    $btn = $_POST['button'];
}else{
    $option_name = "";
    $day = "";
    $price = "";
    $option_id = "";
    $btn ="";
    
}
// Bild hochladen
if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $image_filename = $_FILES['image']['name'];
    $option_image = $_FILES['image']['type'];
    $temp_path = $_FILES['image']['tmp_name'];
    $data = file_get_contents($temp_path);

    // // Move the uploaded file to a permanent location
    // $target_path = './uploads/' . $image_filename;
    // move_uploaded_file($temp_path, $target_path);

    if($btn == "insert"){
        $sql = "INSERT INTO tbl_option (option_name, option_image, image_filename, data, day, price ) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $option_name, $option_image, $image_filename, $data, $day, $price);
        $stmt->execute();
    }
    if($btn == "update"){
        $sql = "UPDATE tbl_option SET option_name = ?, option_image = ?, image_filename = ?, data = ?, day = ?, price = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $option_name, $option_image, $image_filename, $data, $day, $price, $option_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: index.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}else{
    if($btn == "update"){
        if(empty($image_filename) && empty($option_image) && empty($data)){
            // If no file has been uploaded, use the existing data in the database
            $sql = "SELECT option_image, image_filename, data FROM tbl_option WHERE id = '{$option_id}'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $option_image = $row['option_image'];
            $image_filename = $row['image_filename'];
            $data = $row['data'];
            
        }
        if(empty($option_name)) {
            $query = "SELECT option_name FROM tbl_option WHERE id= '{$option_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $option_name = $row['option_name'];
            
        }
        if(empty($day) || $day == "Wählen Sie ein Tag aus...") {
            $query = "SELECT day FROM tbl_option WHERE id= '{$option_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $day = $row['day'];
            
        }
        if(empty($_POST['price']) ){
            $query = "SELECT price FROM tbl_option WHERE id = '{$option_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
        }

        $sql = "UPDATE tbl_option SET option_name = ?, option_image = ?, image_filename = ?, data = ?, day = ?, price = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $option_name, $option_image, $image_filename, $data, $day, $price, $option_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: index.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}
$conn->close();


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
	<title>Mensa</title>
</head>
<body>
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link active" href="#"><h5>Home</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_login.php"><h5>Login</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_register.php"><h5>Register</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./admin/a_logout.php"><h5>Logout</h5></a>
                </li>
            </ul>
        </div>
    </div>

    <img class="logo" src="./images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
    <nav class="navbar navbar-dark bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <hr style="height: 5px">
    
    <div class="container-fluid">
        <h5>Herzlichen Wilkommen <?php echo $_SESSION['userName']; ?></h5>
    </div>
    <div class="container-fluid">
        <h3>melde ein neue Benutzer</h3>
        <a href="./admin/create_user.php">Neu Benutzer melden</a>

        <h3>Benutzern bearbeiten</h3>
        <a href="./admin/a_user_page.php">Benutzer Seite</a>

        <h3>Bilder</h3>
        <a href="./admin/images.php">images</a>
    </div>

    <form action="index.php" method="post" enctype="multipart/form-data">
        <!-- <input type="text" name="id" value="<?php echo $option_id; ?>"> -->
        <select class="form-control" name="id">
            <?php
            include('./conn/db_conn.php');
            // Send query to database to get School Classes
                $sql = "SELECT id, option_name, image_filename, data, day, price FROM tbl_option";
                $result = mysqli_query($conn, $sql);
            // Include each result as an option tag in the drop-down list
            while($row = mysqli_fetch_assoc($result)){
                $option_name = $row['option_name'];
                $day = $row['day'];
                $data = $row['data'];
                $price = $row['price'];
                $option_id = $row['id'];
                echo '<option value="' . $option_id . '">'.$option_id." - " . $option_name . ' - ' . $day. " - " . $price . '€</option>';
            }
            $conn->close();
            ?>

        </select>
        <input type="text" class="form-control" name="option_name"  placeholder="option name">
        <select  class="form-control" name="day">
            <option>Wählen Sie ein Tag aus...</option>
            <option>Montag</option>
            <option>Dienstag</option>
            <option>Mittwoch</option>
            <option>Donnerstag</option>
            <option>Freitag</option>
        </select>
        <!-- <input type="text" class="form-control" name="day" placeholder="Tag"> -->
        <input type="text" class="form-control" name="price" placeholder="price">
        <label>Wenn das Bild ausgewählt ist, müssen andere Felder ausgefüllt werden. Aber die anderen Felder können einzeln aktualisiert werden.</label>
        <input type="file" class="form-control" name="image">
        <button type="submit" name="button" value="insert">Eingeben</button>
        <button type="submit" name="button" value="update">Aktualisieren</button>
    </form>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
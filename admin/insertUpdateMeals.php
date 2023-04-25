<?php 
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $option_name = $_POST['option_name'];
    $day = $_POST['day'];
    $price = $_POST['price'];
    $btn = $_POST['button'];
    $date = $_POST['date'];
}else{
    $option_name = "";
    $day = "";
    $price = "";
    $btn ="";
    $date= "";
    
}

if (isset($_POST['id'])) {
    $option_id = $_POST['id'];
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
        $sql = "INSERT INTO tbl_option (option_name, option_image, image_filename, data, day, date, price, admin_id ) VALUES (?, ?, ?, ?, ?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $option_name, $option_image, $image_filename, $data, $day, $date, $price, $admin_id);
        $stmt->execute();
    }
    if($btn == "update"){
        $sql = "UPDATE tbl_option SET option_name = ?, option_image = ?, image_filename = ?, data = ?, day = ?, date = ?, price = ?, admin_id_update = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssss", $option_name, $option_image, $image_filename, $data, $day, $date, $price, $admin_id, $option_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: meals_edit.php");
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
        if(empty($_POST['date']) ){
            $query = "SELECT date FROM tbl_option WHERE id = '{$option_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $date = $row['date'];
        }
        if(empty($_POST['price']) ){
            $query = "SELECT price FROM tbl_option WHERE id = '{$option_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
        }

        $sql = "UPDATE tbl_option SET option_name = ?, option_image = ?, image_filename = ?, data = ?, day = ?, date = ?, price = ?, admin_id_update = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssss", $option_name, $option_image, $image_filename, $data, $day, $date, $price, $admin_id, $option_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: meals_edit.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
    if($btn == "dateUpdate"){
        $sql = "UPDATE tbl_option SET date = ?, admin_id_update = ? WHERE day = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $date, $admin_id, $day);
        if(mysqli_stmt_execute($stmt)){
            header("Location: meals_edit.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
}

?>
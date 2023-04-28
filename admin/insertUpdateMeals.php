<?php 


// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}

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

        // Speichern der ursprünglichen Benutzerdaten in einer Variable
        $query = "SELECT * FROM tbl_option WHERE id = $option_id";
        $result = mysqli_query($conn, $query);
        $original_data = mysqli_fetch_assoc($result);
        
        // Überprüfen, welche Felder geändert wurden und sie in der Tabelle tbl_option_changes speichern
        foreach ($_POST as $key => $value) {
            if ($key != "option_id" && $key != "button") {
                if ($value != $original_data[$key]) {
                    $field_name = mysqli_real_escape_string($conn, $key);
                    $old_value = mysqli_real_escape_string($conn, $original_data[$key]);
                    $new_value = mysqli_real_escape_string($conn, $value);
                    $change_date = date("Y-m-d H:i:s");

                    // Überprüft, ob die Felder leer sind, sodass sie übersprungen werden und die Abfrage nicht ausgeführt wird.
                    if(!empty($new_value) && !empty($image_filename) && !empty($option_image) && !empty($data)){
                        $queryChange = "INSERT INTO tbl_option_changes (admin_id, option_id, field_name, old_value, new_value, change_date) VALUES ($admin_id, $option_id, '$field_name', '$old_value', '$new_value', '$change_date')";
                        mysqli_query($conn, $queryChange);
                    }
                }
            }
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
        
        // Speichern der ursprünglichen Benutzerdaten in einer Variable
        $query = "SELECT * FROM tbl_option WHERE id = $option_id";
        $result = mysqli_query($conn, $query);
        $original_data = mysqli_fetch_assoc($result);
        
        // Überprüfen, welche Felder geändert wurden und sie in der Tabelle tbl_option_changes speichern
        foreach ($_POST as $key => $value) {
            if ($key != "option_id" && $key != "button") {
                if ($value != $original_data[$key]) {
                    $field_name = mysqli_real_escape_string($conn, $key);
                    $old_value = mysqli_real_escape_string($conn, $original_data[$key]);
                    $new_value = mysqli_real_escape_string($conn, $value);
                    $change_date = date("Y-m-d H:i:s");

                    // Überprüft, ob die Felder leer sind, sodass sie übersprungen werden und die Abfrage nicht ausgeführt wird.
                    if(!empty($new_value)){
                        $queryChange = "INSERT INTO tbl_option_changes (admin_id, option_id, field_name, old_value, new_value, change_date) VALUES ($admin_id, $option_id, '$field_name', '$old_value', '$new_value', '$change_date')";
                        mysqli_query($conn, $queryChange);
                    }
                }
            }
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

        // Speichern der ursprünglichen Benutzerdaten in einer Variable
        $query = "SELECT * FROM tbl_option WHERE id = $option_id";
        $result = mysqli_query($conn, $query);
        $original_data = mysqli_fetch_assoc($result);
        
        // Überprüfen, welche Felder geändert wurden und sie in der Tabelle tbl_option_changes speichern
        foreach ($_POST as $key => $value) {
            if ($key != "option_id" && $key != "button" && $key != "day") {
                if ($value != $original_data[$key]) {
                    $field_name = mysqli_real_escape_string($conn, $key);
                    $old_value = mysqli_real_escape_string($conn, $original_data[$key]);
                    $new_value = mysqli_real_escape_string($conn, $value);
                    $change_date = date("Y-m-d H:i:s");

                    // Überprüft, ob die Felder leer sind, sodass sie übersprungen werden und die Abfrage nicht ausgeführt wird.
                    if(!empty($new_value)){
                        $queryChange = "INSERT INTO tbl_option_changes (admin_id, option_id, field_name, old_value, new_value, change_date) VALUES ($admin_id, $option_id, '$field_name', '$old_value', '$new_value', '$change_date')";
                        mysqli_query($conn, $queryChange);
                    }
                }
            }
        }

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
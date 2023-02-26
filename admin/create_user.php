<?php
include('../conn/db_conn.php');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: a_login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $klasse = $_POST['klasse'];
    $schule = $_POST['schule'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];
    $userName = $_POST['userName'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $ortsteil = $_POST['ortsteil'];
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];
    $btn = $_POST['button'];
    $user_id = $_POST['id'];

    if(isset($_POST['klasse_submitted'])){
        $sql= "INSERT INTO tbl_klasse (schule, klasse) VALUES ('$schule', '$klasse')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    elseif(isset($_POST['ort_submitted'])){
        $sql= "INSERT INTO tbl_ort (plz, ort) VALUES ('$plz', '$ort')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    elseif($btn == "insert"){
        $sql = "INSERT INTO tbl_user (klasse, plz, userName, firstName, lastName, email, phone, adresse, ortsteil, birthday, password)
                VALUES ('$klasse', '$plz', '$userName', '$firstName', '$lastName', '$email', '$phone', '$adresse', '$ortsteil', '$birthday', '$password')";
        if(mysqli_query($conn, $sql)){
            echo "Schüler ist erfolgreich hinzugefügt";
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    if($btn == "update"){
        if(empty($firstName)) {
            $query = "SELECT firstName FROM tbl_user WHERE id= '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $firstName = $row['firstName'];
            
        }
        if(empty($lastName)) {
            $query = "SELECT lastName FROM tbl_user WHERE id= '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $lastName = $row['lastName'];
            
        }
        if(empty($email)){
            $query = "SELECT email FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
        }
        if(empty($phone)){
            $query = "SELECT phone FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $phone = $row['phone'];
        }
        if(empty($adresse)){
            $query = "SELECT adresse FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $adresse = $row['adresse'];
        }
        if(empty($ortsteil)){
            $query = "SELECT ortsteil FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $ortsteil = $row['ortsteil'];
        }if(empty($password)){
            $query = "SELECT password FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $password = $row['password'];
        }
        

        $sql = "UPDATE tbl_user SET klasse = ?, plz = ?, userName = ?, firstName = ?, lastName = ?, email = ?, phone = ?, adresse = ?, ortsteil = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssss", $klasse, $plz, $userName, $firstName, $lastName, $email, $phone, $adresse, $ortsteil, $password, $user_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }

}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
<body>

    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="../index.php"><h5>Home</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_login.php"><h5>Login</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_register.php"><h5>Register</h5></a>
                </li>
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_logout.php"><h5>Logout</h5></a>
                </li>
            </ul>
        </div>
    </div>
    <img src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
    <nav class="navbar navbar-dark bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    
    <hr style="height: 5px">

    <div class="container-fluid">
        <div class="container-fluid text-center">
            <div>
                <div class="mb-3 col-12" style="float: left;">
                    <!-- Form to add a new school class -->
                    <div>
                        <h4>Neue Klasse einfügen</h4>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="klasse" id="klasse" placeholder="Klasse" required>
                            <!-- <label for="klasse">Klasse</label> -->
                        </div>
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="schule" id="schule" placeholder="Schule" required>
                            <!-- <label for="schule">Schule</label> -->
                        </div>
                        <div>
                            <input type="hidden" name="klasse_submitted" value="1">
                            <input type="submit" class="btn btn-primary" value="Kalsse Erstellen">
                        </div>
                    </form>
                </div>

                <div class="mb-3 col-12">
                    <!-- Form to add a new postal code and city -->
                    <div>
                        <h4>Neues Ort einfügen</h4>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="plz" id="plz" placeholder="PLZ" required>
                            <!-- <label for="plz">PLZ</label> -->
                        </div>
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="ort" id="ort" placeholder="Ort" required>
                            <!-- <label for="ort">Ort</label> -->
                        </div>
                        <div>
                            <input type="hidden" name="ort_submitted" value="1">
                            <input type="submit" class="btn btn-primary" value="Ort Erstellen">
                        </div>
                    </form>
                </div>
            </div>

            <hr style="height: 10px;">

            <!-- Form to add a new student -->
            <div class="createUserForm">
                <h4>Neu SchülerInnen hinzufügen</h4>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <input type="text" class="form-control" id="searchInput" onkeyup="filterOptions()" placeholder="Search for an option...">
                    <select name="id" class="form-control" id="optionList">
                        <?php
                            // Send query to database to get users
                                $sql = "SELECT id, userName FROM tbl_user";
                                $result = mysqli_query($conn, $sql);

                            // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['id']."'>" .$row['userName'] . "</option>";
                                }
                            ?>
                    </select>
                    <div class="mb-1">
                        <!-- <label for="klasse">Klasse:</label> -->
                        <select class="form-control" name="klasse" id="klasse">
                            <?php
                            // Send query to database to get School Classes
                                $sql = "SELECT * FROM tbl_klasse";
                                $result = mysqli_query($conn, $sql);

                            // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['klasse']."'>" . $row['klasse'] . "</option>";
                                }
                                mysqli_close($conn);
                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <!-- <label for="plz">PLZ:</label> -->
                        <select class="form-control" name="plz" id="plz">
                            <?php 
                                include ('../conn/db_conn.php');
                            // Send query to database to get postal code and cities
                                $sql = "SELECT * FROM tbl_ort";
                                $result = mysqli_query($conn, $sql);
                            
                            // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['plz']."'>" . $row['plz'] . " - " . $row['ort'] . "</option>";
                                }
                                mysqli_close($conn);
                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <!-- <label for="userName">Benutzername:</label> -->
                        <input type="text" class="form-control" name="userName" id="userName" placeholder="Benutzername wird automatisch ausgefüllt" readonly>
                    </div>
                    <div class="mb-1">
                        <!-- <label for="firstName">Vorname:</label> -->
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Vorname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="lastName">Nachname:</label> -->
                        <input type="text" class="form-control" name="lastName" id="lastName"  placeholder="Nachname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="email">Email:</label> -->
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Adresse" >
                    </div>
                    <div class="mb-1">
                        <!-- <label for="phone">Handy:</label> -->
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Handynummer" >
                    </div>
                    
                    <div class="mb-1">
                        <!-- <label for="adresse">Anschrift:</label> -->
                        <input type="text" class="form-control" name="adresse" id="adresse" placeholder="Straße + Haus Nr." >
                    </div>
                    <div class="mb-1">
                        <!-- <label for="ortsteil">Ortsteil:</label> -->
                        <input type="text" class="form-control" name="ortsteil" id="ortsteil" placeholder="Ortsteil" >
                    </div>
                    <div class="mb-1">
                        <!-- <label for="birthday">Geburtsdatum:</label> -->
                        <input type="text" class="form-control" name="birthday" id="birthday"  placeholder="01.01.1999" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="password">Kennwort:</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="Kennwort" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary" name="button" value="insert">Hinzufügen</button>
                    <button type="submit" class="btn btn-primary" name="button" value="update">Aktualisieren</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterOptions() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var select = document.getElementById("optionList");
            var options = select.getElementsByTagName("option");
            for (var i = 0; i < options.length; i++) {
                var optionText = options[i].text.toUpperCase();
                if (optionText.indexOf(filter) > -1) {
                options[i].style.display = "";
                } else {
                options[i].style.display = "none";
                }
            }
        }
    </script>
    <!-- <script src="../js/jquery-3.6.0.min.js"></script> -->
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
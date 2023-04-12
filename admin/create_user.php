<?php
include('../conn/db_conn.php');
session_start();
// Check if the user is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
}else{
    header("Location: a_login.php");
	exit;
}

$aktiv_ab = date('d.m.Y');

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (isset($_POST['userName1'])) {
        $userName1 = $_POST['userName1'];
    }
    if (isset($_POST['klasse'])) {
        $klasse = $_POST['klasse'];
    }
    if (isset($_POST['schule'])) {
        $schule = $_POST['schule'];
    }
    if (isset($_POST['plz'])) {
        $plz = $_POST['plz'];
    }
    if (isset($_POST['ort'])) {
        $ort = $_POST['ort'];
    }
    if (isset($_POST['userName'])) {
        $userName = $_POST['userName'];
    }
    if (isset($_POST['firstName'])) {
        $firstName = $_POST['firstName'];
    }
    if (isset($_POST['lastName'])) {
        $lastName = $_POST['lastName'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['phone'])) {
        $phone = $_POST['phone'];
    }
    if (isset($_POST['adresse'])) {
        $adresse = $_POST['adresse'];
    }
    if (isset($_POST['ortsteil'])) {
        $ortsteil = $_POST['ortsteil'];
    }
    if (isset($_POST['birthday'])) {
        $birthday = $_POST['birthday'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (isset($_POST['button'])) {
        $button = $_POST['button'];
    }
    if (isset($_POST['id'])) {
        $user_id = $_POST['id'];
    }
    if (isset($_POST['admin_id'])) {
        $admin_id = $_POST['admin_id'];
    }

    if(isset($_POST['klasse_submitted'])){
        $sql= "INSERT INTO tbl_klasse (schule, klasse, admin_id) VALUES ('$schule', '$klasse','{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    if(isset($_POST['ort_submitted'])){
        $sql= "INSERT INTO tbl_ort (plz, ort, admin_id) VALUES ('$plz', '$ort', '{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    // Hash the password
    $hashed_password = hash('sha256', $password);

    if($button == "insert"){
        $sql = "INSERT INTO tbl_user (klasse, plz, userName, firstName, lastName, email, phone, adresse, ortsteil, birthday, aktiv_ab, password, admin_id)
                VALUES ('$klasse', '$plz', '$userName', '$firstName', '$lastName', '$email', '$phone', '$adresse', '$ortsteil', '$birthday', '$aktiv_ab', '$hashed_password', '{$admin_id}')";
        if(mysqli_query($conn, $sql)){
            echo "Schüler ist erfolgreich hinzugefügt";
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    if($button == "update"){
        
        if(empty($klasse)) {
            $query = "SELECT klasse FROM tbl_user WHERE id= '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $klasse = $row['klasse'];
            
        }
        if(empty($plz)) {
            $query = "SELECT plz FROM tbl_user WHERE id= '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $plz = $row['plz'];
            
        }
        if(empty($userName)) {
            $query = "SELECT userName FROM tbl_user WHERE id= '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $userName = $row['userName'];
            
        }
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
        }
        if(empty($password)){
            $query = "SELECT password FROM tbl_user WHERE id = '{$user_id}'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $password = $row['password'];
        }
        

        $sql = "UPDATE tbl_user SET klasse = ?, plz = ?, firstName = ?, lastName = ?, email = ?, phone = ?, adresse = ?, ortsteil = ?, admin_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $klasse, $plz, $firstName, $lastName, $email, $phone, $adresse, $ortsteil, $admin_id, $user_id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
    }
    if($button == "delete"){
        $sql = "DELETE u, b, s, e, a FROM tbl_user u
                LEFT JOIN tbl_bestellung b ON u.id = b.user_id
                LEFT JOIN tbl_bestellstatus s ON u.id = s.user_id
                LEFT JOIN tbl_einzahlung e ON u.id = e.user_id
                LEFT JOIN tbl_auszahlung a ON u.id = a.user_id
                WHERE u.id = '{$user_id}'";
        if(mysqli_query($conn, $sql)){
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
    <style>
        .tooltips {
            position: relative;
            display: inline-block;
            border-bottom: 2px dotted black;
            cursor: pointer;
            color: black;
        }

        .hint {
            display: none;
            position: absolute;
            z-index: 1;
            background-color: #f9f9f9;
            color:  navy; 
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 400px;
            text-align: center;
        }

        .hint::before {
            content: "";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent #f9f9f9 transparent transparent;
        }
                    
    </style>
</head>
<body>

    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link" href="../index.php"><h6>Haupt Seite |</h6></a>
                </li>

                <li class="nav-item item_besonder">
                    <a class="nav-link active" href="./create_user.php"><h6>Neu Benutzer |</h6></a>
                </li>

                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_user_page.php"><h6>Benutzer Seite |</h6></a>
                </li>

                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
                </li>

                <li class="nav-item item_besonder">
                    <a class="nav-link" href="./a_logout.php"><h6>Abmelden</h6></a>
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
            <h5 style="margin: 0;">Herzlich Willkommen <span style="color:green"><?php echo $_SESSION['adminName']; ?></span></h5>
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
                            <input type="submit" class="btn btn-warning" value="Kalsse Erstellen">
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
                            <input type="text" style="width: 320px" name="plz" id="plz" placeholder="PLZ"  onkeyup="sucheStadt()" required>
                            <!-- <label for="plz">PLZ</label> -->
                        </div>
                        <div class="mb-1">
                            <input type="text" style="width: 320px" name="ort" id="ort" placeholder="Ort" required>
                            <!-- <label for="ort">Ort</label> -->
                        </div>
                        <div>
                            <input type="hidden" name="ort_submitted" value="1">
                            <input type="submit" class="btn btn-warning" value="Ort Erstellen">
                        </div>
                    </form>
                </div>
            </div>

            <hr style="height: 10px;">

            <!-- Form to add a new student -->
            <div class="createUserForm">
                <h4>Neu SchülerInnen hinzufügen </h4>
                <span class="tooltips" onclick="showHint()">info?</span>
                <div class="hint" id="hint">This is a hint flmösdölkf sdf skdn fnsdnf nsdfnsdnbfjkn sdlkf ksdlöf lsdkfk nsdb fsdbfb s,dnfsd kfmsdmfl sdmlfsldmf .</div>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <div class="mb-1">
                        <input type="text" class="form-control" id="searchInput" onkeyup="filterOptions()" placeholder="nach einem Benutzer suchen...">
                    </div>
                    <div class="mb-1">
                        <select name="id" class="form-control" id="optionList">
                            <option>Benutzer Name auswählen...</option>
                            <?php
                                // Send query to database to get users
                                $sql = "SELECT id, userName FROM tbl_user";
                                $result = mysqli_query($conn, $sql);
                                // Include each result as an option tag in the drop-down list
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value= '" . $row['id']."'>" .$row['id']. "_".$row['userName'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <!-- <label for="klasse">Klasse:</label> -->
                        <select class="form-control" name="klasse" id="klasse">
                            <option></option>
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
                            <option></option>
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
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Adresse">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="phone">Handy:</label> -->
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Handynummer">
                    </div>
                    
                    <div class="mb-1">
                        <!-- <label for="adresse">Anschrift:</label> -->
                        <input type="text" class="form-control" name="adresse" id="adresse" placeholder="Straße, Haus Nr.">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="ortsteil">Ortsteil:</label> -->
                        <input type="text" class="form-control" name="ortsteil" id="ortsteil" placeholder="Ortsteil">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="birthday">Geburtsdatum:</label> -->
                        <input type="text" class="form-control" name="birthday" id="birthday"  placeholder="01.01.1999" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="password">Kennwort:</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="Kennwort wird automatisch ausgefüllt" readonly>
                    </div>
                    <button type="submit" class="btn btn-warning" name="button" value="insert">Hinzufügen</button>
                    <button type="submit" class="btn btn-warning" name="button" value="update">Aktualisieren</button>
                    <button type="submit" class="btn btn-warning" name="button" value="delete"  onclick="return confirm('Möchten Sie diesen Benutzer wirklich löschen?')">Löschen</button>
                </form>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 80px">
    
    </div>
    <footer class="fixed-bottom footer">
        <p class="footer_text"><span>&copy; 2023 created by Khalid Arab</span></p>
    </footer>


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


        function showHint() {
            var tooltip = document.getElementsByClassName("tooltips")[0];
            var hint = document.getElementById("hint");
            if (hint.style.display === "block") {
                hint.style.display = "none";
            } else {
                hint.style.display = "block";
                hint.style.top = tooltip.offsetTop + "px";
                hint.style.left = (tooltip.offsetLeft + tooltip.offsetWidth) + "px";
            }
        }


        function deleteRecord(id) {
            if (confirm("Möchten Sie diesen Benutzer wirklich löschen?")) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
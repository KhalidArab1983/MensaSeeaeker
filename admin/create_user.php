<?php
include('../conn/db_conn.php');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: a_login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['klasse_submitted'])){
        $klasse = $_POST['klasse'];
        $schule = $_POST['schule'];
        
        $sql= "INSERT INTO tbl_klasse (schule, klasse) VALUES ('$schule', '$klasse')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    elseif(isset($_POST['ort_submitted'])){
        $plz = $_POST['plz'];
        $ort = $_POST['ort'];

        $sql= "INSERT INTO tbl_ort (plz, ort) VALUES ('$plz', '$ort')";
        if(mysqli_query($conn, $sql)){
            header("Location: create_user.php");
        }else{
            echo "Error: " . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    elseif(isset($_POST['benutzer_submitted'])){
        $klasse = $_POST['klasse'];
        $userName = $_POST['userName'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $plz = $_POST['plz'];
        $adresse = $_POST['adresse'];
        $ortsteil = $_POST['ortsteil'];
        $birthday = $_POST['birthday'];
        $password = $_POST['password'];

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

}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
<body>

    <!-- <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/a_logout.php">Logout</a>
                    </li>
                </ul>
                <img src="./images/logo.png" alt="Seeäkerschule Logo" width=10%>
            </div>
        </div>
    </nav> -->
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4" style="display:inline-block;">
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
            <h4>Neu SchülerInnen hinzufügen</h4>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <div class="mb-1">
                    <!-- <label for="klasse">Klasse:</label> -->
                    <select class="w-25" name="klasse" id="klasse">
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
                    <select class="w-25" name="plz" id="plz">
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
                    <input type="text" class="w-25" name="userName" id="userName" placeholder="Benutzername wird automatisch ausgefüllt" readonly>
                </div>
                <div class="mb-1">
                    <!-- <label for="firstName">Vorname:</label> -->
                    <input type="text" class="w-25" name="firstName" id="firstName" placeholder="Vorname" required onkeyup="updateInputUser()">
                </div>
                <div class="mb-1">
                    <!-- <label for="lastName">Nachname:</label> -->
                    <input type="text" class="w-25" name="lastName" id="lastName"  placeholder="Nachname" required onkeyup="updateInputUser()">
                </div>
                <div class="mb-1">
                    <!-- <label for="email">Email:</label> -->
                    <input type="email" class="w-25" name="email" id="email" placeholder="Email Adresse" required>
                </div>
                <div class="mb-1">
                    <!-- <label for="phone">Handy:</label> -->
                    <input type="text" class="w-25" name="phone" id="phone" placeholder="Handynummer" required>
                </div>
                
                <div class="mb-1">
                    <!-- <label for="adresse">Anschrift:</label> -->
                    <input type="text" class="w-25" name="adresse" id="adresse" placeholder="Straße + Haus Nr." required>
                </div>
                <div class="mb-1">
                    <!-- <label for="ortsteil">Ortsteil:</label> -->
                    <input type="text" class="w-25" name="ortsteil" id="ortsteil" placeholder="Ortsteil" required>
                </div>
                <div class="mb-1">
                    <!-- <label for="birthday">Geburtsdatum:</label> -->
                    <input type="text" class="w-25" name="birthday" id="birthday" required placeholder="01.01.1999" onkeyup="updateInputUser()">
                </div>
                <div class="mb-1">
                    <!-- <label for="password">Kennwort:</label> -->
                    <input type="password" class="w-25" name="password" id="password" placeholder="Kennwort" readonly>
                </div>
                <input type="hidden" name="benutzer_submitted" value="1">
                <input type="submit" class="btn btn-primary" value="Hinzufügen">
            </form>
        </div>
    </div>

    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        function updateInputUser(){
            var firstNameValue = document.getElementById("firstName").value;
            var lastNameValue = document.getElementById("lastName").value;
            var birthdayValue = document.getElementById("birthday").value;
            var klasseValue = document.getElementById("klasse").value;

            var benutzerNameValue = (lastNameValue.substr(0,2) + firstNameValue.substr(0,2)).toLowerCase() + birthdayValue.substr(8,4);
            var kennwortValue = lastNameValue.substr(0,2).toUpperCase() + firstNameValue.substr(0,2).toLowerCase() +"-"+ birthdayValue.substr(8,4);
            document.getElementById("userName").value = benutzerNameValue;
            document.getElementById("password").value = kennwortValue;
        }
    </script>
</body>
</html>
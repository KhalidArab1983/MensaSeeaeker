<?php
include('./conn/db_conn.php');

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
    <link rel="stylesheet" href="./css/style.css">
	<title>Mensa</title>
</head>
<body>
    <!-- Form to add a new school class -->
    <h1>Neue Klasse einfügen</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="klasse">Klasse:</label>
		<input type="text" name="klasse" id="klasse" required>
		<br>
        <label for="schule">Schule:</label>
		<input type="text" name="schule" id="schule" required>
		<br>
        <input type="hidden" name="klasse_submitted" value="1">
		<input type="submit" value="Kalsse Erstellen">
    </form>

    <!-- Form to add a new postal code and city -->
    <h1>Neues Ort einfügen</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="plz">PLZ:</label>
		<input type="text" name="plz" id="plz" required>
		<br>
        <label for="ort">Ort:</label>
		<input type="text" name="ort" id="ort" required>
		<br>
        <input type="hidden" name="ort_submitted" value="1">
		<input type="submit" value="Ort Erstellen">
    </form>

    <!-- Form to add a new student -->
    <h1>Neu SchülerInnen hinzufügen</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="klasse">Klasse:</label>
		<select name="klasse" id="klasse">
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
		<br>
        <label for="userName">Benutzername:</label>
		<input type="text" name="userName" id="userName" readonly>
		<br>
        <label for="firstName">Vorname:</label>
		<input type="text" name="firstName" id="firstName" placeholder="Vorname" required onkeyup="updateInputUser()">
		<br>
        <label for="lastName">Nachname:</label>
		<input type="text" name="lastName" id="lastName"  placeholder="Nachname" required onkeyup="updateInputUser()">
		<br>
        <label for="email">Email:</label>
		<input type="email" name="email" id="email" required>
		<br>
        <label for="phone">Handy:</label>
		<input type="text" name="phone" id="phone" required>
		<br>
        <label for="plz">PLZ:</label>
		<select name="plz" id="plz">
            <?php 
                include ('./conn/db_conn.php');
            // Send query to database to get postal code and cities
                $sql = "SELECT * FROM tbl_ort";
                $result = mysqli_query($conn, $sql);
            
            // Include each result as an option tag in the drop-down list
                while($row = mysqli_fetch_assoc($result)){
                    echo "<option value= '" . $row['plz']."'>" . $row['plz'] .  "</option>";
                }
                mysqli_close($conn);
            ?>
        </select>
		<br>
        <label for="adresse">Anschrift:</label>
		<input type="text" name="adresse" id="adresse" placeholder="Straße + Haus Nr." required>
		<br>
        <label for="ortsteil">Ortsteil:</label>
		<input type="text" name="ortsteil" id="ortsteil" required>
		<br>
        <label for="birthday">Geburtsdatum:</label>
		<input type="text" name="birthday" id="birthday" required placeholder="01.01.1999" onkeyup="updateInputUser()">
		<br>
        <label for="password">Kennwort:</label>
		<input type="password" name="password" id="password" required>
		<br>
        <input type="hidden" name="benutzer_submitted" value="1">
		<input type="submit" value="Hinzufügen">
    </form>

    <script>
        function updateInputUser(){
            var firstNameValue = document.getElementById("firstName").value;
            var lastNameValue = document.getElementById("lastName").value;
            var birthdayValue = document.getElementById("birthday").value;

            var benutzerNameValue = (lastNameValue.substr(0,2) + firstNameValue.substr(0,2)).toLowerCase() + birthdayValue.substr(8,4);
            document.getElementById("userName").value = benutzerNameValue;
        }
    </script>

</body>
</html>
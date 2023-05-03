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

include ('./create_user_includes/create_user_inc.php');
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<title>Mensa</title>
</head>
<body>
    <?php include ('./nav_includes/navbar.php') ?>
    
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
                            <input type="submit" class="btn btn-warning" value="Klasse Erstellen">
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
                <h4>SchülerInnen Hinzufügen, Aktualisieren oder Löschen </h4>
                <span class="tooltips" onclick="showHint()">info?</span>
                <div class="hint" id="hint">
                    1. Suchen Sie nach dem Benutzer, indem Sie seinen Namen in das erste Feld eingeben.(Sie können einen beliebigen Teil des Benutzernamentextes eingeben)<br>
                    2. Wählen Sie dann den Benutzer aus der Dropdown-Liste im nächsten Feld aus.<br>
                    2.1. Um den ausgewählten Benutzer und alle seine Daten zu löschen, klicken Sie auf die Schaltfläche „Löschen“.<br>
                    2.2. Um die Benutzerdaten zu aktualisieren, füllen Sie die zu ändernden Felder aus und klicken Sie auf die Schaltfläche „Aktualisieren“.<br>
                    3. Das dritte Feld zur Auswahl der Klasse aus der Dropdown-Liste.<br>
                    4. Das vierte Feld zur Auswahl der Stadt aus der Dropdown-Liste.<br>
                    5. Die Felder Benutzername und Passwort werden automatisch ausgefüllt.<br>
                    6. Um einen neuen Benutzer hinzuzufügen, füllen Sie alle erforderlichen Felder aus und klicken Sie auf die Schaltfläche "Hinzufügen".<br>
                    7. Um diesen Dialog wieder auszublenden, klicken Sie erneut auf das Wort „info?“.
                </div>
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
                        <div class="form-text error"><?php echo $error;?></div>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="klasse">Klasse:</label> -->
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
                        <!-- <label class="floatLeft" for="plz">PLZ:</label> -->
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
                        <!-- <label class="floatLeft" for="userName">Benutzername:</label> -->
                        <input type="text" class="form-control" name="userName" id="userName" placeholder="* Benutzername wird automatisch ausgefüllt" readonly>
                    </div>
                    <div class="mb-1">
                        <!-- <label class="floatLeft" for="firstName">Vorname:</label> -->
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="* Vorname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="lastName">Nachname:</label> -->
                        <input type="text" class="form-control" name="lastName" id="lastName"  placeholder="* Nachname" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="email">Email:</label> -->
                        <input type="email" class="form-control" name="email" id="email" placeholder="* Email Adresse">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="phone">Handy:</label> -->
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="* Handynummer">
                    </div>
                    
                    <div class="mb-1">
                        <!-- <label for="adresse">Anschrift:</label> -->
                        <input type="text" class="form-control" name="adresse" id="adresse" placeholder="* Straße, Haus Nr.">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="ortsteil">Ortsteil:</label> -->
                        <input type="text" class="form-control" name="ortsteil" id="ortsteil" placeholder="* Ortsteil">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="birthday">Geburtsdatum:</label> -->
                        <input type="text" class="form-control" name="birthday" id="birthday"  placeholder="* 01.01.1900" onkeyup="updateInputUser()">
                    </div>
                    <div class="mb-1">
                        <!-- <label for="password">Kennwort:</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="* Kennwort wird automatisch ausgefüllt" readonly>
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

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
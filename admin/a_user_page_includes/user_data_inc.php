<h3 class="headerDeco">Benutzer Daten:</h3>
<form method="get">
    <div class="col-4 disFlex">
        <input type="text" class="form-control m-1" name="userNameTable" id="user_id" placeholder="nach einem Benutzer suchen..."> 
        <button type="submit" name="button" class="btn btn-warning m-1" value="userTableSuchen">Suchen</button>
    </div>
</form>
<div class="container-fluid">
    <?php 
        if (isset($_GET['userNameTable'])) {
            // Benutzereingabe bereinigen
            $userNameTable = trim(mysqli_real_escape_string($conn, $_GET['userNameTable']));
            // Um Benutzerdaten abzurufen und in den Eingabefeldern anzuzeigen
            $sql = "SELECT * FROM tbl_user u  
            INNER JOIN tbl_ort o ON u.plz = o.plz
            WHERE u.userName = '$userNameTable'";
            $result = mysqli_query($conn, $sql);
            if($result->num_rows > 0){
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
        <div name="userDataTable" class="row">
            <div class="card col-sm-12 col-md-4 m-1">
                <h4 class="mb-5">Allgemeine Daten:</h4>
                <div class="form-group">
                    <label for="userName" class="fontBold">Benutzername:</label>
                    <input type="text" name="userName" id="userName" class="form-control tableRow" value="<?php echo $userName; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="lastName" class="fontBold">Nachname:</label>
                    <input type="text" name="lastName" id="lastName" class="form-control tableRow" value="<?php echo $lastName; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="firstName" class="fontBold">Vorname:</label>
                    <input type="text" name="firstName" id="firstName" class="form-control tableRow" value="<?php echo $firstName; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="birthday" class="fontBold">Geburtsdatum:</label>
                    <input type="text" name="birthday" id="birthday" class="form-control tableRow" value="<?php echo $birthday; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="aktiv_ab" class="fontBold">Aktiv ab:</label>
                    <input type="text" name="aktiv_ab" id="aktiv_ab" class="form-control tableRow" value="<?php echo $aktiv_ab; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="klasse" class="fontBold">Klasse:</label>
                    <input type="text" name="klasse" id="klasse" class="form-control tableRow" value="<?php echo $klasse; ?>" readonly>
                </div>
                <p>* Die Daten in diesen Tabellen dienen nur zur Anzeige und können nicht geändert werden.</p>
            </div>
            <div class="card col-sm-12 col-md-4 m-1">
                <h4 class="mb-5">Adresse:</h4>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                    <div class="form-group">                                    
                        <label class="fontBold width200">Adresse :</label>
                        <input type="text" name="adresse" id="adresse" class="form-control tableRow" value="<?php echo $adresse; ?>" readonly>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold width200">PLZ :</label>
                        <input type="text" name="plz" id="plz" class="form-control tableRow" value="<?php echo $plz; ?>" readonly>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold width200">Ort :</label>
                        <input type="text" name="ort" id="ort" class="form-control tableRow" value="<?php echo $ort; ?>" readonly>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold width200">Ortsteil :</label>
                        <input type="text" name="ortsteil" id="ortsteil" class="form-control tableRow" value="<?php echo $ortsteil; ?>" readonly>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold width200">Handy :</label>
                        <input type="text" name="phone" id="phone" class="form-control tableRow" value="<?php echo $phone; ?>" readonly>
                    </div>
                    <div class="form-group">                                    
                        <label class="fontBold width200">Email-Adresse :</label>
                        <input type="text" name="email" id="email" class="form-control tableRow" value="<?php echo $email; ?>" readonly>
                    </div>
                    <p>Um die Daten des Benutzers zu ändern, wenden Sie sich an "Neu Benutzer" Seite.</p>
                </form>
            </div>
        </div>
    <?php 
        }else{
            echo '<h4>Die Benutzername <span class="fontBold colorBlue">{$userNameTable}</span> nicht gefunden.</h4>';
        }
    }
    ?>
</div>
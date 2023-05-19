<div class="row">
    <div class="card col-sm-12 col-md-12 col-lg-4 m-2">
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
        <p class="para">* Die Daten in dieser Tabelle dienen nur zur Anzeige und können nicht geändert werden.</p>
    </div>
    <div class="card col-sm-12 col-md-12 col-lg-4 m-2">
        <h4 class="mb-5">Adresse:</h4>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <div class="form-group">                                    
                <label class="fontBold">Adresse :</label>
                <input type="text" name="adresse" id="adresse" class="form-control tableRow" value="<?php echo $adresse; ?>">
            </div>
            <div class="form-group">                                    
                <label class="fontBold">PLZ :</label>
                <input type="text" name="plz" id="plz" class="form-control tableRow" value="<?php echo $plz; ?>">
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Ort :</label>
                <input type="text" name="ort" id="ort" class="form-control tableRow" value="<?php echo $ort; ?>" readonly>
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Ortsteil :</label>
                <input type="text" name="ortsteil" id="ortsteil" class="form-control tableRow" value="<?php echo $ortsteil; ?>">
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Handy :</label>
                <input type="text" name="phone" id="phone" class="form-control tableRow" value="<?php echo $phone; ?>">
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Email-Adresse :</label>
                <input type="text" name="email" id="email" class="form-control tableRow" value="<?php echo $email; ?>">
            </div>
            <p class="para">* Geben Sie die PLZ ein und das Ort wird automatisch geändert.</p>
            <div class="form-group">                                    
                <button type="submit" class="btn btn-warning m-2" name="adresseForm" value="save">Speichern</button>
                <button type="reset" class="btn btn-warning m-2" name="button" value="cancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
            </div>
        </form>
    </div>
    <div class="card col-sm-12 col-md-12 col-lg-3 m-2">
        <h4 class="mb-5">Passwort:</h4>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="form-group">                                    
                <label class="fontBold">bisheriges Passwort:</label>
                <input type="password" name="current_password" id="current_password" class="form-control tableRow" value="<?php echo $current_password; ?>">
                <div class="form-text mb-3 error"><?php echo $errors['currentPassError'] ?></div>
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Neues Passwort:</label>
                <input type="password" name="new_password" id="new_password" class="form-control tableRow" value="<?php echo $new_password; ?>">
                <div class="form-text mb-3 error"><?php echo $errors['newPassError'] ?></div>
                <div class="form-text mb-3 error"><?php echo $errors['passRegexError'] ?></div>
                
            </div>
            <div class="form-group">                                    
                <label class="fontBold">Neues Passwort bestätigen:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control tableRow" value="<?php echo $confirm_password; ?>">
                <div class="form-text mb-3 error"><?php echo $errors['confirmPassError'] ?></div>
                <div class="form-text mb-3 error"><?php echo $errors['otherError'] ?></div>
            </div>
            <div class="form-group">                                    
                <button type="submit" class="btn btn-warning m-2" name="passwordForm" value="passSave">Speichern</button>
                <button type="reset" class="btn btn-warning m-2" name="button" value="passCancel" onClick="location.href='<?php echo $_SERVER["PHP_SELF"] ?>'">Abrechen</button>
            </div>
            <div class="form-text m-5 success"><?php echo $success ?></div>
        </form>
    </div>
</div>
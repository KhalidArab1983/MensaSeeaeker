//die Sekunden im Label laufend aktualisieren
// Funktion zur Aktualisierung der Systemzeit
function updateTime() {
    document.getElementById("system-time").value = new Date().toLocaleString();
}
// Aktualisiere die Systemzeit alle Sekunde
setInterval(updateTime, 1000);



// Diese Script um Tab funktion in der u_user_page Seite zu erstellen
$(document).ready(function(){
    // Standardmäßig wird der erste Tab geöffnet
    $(".tabcontent:first").show();
    
    // Beim Klicken auf den Tab-Button wird der entsprechende Inhalt geöffnet
    $(".tab button").click(function(){
        var tab_id = $(this).attr("data-tab");
        
        $(".tab button").removeClass("active");
        $(".tabcontent").hide();
        
        $(this).addClass("active");
        $("#" + tab_id).show();
    });
});



// Diese Script um automatische Benutzername zu erstellen
function updateInputUser(){
    var firstNameValue = document.getElementById("firstName").value;
    var lastNameValue = document.getElementById("lastName").value;
    var birthdayValue = document.getElementById("birthday").value;
    var klasseValue = document.getElementById("klasse").value;

    var benutzerNameValue = firstNameValue.substr().toLowerCase() +"-"+ lastNameValue.substr(0,2).toUpperCase() + birthdayValue.substr(8,4);
    var kennwortValue = firstNameValue.substr().toLowerCase() +"-"+ lastNameValue.substr(0,2).toUpperCase() + birthdayValue.substr(8,4);
    document.getElementById("userName").value = benutzerNameValue;
    document.getElementById("password").value = kennwortValue;
}

//Um Bilder aus der Datenbank abzurufen und sie mit einem Dropdown-Element anzuzeigen

function chImageMo() {
    // Elemente auswählen
    var selectList = document.getElementById("optionListMo");
    var container = document.getElementById("imageContainer");
    // Ausgewähltes Element auswählen
    var selectedOption = selectList.options[selectList.selectedIndex];
    var optionID = selectedOption.value;
    // AJAX-Anfrage senden, um das Bild aus der Datenbank abzurufen
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var imageURL = xhr.responseText;
            // Bild in den Container einfügen
            container.innerHTML = imageURL;
        }
    };
    xhr.open("GET", "images.php?id=" + optionID, true);
    xhr.send();
}

function chImageDi() {
    // Elemente auswählen
    var selectList = document.getElementById("optionListDi");
    var container = document.getElementById("imageContainer1");
    // Ausgewähltes Element auswählen
    var selectedOption = selectList.options[selectList.selectedIndex];
    var optionID = selectedOption.value;
    // AJAX-Anfrage senden, um das Bild aus der Datenbank abzurufen
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var imageURL = xhr.responseText;
            // Bild in den Container einfügen
            container.innerHTML = imageURL;
        }
    };
    xhr.open("GET", "images.php?id=" + optionID, true);
    xhr.send();
}

function chImageMi() {
    // Elemente auswählen
    var selectList = document.getElementById("optionListMi");
    var container = document.getElementById("imageContainer2");
    // Ausgewähltes Element auswählen
    var selectedOption = selectList.options[selectList.selectedIndex];
    var optionID = selectedOption.value;
    // AJAX-Anfrage senden, um das Bild aus der Datenbank abzurufen
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var imageURL = xhr.responseText;
            // Bild in den Container einfügen
            container.innerHTML = imageURL;
        }
    };
    xhr.open("GET", "images.php?id=" + optionID, true);
    xhr.send();
}

function chImageDo() {
    // Elemente auswählen
    var selectList = document.getElementById("optionListDo");
    var container = document.getElementById("imageContainer3");
    // Ausgewähltes Element auswählen
    var selectedOption = selectList.options[selectList.selectedIndex];
    var optionID = selectedOption.value;
    // AJAX-Anfrage senden, um das Bild aus der Datenbank abzurufen
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var imageURL = xhr.responseText;
            // Bild in den Container einfügen
            container.innerHTML = imageURL;
        }
    };
    xhr.open("GET", "images.php?id=" + optionID, true);
    xhr.send();
}

function chImageFr() {
    // Elemente auswählen
    var selectList = document.getElementById("optionListFr");
    var container = document.getElementById("imageContainer4");
    // Ausgewähltes Element auswählen
    var selectedOption = selectList.options[selectList.selectedIndex];
    var optionID = selectedOption.value;
    // AJAX-Anfrage senden, um das Bild aus der Datenbank abzurufen
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var imageURL = xhr.responseText;
            // Bild in den Container einfügen
            container.innerHTML = imageURL;
        }
    };
    xhr.open("GET", "images.php?id=" + optionID, true);
    xhr.send();
}

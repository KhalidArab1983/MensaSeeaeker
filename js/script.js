//die Sekunden im Label laufend aktualisieren
// Funktion zur Aktualisierung der Systemzeit
function updateTime() {
    document.getElementById("system-time").value = new Date().toLocaleString();
}
// Aktualisiere die Systemzeit alle Sekunde
setInterval(updateTime, 1000);

//Stadt basierend auf PLZ suchen durch API abrufen
function sucheStadt() {
    var plz = document.getElementById("plz").value;
    var ort = document.getElementById("ort");

    if (plz.length == 5 && /^\d+$/.test(plz)) {
        $.ajax({
            url: "../admin/sucheStadt.php",
            type: "POST",
            data: {plz: plz},
            success: function(data) {
                if (data) {
                    ort.value = data;
                } else {
                    ort.value = "Keine ort gefunden";
                }
            },
            error: function() {
                ort.value = "Fehler beim Abrufen dem ort";
            }
        });
    }else if(plz.value = ""){
        ort = "";
    }
}




// // Diese Script um Tab funktion in der u_user_page Seite zu erstellen
// $(document).ready(function(){
//     // Standardmäßig wird der erste Tab geöffnet
//     $(".tabcontent:first").show();
    
//     // Beim Klicken auf den Tab-Button wird der entsprechende Inhalt geöffnet
//     $(".tab button").click(function(){
//         var tab_id = $(this).attr("data-tab");
        
//         $(".tab button").removeClass("active");
//         $(".tabcontent").hide();
        
//         $(this).addClass("active");
//         $("#" + tab_id).show();
//     });
// });



// Diese Script um automatische Benutzername zu erstellen
function updateInputUser(){
    var firstNameValue = document.getElementById("firstName").value;
    var lastNameValue = document.getElementById("lastName").value;
    var birthdayValue = document.getElementById("birthday").value;

    var benutzerNameValue = lastNameValue.substr().toLowerCase() +"-"+ firstNameValue.substr(0,2).toUpperCase() + birthdayValue.substr(8,4);
    var kennwortValue = lastNameValue.substr().toLowerCase() +"-"+ firstNameValue.substr(0,2).toUpperCase() + birthdayValue.substr(8,4);
    document.getElementById("userName").value = benutzerNameValue;
    document.getElementById("password").value = kennwortValue;
}

//Um Bilder aus der Datenbank abzurufen und sie mit einem Dropdown-Element anzuzeigen
function chImageMontag() {
    // Elemente auswählen
    var selectList = document.getElementById("option_name_Montag");
    var container = document.getElementById("imageContainerMontag");
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
    xhr.open("GET", "images.php?id=" + optionID , true);
    xhr.send();
}

function chImageDienstag() {
    // Elemente auswählen
    var selectList = document.getElementById("option_name_Dienstag");
    var container = document.getElementById("imageContainerDienstag");
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

function chImageMittwoch() {
    // Elemente auswählen
    var selectList = document.getElementById("option_name_Mittwoch");
    var container = document.getElementById("imageContainerMittwoch");
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

function chImageDonnerstag() {
    // Elemente auswählen
    var selectList = document.getElementById("option_name_Donnerstag");
    var container = document.getElementById("imageContainerDonnerstag");
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

function chImageFreitag() {
    // Elemente auswählen
    var selectList = document.getElementById("option_name_Freitag");
    var container = document.getElementById("imageContainerFreitag");
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



// Funktion, um Tab-Inhalt anzuzeigen
function openTab(evt, tabName) {

    // Variablen deklarieren
    var i, tabcontent, tablinks;
    
    // Alle Tab-Inhalte ausblenden
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    
    // Alle Tab-Links auf inaktiv setzen
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    // Aktuellen Tab-Inhalt anzeigen und den entsprechenden Tab-Link aktivieren
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Index des aktiven Tabs in den Browser-Cookies oder im Local Storage speichern
    var activeTabIndex = Array.prototype.indexOf.call(tablinks, evt.currentTarget);
    localStorage.setItem("activeTabIndex", activeTabIndex);

}




  // Funktion, um sub-Tab-Inhalt anzuzeigen
function openSubTab(evt, subTabName) {
    // Variablen deklarieren
    var i, subtabcontent, subtablinks;
    
    // Alle sub-Tab-Inhalte ausblenden
    subtabcontent = document.getElementsByClassName("subtabcontent");
    for (i = 0; i < subtabcontent.length; i++) {
        subtabcontent[i].style.display = "none";
    }
    
    // Alle sub-Tab-Links auf inaktiv setzen
    subtablinks = document.getElementsByClassName("subtablinks");
    for (i = 0; i < subtablinks.length; i++) {
        subtablinks[i].className = subtablinks[i].className.replace(" active", "");
    }
    
    // Aktuellen sub-Tab-Inhalt anzeigen und den entsprechenden sub-Tab-Link aktivieren
    document.getElementById(subTabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Index des aktiven Tabs in den Browser-Cookies oder im Local Storage speichern
    var activeSubTabIndex = Array.prototype.indexOf.call(subtablinks, evt.currentTarget);
    localStorage.setItem("activeSubTabIndex", activeSubTabIndex);
}

// Beim Laden der Seite den zuletzt aktiven Tab wiederherstellen
window.onload = function() {
    var activeTabIndex = localStorage.getItem("activeTabIndex");
    var activeSubTabIndex = localStorage.getItem("activeSubTabIndex");

    if (activeTabIndex !== null) {
        var tablinks = document.getElementsByClassName("tablinks");
        if (activeTabIndex >= 0 && activeTabIndex < tablinks.length) {
            tablinks[activeTabIndex].click();
        }
    }
    if (activeSubTabIndex !== null) {
        var subtablinks = document.getElementsByClassName("subtablinks");
        if (activeSubTabIndex >= 0 && activeSubTabIndex < subtablinks.length) {
            subtablinks[activeSubTabIndex].click();
        }
    }
};


// Von create_user.php Seite
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
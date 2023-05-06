var btnMontag = document.getElementById('Montag');
var btnDienstag = document.getElementById('Dienstag');
var btnMittwoch = document.getElementById('Mittwoch');
var btnDonnerstag = document.getElementById('Donnerstag');
var btnFreitag = document.getElementById('Freitag');

var kontostand = "<?php echo $kontostand; ?>";
var btnBestellen = document.getElementById('bestellen');
var guthabenError = document.getElementById('guthaben');
var bestellStatus = "<?php echo $bestell_status; ?>"

if(bestellStatus == 1){
    btnBestellen.disabled = true;
}
if(kontostand == 0){
    btnBestellen.disabled = true;
    btnBestellen.style.cursor = 'not-allowed';
    guthabenError.innerHTML = "Ihr aktuelles Guthaben ist 0.00€, bitte laden Sie es auf, um Ihren Einkauf abzuschließen.";
}else{
    if(bestellStatus == 0){
        btnBestellen.disabled = false;
        btnBestellen.style.cursor = 'pointer';
        guthabenError.innerHTML = "";
    }
}

// Define an object to store the prices for each day
var prices = {
    Montag: 0,
    Dienstag: 0,
    Mittwoch: 0,
    Donnerstag: 0,
    Freitag: 0
};
// Define a function to calculate the total price based on the selected options
function calculateTotalPrice() {
    totalPrice = 0;
    // Loop through each day
    for (var day in prices) {
        // Get the selected option for this day
        var selectList = document.getElementById("option_name_" + day);
        var selectedOption = selectList.options[selectList.selectedIndex];
        // Extract the price from the selected option
        var price = parseFloat(selectedOption.text.split("-")[2].replace("€", ""));
        // Update the price for this day in the prices object
        prices[day] = price;
        // Add the price to the total price
        totalPrice += price;
    }
    // Update the total price display
    document.getElementById("totalPrice").innerText = totalPrice.toFixed(2) + "€";
    // Return the total price
    return totalPrice;
}
// Call the calculateTotalPrice function whenever a new option is selected
var selectLists = document.querySelectorAll("select");
for (var i = 0; i < selectLists.length; i++) {
    selectLists[i].addEventListener("change", function() {
        totalPrice = calculateTotalPrice();
        price = calculateTotalPrice();
        // document.cookie = 'totalPrice='+totalPrice;

        if(bestellStatus == 1){
            btnBestellen.disabled = true;
        }
        if(totalPrice > kontostand){
            btnBestellen.disabled = true;
            btnBestellen.style = 'cursor: none; pointer-events: none;';
            if(bestellStatus == 1){
                guthabenError.innerHTML = "Das Guthaben reicht nicht aus, um den Kauf abzuschließen oder Bestellungen aktualisieren.";
            }
        }
        else{
            if(bestellStatus == 0){
                btnBestellen.disabled = false;
                btnBestellen.style = 'cursor: pointer; pointer-events: visible;';
                guthabenError.innerHTML = "";
            }
        }
        

        var statusMontag = "<?php echo $Montag; ?>";
        var statusDienstag = "<?php echo $Dienstag; ?>";
        var statusMittwoch = "<?php echo $Mittwoch; ?>";
        var statusDonnerstag = "<?php echo $Donnerstag; ?>";
        var statusFreitag = "<?php echo $Freitag; ?>";
        if(statusMontag == 1){
            btnMontag.disabled = true;
        }
        if(totalPrice > kontostand){
            btnMontag.disabled = true;
        }else{
            if(statusMontag == 0){
                btnMontag.disabled = false;
                guthabenError.innerHTML = "";
            }
        }
        if(statusDienstag == 1){
            btnDienstag.disabled = true;
        }
        if(totalPrice > kontostand){
            btnDienstag.disabled = true;
        }else{
            if(statusDienstag == 0){
                btnDienstag.disabled = false;
                guthabenError.innerHTML = "";
            }
        }
        if(statusMittwoch == 1){
            btnMittwoch.disabled = true;
        }
        if(totalPrice > kontostand){
            btnMittwoch.disabled = true;
        }else{
            if(statusMittwoch == 0){
                btnMittwoch.disabled = false;
                guthabenError.innerHTML = "";
            }
        }
        if(statusDonnerstag == 1){
            btnDonnerstag.disabled = true;
        }
        if(totalPrice > kontostand){
            btnDonnerstag.disabled = true;
        }else{
            if(statusDonnerstag == 0){
                btnDonnerstag.disabled = false;
                guthabenError.innerHTML = "";
            }
        }
        if(statusFreitag == 1){
            btnFreitag.disabled = true;
        }
        if(totalPrice > kontostand){
            btnFreitag.disabled = true;
        }else{
            if(statusFreitag == 0){
                btnFreitag.disabled = false;
                guthabenError.innerHTML = "";
            }
        }
    });
}
if(kontostand < 25){
    alert('Ihr Guthaben ist sehr niedrig, bitte bald aufladen');
}
<?php

//Stadt basierend auf PLZ suchen durch API abrufen
if (isset($_POST["plz"])) {
	$plz = $_POST["plz"];
	$url = "https://api.zippopotam.us/de/$plz";
	$response = @file_get_contents($url);
	if($response === false){
        echo "Keine Stadt gefunden für PLZ: $plz";
    }else{
        $json = json_decode($response, true);
        if(isset($json['places'][0]['place name'])){
            $stadt = $json['places'][0]['place name'];
            echo $stadt;
        }else{
            echo "Keine Stadt gefunden für PLZ: $plz";
        }
    }
}

?>
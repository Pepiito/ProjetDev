<?php
function urlExists($url=NULL)  
{  
    if($url == NULL) return false;  
    $ch = curl_init($url);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $data = curl_exec($ch);  
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    curl_close($ch);  
    if($httpcode>=200 && $httpcode<300){  
        return 'true';  
    } else {  
        return 'false'; 
    }  
}  


function MN95_to_MN03($E_MN95, $N_MN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv95tolv03?easting='.$E_MN95.'&northing='.$N_MN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$E_lv03 = $json_dec['easting'];
	$N_lv03 = $json_dec['northing'];
	return array($E_lv03,$N_lv03);
}
//list($E_lv03,$N_lv03) = MN95_to_MN03(2571223.123, 1220294.937);



function MN03_to_MN95($E_MN03, $N_MN03){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv03tolv95?easting='.$E_MN03.'&northing='.$N_MN03.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$E_lv95 = $json_dec['easting'];
	$N_lv95 = $json_dec['northing'];
	return array($E_lv95,$N_lv95);
}

// list($E_lv95,$N_lv95) = MN03_to_MN95(571223.123, 220294.937);



function NF02_to_RAN95($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';
	$test=urlExists($url);
	
	if($test=='true'){
		$json = file_get_contents($url);
		$json_dec=json_decode($json, true);
		if(isset($json_dec['altitude'])){
			$H_RAN95 = $json_dec['altitude'];
			return $H_RAN95;
		}else{
			echo 'erreur - coordonnées en dehors de la grille';
			return 'error 500';
		}
	}else{
		echo 'erreur API - Server is not not available right now.';
		return 'erreur 400';
	}
}



$H_RAN95 = NF02_to_RAN95(2476000.223, 12209500.333, 550);
echo $H_RAN95;
// echo $a;

function RAN95_to_NF02($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95toln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_NF02 = $json_dec['altitude'];
	return $H_NF02;
}

// $H_NF02 = RAN95_to_NF02(2571223.223, 1220294.333, 550);



function RAN95_to_Bessel($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_bessel = $json_dec['altitude'];
	return $H_bessel;
}

// $H_bessel = RAN95_to_Bessel(2571223.223, 1220294.333, 550);


function Bessel_to_RAN95($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_RAN95 = $json_dec['altitude'];
	return $H_RAN95;
}

// $H_RAN95 = Bessel_to_RAN95(2571223.223, 1220294.333, 550);


function NF02_to_Bessel($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_bessel = $json_dec['altitude'];
	return $H_bessel;
}

// $H_bessel = NF02_to_Bessel(2571223.223, 1220294.333, 550);


function Bessel_to_NF02($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltoln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_NF02 = $json_dec['altitude'];
	return $H_NF02;
}

// $H_NF02 = NF02_to_Bessel(2571223.223, 1220294.333, 550);


?>

<?php
function MN95_to_MN03($E_MN95, $N_MN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv95tolv03?easting='.$E_MN95.'&northing='.$N_MN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$E_lv03 = $json_dec['easting'];
	$N_lv03 = $json_dec['northing'];
	return array($E_lv03,$N_lv03);
}
list($E_lv03,$N_lv03) = MN95_to_MN03(2571223.123, 1220294.937);
echo 'MN95_to_MN03<br>';
echo $E_lv03.'<br>'.$N_lv03.'<br>';


function MN03_to_MN95($E_MN03, $N_MN03){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv03tolv95?easting='.$E_MN03.'&northing='.$N_MN03.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$E_lv95 = $json_dec['easting'];
	$N_lv95 = $json_dec['northing'];
	return array($E_lv95,$N_lv95);
}
echo 'MN03_to_MN95<br>';
list($E_lv95,$N_lv95) = MN03_to_MN95(571223.123, 220294.937);
echo $E_lv95.'<br>'.$N_lv95.'<br>';


function NF02_to_RAN95($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_RAN95 = $json_dec['altitude'];
	return $H_RAN95;
}
echo 'NF02_to_RAN95<br>';
$H_RAN95 = NF02_to_RAN95(2571223.223, 1220294.333, 550);
echo $H_RAN95.'<br>';

function RAN95_to_NF02($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95toln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_NF02 = $json_dec['altitude'];
	return $H_NF02;
}
echo 'RAN95_to_NF02<br>';
$H_NF02 = RAN95_to_NF02(2571223.223, 1220294.333, 550);
echo $H_NF02.'<br>';


function RAN95_to_Bessel($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_bessel = $json_dec['altitude'];
	return $H_bessel;
}
echo 'RAN95_to_Bessel<br>';
$H_bessel = RAN95_to_Bessel(2571223.223, 1220294.333, 550);
echo $H_bessel.'<br>';

function Bessel_to_RAN95($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_RAN95 = $json_dec['altitude'];
	return $H_RAN95;
}
echo 'Bessel_to_RAN95<br>';
$H_RAN95 = Bessel_to_RAN95(2571223.223, 1220294.333, 550);
echo $H_RAN95.'<br>';

function NF02_to_Bessel($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_bessel = $json_dec['altitude'];
	return $H_bessel;
}
echo 'NF02_to_Bessel<br>';
$H_bessel = NF02_to_Bessel(2571223.223, 1220294.333, 550);
echo $H_bessel.'<br>';

function Bessel_to_NF02($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltoln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';
	$json = file_get_contents($url);
	$json_dec=json_decode($json, true);
	$H_NF02 = $json_dec['altitude'];
	return $H_NF02;
}
echo 'NF02_to_Bessel<br>';
$H_NF02 = NF02_to_Bessel(2571223.223, 1220294.333, 550);
echo $H_NF02.'<br>';

?>

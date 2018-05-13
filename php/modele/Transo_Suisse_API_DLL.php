<?php
#HEIG
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
    return($httpcode>=200 && $httpcode<300);
}

function testTransfoPlani_MN95_to_MN03($e, $n, $e2, $n2){
    $de =abs($e-$e2-2000000);
    $dn = abs($n-$n2-1000000);
    if($e<$de+15000 AND $e>$de-15000 AND $n<$dn+15000 AND $n>$dn-15000 ){
        return array($e2, $n2);
    }elseif($de<0.001 AND $dn<0.001){
        echo 'Erreur 400: Coordonnées MN95 en dehors du perimètre de transformation vers MN03';
        exit;
    }else{
        return array($e2, $n2);
    }
}

function testTransfoPlani_MN03_to_MN95($e, $n, $e2, $n2){
    $de =abs($e-$e2+2000000);
       $dn = abs($n-$n2+1000000);
    if($e<$de+15000 AND $e>$de-15000 AND $n<$dn+15000 AND $n>$dn-15000 ){
        return array($e2, $n2);
    }elseif($de<0.001 AND $dn<0.001){
        echo 'Erreur 401: Coordonnées MN03 en dehors du perimètre de transformation vers MN95';
        exit;
    }else{
        return array($e2, $n2);
    }
}

function MN95_to_MN03($E_MN95, $N_MN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv95tolv03?easting='.$E_MN95.'&northing='.$N_MN95.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['easting'])){
            $E_lv03 = $json_dec['easting'];
            $N_lv03 = $json_dec['northing'];
            $transf_coord = testTransfoPlani_MN95_to_MN03($E_MN95, $N_MN95, $E_lv03, $N_lv03);
            return $transf_coord;
            }else{
                echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
                exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}
// list($E_lv03,$N_lv03) = MN95_to_MN03(2571223.123, 1220294.937);


function MN03_to_MN95($E_MN03, $N_MN03){
	$url = 'http://geodesy.geo.admin.ch/reframe/lv03tolv95?easting='.$E_MN03.'&northing='.$N_MN03.'&format=json';

	if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['easting'])){
            $E_lv95 = $json_dec['easting'];
            $N_lv95 = $json_dec['northing'];
            $transf_coord = testTransfoPlani_MN03_to_MN95($E_MN03, $N_MN03, $E_lv95, $N_lv95);
            return $transf_coord;
        }else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}

// list($E_lv95,$N_lv95) = MN03_to_MN95(571223.123, 220294.937);



function NF02_to_RAN95($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';

	if(urlExists($url)){
		$json = file_get_contents($url);
		$json_dec=json_decode($json, true);
		if(isset($json_dec['altitude'])){
			$H_RAN95 = $json_dec['altitude'];
			return $H_RAN95;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}



//$H_RAN95 = NF02_to_RAN95(2518000.223, 1166250.333, 550);


function RAN95_to_NF02($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95toln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['altitude'])){
			$H_NF02 = $json_dec['altitude'];
			return $H_NF02;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Server is not not available right nowe';
        exit;
	}
}

//$H_NF02 = RAN95_to_NF02(2571223.223, 1220294.333, 550);


function RAN95_to_Bessel($E_MN03, $N_MN03, $H_RAN95){
	$url = 'http://geodesy.geo.admin.ch/reframe/lhn95tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_RAN95.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['altitude'])){
			$H_bessel = $json_dec['altitude'];
			return $H_bessel;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}

//$H_bessel = RAN95_to_Bessel(2571223.223, 1220294.333, 550);


function Bessel_to_RAN95($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltolhn95?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['altitude'])){
			$H_RAN95 = $json_dec['altitude'];
			return $H_RAN95;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}

//$H_RAN95 = Bessel_to_RAN95(2571223.223, 1220294.333, 550);


function NF02_to_Bessel($E_MN03, $N_MN03, $H_NF02){
	$url = 'http://geodesy.geo.admin.ch/reframe/ln02tobessel?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_NF02.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['altitude'])){
			$H_bessel = $json_dec['altitude'];
			return $H_bessel;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}

//$H_bessel = NF02_to_Bessel(2571223.223, 1220294.333, 550);


function Bessel_to_NF02($E_MN03, $N_MN03, $H_bessel){
	$url = 'http://geodesy.geo.admin.ch/reframe/besseltoln02?easting='.$E_MN03.'&northing='.$N_MN03.'&altitude='.$H_bessel.'&format=json';

    if(urlExists($url)){
        $json = file_get_contents($url);
        $json_dec=json_decode($json, true);
        if(isset($json_dec['altitude'])){
			$H_NF02 = $json_dec['altitude'];
			return $H_NF02;
		}else{
			echo 'Erreur 402: Coordonnées en dehors du modèle de géoïde suisse';
            exit;
		}
	}else{
		echo 'Erreur 450: Erreur API - Votre connexion internet est peut-être trop lente';
        exit;
	}
}

//$H_NF02 = NF02_to_Bessel(2571223.223, 1220294.333, 550);

?>

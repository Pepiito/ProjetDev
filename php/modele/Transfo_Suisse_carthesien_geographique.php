<?php
#Variables pour controler les calculs

$lambda=6.60472222;
$phi=46.80277778;
$h=510.4000;

$E1903plus=4344342.5801;
$N1903plus=503083.4649;
$H1903plus=4626751.1563;


# DÃ©finition des variables

# Demi grand axe de l'ellipsoÃ¯de de Bessel

$Bessel_a=6377397.155;

# CarrÃ© de la premiÃ¨re excentricitÃ© numÃ©rique de l'ellipsoÃ¯de de Bessel
$Bessel_e=0.006674372230614;

# Demi grand axe de l'ellipsoÃ¯de du GRS80
$GRS80_a=6378137;

# CarrÃ© de la premiÃ¨re excentricitÃ© numÃ©rique du GRS80
$GRS80_e=0.006694380023011;

# Latitude gÃ©ographique de l'origine Ã  Berne [radians]
$phi_Berne=0.819474068676122;

# Latitude gÃ©ographique de l'origine Ã  Berne [radians]
$lambda_Berne=0.129845224143161;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

# DÃ©finition des calculs

# Transformation degre-radian

function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}
# Transformation coordonnees geographiques ETRS89 --> coordonnees carthesienne gÃ©ocentrique ETRS89

function LambdaPhiH_to_XYZ($lambda, $phi, $h, $GRS80_a, $GRS80_e){
	$w=sqrt(1-$GRS80_e*(sin($phi))**2);
	$X=($GRS80_a/$w+$h)*cos($phi)*cos($lambda);
	$Y=($GRS80_a/$w+$h)*cos($phi)*sin($lambda);
	$Z=($GRS80_a/$w*(1-$GRS80_e)+$h)*sin($phi);
	return array($X,$Y,$Z);
}

#list($X,$Y,$Z)=LambdaPhiH_to_XYZ(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);
#echo $X;

#Transformation coordonnees carthesienne gÃ©ocentrique ETRS89 --> coordonnees carthesienne gÃ©ocentrique CH1903+

function carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $X1903plus=$X+$Bessel_dx;
    $Y1903plus=$Y+$Bessel_dy;
    $Z1903plus=$Z+$Bessel_dz;
    return array($X1903plus,$Y1903plus,$Z1903plus);
}

#list($X1903plus,$Y1903plus,$Z1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz);

#echo $X1903plus;

# Fonction qui permet de passer des coordonnÃ©es geographiques ETRS89 --> coordonnees carthesienne gÃ©ocentrique ETRS89 --> coordonnees carthesienne gÃ©ocentrique CH1903+

function geog_ETRS89_to_cart_CH1903plus($lambda,$phi,$h,$GRS80_a, $GRS80_e,$Bessel_dx, $Bessel_dy, $Bessel_dz){
    list($X,$Y,$Z)=LambdaPhiH_to_XYZ(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);
    list($X1903plus,$Y1903plus,$Z1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz);
    return array($X1903plus,$Y1903plus,$Z1903plus);
}

list($X1903plus,$Y1903plus,$Z1903plus)=geog_ETRS89_to_cart_CH1903plus($lambda,$phi,$h,$GRS80_a, $GRS80_e,$Bessel_dx, $Bessel_dy, $Bessel_dz);

#echo $X1903plus;

# Transformation coordonnees carthesienne gÃ©ocentrique CH1903+ --> coordonnees geographiques CH1903+ (ellipsoÃ¯dales)

function XYZ_to_LambdaPhiH($E1903plus, $N1903plus, $H1903plus, $Bessel_a, $Bessel_e, $epsilon){
    $r=sqrt($E1903plus**2+$N1903plus**2);
    $lambda=2*tan($N1903plus/($E1903plus+$r));
    $phi=atan($H1903plus/$r);
	$deviation=1;
    while ($deviation>=$epsilon){
		$phi0=$phi;
        $wn=sqrt(1-$Bessel_e*(sin($phi))**2);
        $vn=$Bessel_a/$wn;
        $hn=$r*cos($phi0)+$H1903plus*sin($phi0)-$Bessel_a*$wn;
        $phi=atan($H1903plus*($vn+$hn)/($r*($vn*(1-$Bessel_e)+$hn)));
		$deviation=abs($phi-$phi0);
    }
    return array($lambda,$phi,$hn);
}

list($lambda,$phi,$hn)=XYZ_to_LambdaPhiH($E1903plus, $N1903plus, $H1903plus, $Bessel_a, $Bessel_e, $Epsilon);
#echo $hn;

?>

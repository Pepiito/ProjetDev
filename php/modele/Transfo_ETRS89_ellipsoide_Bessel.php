<?php
#Variables pour controler les calculs

$lambda=6.60472222;
$phi=46.80277778;
$h=510.4000;

# Définition des variables

$GRS80_a=6378137;
$GRS80_e=0.006694380023011;

$Bessel_a=6377397.155;
$Bessel_e=0.006674372230614;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001

# Définition des calculs

# Transformation degre-radian

function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}

# Transformation coordonnees geographiques --> coordonnees carthesienne

function LambdaPhiH_to_XYZ($lambda, $phi, $h, $a, $e){
	$w=sqrt(1-($e*sin($phi))**2);
	$X=($a/$w+$h)*cos($phi)*cos($lambda);
	$Y=($a/$w+$h)*cos($phi)*sin($lambda);
	$Z=($a*(1-$e**2)/$w+$h)*sin($phi);	
	return array($X,$Y,$Z);	
}

list($X,$Y,$Z)=LambdaPhiH_to_XYZ(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);
echo $X;

#Transformation coordonnees carthesienne ETRS89 --> coordonnees carthesienne CH1903+

function carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $X1903plus=$X+$Bessel_dx;
    $Y1903plus=$Y+$Bessel_dy;
    $Z1903plus=$Z+$Bessel_dz;
    return array($X1903plus,$Y1903plus,$Z1903plus);
}

list($X1903plus,$Y1903plus,$Z1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz);
echo $X1903plus;

# Transformation coordonnees carthesienne --> coordonnees geographiques

function XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $epsilon){
    $r=sqrt($X1903plus**2+$Y1903plus**2);
    $lambda=2*tan($Y1903plus/($X1903plus+$r));
    $phi0=atan($Z1903plus/$r);
    while (abs($phi1-$phi0)<=$epsilon);
        $wn=sqrt(1-$Bessel_e*(sin($phi0))**2);
        $vn=$Bessel_a/$wn;
        $hn=$r*cos(phi0)+$Z1903plus*sin(phi0)-$Bessel_a*$wn;
        $phi=atan($Z1903plus*($vu+hn)/($r*(vu*(1-$Bessel_e)+hn)));
    
    return array($r,$lambda,$phi0);    
}

list($r,$lambda,$phi0)=XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $epsilon);
echo $r;

?>
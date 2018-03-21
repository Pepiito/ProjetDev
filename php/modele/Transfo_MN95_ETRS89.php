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


# Définition des calculs

# Transformation degre-radian

function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}

# Transformation coordonnees geographiques --> coordonnees carthesienne

function LambdaPhiH_to_XYZ($lambda, $phi, $h, $Bessel_a, $Bessel_e){
	$w=sqrt(1-($Bessel_e*sin($phi))**2);
	$X=($Bessel_a/$w+$h)*cos($phi)*cos($lambda);
	$Y=($Bessel_a/$w+$h)*cos($phi)*sin($lambda);
	$Z=($Bessel_a*(1-$Bessel_e**2)/$w+$h)*sin($phi);	
	return array($X,$Y,$Z);	
}

list($X,$Y,$Z)=LambdaPhiH_to_XYZ(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);
echo $X;

?>
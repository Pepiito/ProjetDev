<?php
#Variables pour controler les calculs

$X=4345016.9541;
$Y=503098.5209;
$Z=4627156.5023;

# Définition des variables

$Bessel_a=6377397.155;
$Bessel_e=0.006674372230614;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

# Définition des calculs

#Transformation coordonnees carthesienne ETRS89 --> coordonnees carthesienne CH1903+

function carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $X1903plus=$X+$Bessel_dx;
    $Y1903plus=$Y+$Bessel_dy;
    $Z1903plus=$Z+$Bessel_dz;
    return array($X1903plus,$Y1903plus,$Z1903plus);
}

list($X1903plus,$Y1903plus,$Z1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz);

#echo $X1903plus;

# Transformation coordonnees carthesienne --> coordonnees geographiques

function XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $epsilon){
    $r=sqrt($X1903plus**2+$Y1903plus**2);
    $lambda=2*tan($Y1903plus/($X1903plus+$r));
    $phi0=atan($Z1903plus/$r);
	$deviation=1;
    while ($deviation>=$epsilon){
		$phi1=$phi0;
        $wn=sqrt(1-$Bessel_e*(sin($phi0))**2);
        $vn=$Bessel_a/$wn;
        $hn=$r*cos($phi1)+$Z1903plus*sin($phi1)-$Bessel_a*$wn;
        $phi0=atan($Z1903plus*($vn+$hn)/($r*($vn*(1-$Bessel_e)+$hn)));
		$deviation=abs($phi0-$phi1);
    }
    return array($r,$lambda,$phi0);    
}

list($r,$lambda,$phi0)=XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $Epsilon);
#echo $lambda;

?>
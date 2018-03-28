<?php
#Variables pour controler les calculs

$lambda=0.148115967;
$phi=0.821317799;
$h=510.4000;

# Définition des variables

# Demi grand axe de l'ellipsoïde de Bessel

$Bessel_a=6377397.155;

# Carré de la première excentricité numérique de l'ellipsoïde de Bessel
$Bessel_e=0.006674372230614;

# Demi grand axe de l'ellipsoïde du GRS80
$GRS80_a=6378137;

# Carré de la première excentricité numérique du GRS80
$GRS80_e=0.006694380023011;

# Latitude géographique de l'origine à Berne [radians]
$phi_Berne=0.819474068676122;

# Latitude géographique de l'origine à Berne [radians]
$lambda_Berne=0.115274149;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

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
#echo $X;

#Transformation coordonnees carthesienne ETRS89 --> coordonnees carthesienne CH1903+

function carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $X1903plus=$X+$Bessel_dx;
    $Y1903plus=$Y+$Bessel_dy;
    $Z1903plus=$Z+$Bessel_dz;
    return array($X1903plus,$Y1903plus,$Z1903plus);
}

function carthesienne_CH1903plus_to_carthesienne_ETRS89($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $XETRS=$X-$Bessel_dx;
    $YETRS=$Y-$Bessel_dy;
    $ZETRS=$Z-$Bessel_dz;
    return array($XETRS,$YETRS,$ZETRS);
}

list($X1903plus,$Y1903plus,$Z1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($X, $Y, $Z, $Bessel_dx, $Bessel_dy, $Bessel_dz);

#echo $X1903plus;

# Transformation coordonnees carthesienne CH1903+ --> coordonnees geographiques CH1903+ (ellipsoïdales)

function XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $epsilon){
    $r=sqrt($X1903plus**2+$Y1903plus**2);
    $lambda=2*tan($Y1903plus/($X1903plus+$r));
    $phi=atan($Z1903plus/$r);
	$deviation=1;
    while ($deviation>=$epsilon){
		$phi0=$phi;
        $wn=sqrt(1-$Bessel_e*(sin($phi))**2);
        $vn=$Bessel_a/$wn;
        $hn=$r*cos($phi0)+$Z1903plus*sin($phi0)-$Bessel_a*$wn;
        $phi=atan($Z1903plus*($vn+$hn)/($r*($vn*(1-$Bessel_e)+$hn)));
		$deviation=abs($phi-$phi0);
    }
    return array($r,$lambda,$phi);
}

list($r,$lambda,$phi)=XYZ_to_LambdaPhiH($X1903plus, $Y1903plus, $Z1903plus, $Bessel_a, $Bessel_e, $Epsilon);
#echo $lambda;

?>

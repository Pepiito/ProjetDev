<?php
#Variables pour controler les calculs

$lambda=6.60472222;
$phi=46.80277778;
$h=510.4000;

# D�finition des variables

# Demi grand axe de l'ellipso�de de Bessel

$Bessel_a=6377397.155;

# Carr� de la premi�re excentricit� num�rique de l'ellipso�de de Bessel
$Bessel_e=0.006674372230614;

# Demi grand axe de l'ellipso�de du GRS80
$GRS80_a=6378137;

# Carr� de la premi�re excentricit� num�rique du GRS80
$GRS80_e=0.006694380023011;

# Latitude g�ographique de l'origine � Berne [radians]
$phi_Berne=0.819474068676122;

# Latitude g�ographique de l'origine � Berne [radians]
$lambda_Berne=0.129845224143161;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

# D�finition des calculs

# Transformation degre-radian
/*
function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}
*/
# Transformation coordonnees geographiques ETRS89 --> coordonnees carthesienne g�ocentrique ETRS89

function geog_ETR89_to_cart_ETRS89($lambda, $phi, $h, $GRS80_a, $GRS80_e){
	$w=sqrt(1-$GRS80_e*(sin($phi))**2);
	$Xcart_ETRS89=($GRS80_a/$w+$h)*cos($phi)*cos($lambda);
	$Ycart_ETRS89=($GRS80_a/$w+$h)*cos($phi)*sin($lambda);
	$Zcart_ETRS89=($GRS80_a/$w*(1-$GRS80_e)+$h)*sin($phi);
	return array($Xcart_ETRS89,$Ycart_ETRS89,$Zcart_ETRS89);
}

#list($Xcart_ETRS89,$Ycart_ETRS89,$Zcart_ETRS89)=geog_ETR89_to_cart_ETRS89(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);

#echo $Xcart_ETRS89;

#Transformation coordonnees carthesienne g�ocentrique ETRS89 --> coordonnees carthesienne g�ocentrique CH1903+

function carthesienne_ETRS89_to_carthesienne_CH1903plus($Xcart_ETRS89, $Ycart_ETRS89, $Zcart_ETRS89, $Bessel_dx, $Bessel_dy, $Bessel_dz){
    $XcartCH1903plus=$Xcart_ETRS89+$Bessel_dx;
    $YcartCH1903plus=$Ycart_ETRS89+$Bessel_dy;
    $ZcartCH1903plus=$Zcart_ETRS89+$Bessel_dz;
    return array($XcartCH1903plus,$YcartCH1903plus,$ZcartCH1903plus);
}

#list($XcartCH1903plus,$YcartCH1903plus,$ZcartCH1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($Xcart_ETRS89, $Ycart_ETRS89, $Zcart_ETRS89, $Bessel_dx, $Bessel_dy, $Bessel_dz);

#echo $XcartCH1903plus;

# Transformation coordonnees carthesienne g�ocentrique CH1903+ --> coordonnees geographiques CH1903+ (ellipso�dales)

function cart_CH1903plus_to_geog_CH1903plus($XcartCH1903plus, $YcartCH1903plus, $ZcartCH1903plus, $Bessel_a, $Bessel_e, $Epsilon){
    $r=sqrt($XcartCH1903plus**2+$YcartCH1903plus**2);
    $lambda=2*atan($YcartCH1903plus/($XcartCH1903plus+$r));
    $phi=atan($ZcartCH1903plus/$r);
	$deviation=1;
    while ($deviation>=$Epsilon){
		$phi0=$phi;
        $wn=sqrt(1-$Bessel_e*(sin($phi))**2);
        $vn=$Bessel_a/$wn;
        $hn=$r*cos($phi0)+$ZcartCH1903plus*sin($phi0)-$Bessel_a*$wn;
        $phi=atan($ZcartCH1903plus*($vn+$hn)/($r*($vn*(1-$Bessel_e)+$hn)));
		$deviation=abs($phi-$phi0);
    }
    return array($lambda,$phi,$hn);
}

#list($lambdaCH1903plus,$phiCH1903plus,$hnCH1903plus)=cart_CH1903plus_to_geog_CH1903plus($XcartCH1903plus, $YcartCH1903plus, $ZcartCH1903plus, $Bessel_a, $Bessel_e, $Epsilon);
#echo $lambdaCH1903plus;

# Fonction qui permet de passer des coordonnees geographiques ETRS89 --> coordonnees carthesienne g�ocentrique ETRS89 --> coordonnees carthesienne g�ocentrique CH1903+ --> coordonnees geographiques CH1903+ (ellipso�dales)

function geogGRS80_to_geogCH1903plus($lambda, $phi, $h, $GRS80_a, $GRS80_e,$Bessel_dx, $Bessel_dy, $Bessel_dz, $Bessel_a, $Bessel_e, $Epsilon){
    list($Xcart_ETRS89,$Ycart_ETRS89,$Zcart_ETRS89)=geog_ETR89_to_cart_ETRS89(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);
    list($XcartCH1903plus,$YcartCH1903plus,$ZcartCH1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($Xcart_ETRS89, $Ycart_ETRS89, $Zcart_ETRS89, $Bessel_dx, $Bessel_dy, $Bessel_dz);
    list($lambdaCH1903plus,$phiCH1903plus,$hnCH1903plus)=cart_CH1903plus_to_geog_CH1903plus($XcartCH1903plus, $YcartCH1903plus, $ZcartCH1903plus, $Bessel_a, $Bessel_e, $Epsilon);
    return array ($lambdaCH1903plus,$phiCH1903plus,$hnCH1903plus);
}
#list($lambda,$phi,$hn)=geogGRS80_to_geogCH1903plus($lambda, $phi, $h, $GRS80_a, $GRS80_e,$Bessel_dx, $Bessel_dy, $Bessel_dz, $Bessel_a, $Bessel_e, $Epsilon);

#echo $lambda;

# Transformation coordonnees geographiques CH1903+ (ellipso�dales) --> coordonnees suisse en projection y, x (formules rigoureuses)

# Calcul de valeurs auxiliaires

# Rayon de la sph�re de projection [m�tre]
/*
function rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a){
	$R=($Bessel_a*sqrt(1-$Bessel_e))/(1-$Bessel_e*sin($phi_Berne)**2);
	return array($R);
}
*/
#list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
#echo $R;

#Rapport des longitudes (de la sph�re � l'ellipso�de)
/*
function rapport_longitude($phi_Berne, $Bessel_e){
	$alpha=sqrt(1+$Bessel_e/(1-$Bessel_e)*cos($phi_Berne)**4);
	return array($alpha);
}
*/
#list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
#echo $alpha;

# Latitude de l'origine sur la sph�re [degr� decimal]
/*
function latitude_origine_sphere($phi_Berne, $alpha){
	$b0=asin(sin($phi_Berne)/$alpha);
	return array($b0);
}
*/
#list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
#echo $b0;

#Constante de la formule des latitudes
/*
function constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e){
	$K=log(tan(pi()/4+$b0/2))-$alpha*log(tan(pi()/4+$phi_Berne/2))+$alpha*sqrt($Bessel_e)/2*log((1+sqrt($Bessel_e)*sin($phi_Berne))/(1-sqrt($Bessel_e)*sin($phi_Berne)));
	return array($K);
}
*/
#list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
#echo $K;

# Ellipso�de (phi, lambda) ? Sph�re (b, l) (projection de Gauss)

function ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R){
	$S=$alpha*log(tan(pi()/4+$phi/2))-$alpha*sqrt($Bessel_e)/2*log((1+sqrt($Bessel_e)*sin($phi))/(1-sqrt($Bessel_e)*sin($phi)))+$K;
	$b=2*(atan(exp($S))-pi()/4);
	$l=$alpha*($lambda-$lambda_Berne);
# Syst�me �quatorial (b, l) ? syst�me pseudo-�quatorial ( b, l ) (rotation)
	$l_2=atan(sin($l)/(sin($b0)*tan($b)+cos($b0)*cos($l)));
	$b_2=asin(cos($b0)*sin($b)-sin($b0)*cos($b)*cos($l));
# Sph�re ( b, l ) ? plan de projection (y, x) (projection de Mercator)
	$Yprojection=$R*$l_2;
	$Xprojection=$R/2*log((1+sin($b_2))/(1-sin($b_2)));
	return array($Yprojection, $Xprojection);
}

#list($Yprojection, $Xprojection)=ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R);

#echo $Xprojection;

# Transformation vers LV95 (2'600'000, 1'200'000)

function sphere_to_LV95($Yprojection, $Xprojection){
	$Y_LV95=$Yprojection+2600000;
	$X_LV95=$Xprojection+1200000;
	return array($Y_LV95, $X_LV95);
}

#list($Y_LV95, $X_LV95)=sphere_to_LV95($Yprojection, $Xprojection);

#echo $X_LV95;

# Fonction qui permet de passer de la transformation coordonnees geographiques CH1903+ (ellipso�dales) --> coordonnees suisse en projection y, x (formules rigoureuses)

function geog_to_MN95($lambda, $phi, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne){
    list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
    list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
    list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
    list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
    list($Yprojection, $Xprojection)=ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R);
    list($Y_LV95, $X_LV95)=sphere_to_LV95($Yprojection, $Xprojection);
    return array($Y_LV95, $X_LV95);
}

#list ($Y_LV95, $X_LV95)=geog_to_MN95($lambda, $phi, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

#echo $Y_LV95;
?>

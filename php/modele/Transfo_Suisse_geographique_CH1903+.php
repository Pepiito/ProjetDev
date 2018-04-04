<?php
#Variables pour controler les calculs

$lambda=0.148115967;
$phi=0.821317799;
$h=510.4000;

$E_MN95=2679520.05;
$N_MN95=1212273.44;

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
$lambda_Berne=0.129845224143161;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

# Définition des calculs

# Transformation coordonnees geographiques CH1903+ (ellipsoïdales) --> coordonnees suisse en projection y, x (formules rigoureuses)

# Calcul de valeurs auxiliaires

# Rayon de la sphère de projection [mètre]

function rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a){
	$R=($Bessel_a*sqrt(1-$Bessel_e))/(1-$Bessel_e*sin($phi_Berne)**2);
	return array($R);
}

list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
#echo $R;

#Rapport des longitudes (de la sphère à l'ellipsoïde)

function rapport_longitude($phi_Berne, $Bessel_e){
	$alpha=sqrt(1+$Bessel_e/(1-$Bessel_e)*cos($phi_Berne)**4);
	return array($alpha);
}

list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
#echo $alpha;

# Latitude de l'origine sur la sphère [degré decimal]

function latitude_origine_sphere($phi_Berne, $alpha){
	$b0=asin(sin($phi_Berne)/$alpha);
	return array($b0);
}

list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
#echo $b0;

#Constante de la formule des latitudes

function constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e){
	$K=log(tan(pi()/4+$b0/2))-$alpha*log(tan(pi()/4+$phi_Berne/2))+$alpha*sqrt($Bessel_e)/2*log((1+sqrt($Bessel_e)*sin($phi_Berne))/(1-sqrt($Bessel_e)*sin($phi_Berne)));
	return array($K);
}

list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
#echo $K;

# Ellipsoïde (phi, lambda) ⇒ Sphère (b, l) (projection de Gauss)

function ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R){
	$S=$alpha*log(tan(pi()/4+$phi/2))-$alpha*sqrt($Bessel_e)/2*log((1+sqrt($Bessel_e)*sin($phi))/(1-sqrt($Bessel_e)*sin($phi)))+$K;
	$b=2*(atan(exp($S))-pi()/4);
	$l=$alpha*($lambda-$lambda_Berne);
# Système équatorial (b, l) ⇒ système pseudo-équatorial ( b, l ) (rotation)
	$l_2=atan(sin($l)/(sin($b0)*tan($b)+cos($b0)*cos($l)));
	$b_2=asin(cos($b0)*sin($b)-sin($b0)*cos($b)*cos($l));
# Sphère ( b, l ) ⇒ plan de projection (y, x) (projection de Mercator)
	$Yprojection=$R*$l_2;
	$Xprojection=$R/2*log((1+sin($b_2))/(1-sin($b_2)));
	return array($Yprojection, $Xprojection);
}

list($Yprojection, $Xprojection)=ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R);

#echo $Xprojection;

# Transformation vers LV95 (2'600'000, 1'200'000)

function sphere_to_LV95($Yprojection, $Xprojection){
	$Y_LV95=$Yprojection+2600000;
	$X_LV95=$Xprojection+1200000;
	return array($Y_LV95, $X_LV95);
}

list($Y_LV95, $X_LV95)=sphere_to_LV95($Yprojection, $Xprojection);

#echo $X_LV95;

# Fonction qui permet de passer de la transformation coordonnees geographiques CH1903+ (ellipsoïdales) --> coordonnees suisse en projection y, x (formules rigoureuses)

function geog_to_MN95($lambda, $phi, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne){
    list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
    list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
    list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
    list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
    list($Yprojection, $Xprojection)=ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R);
    list($Y_LV95, $X_LV95)=sphere_to_LV95($Yprojection, $Xprojection);
    return array($Y_LV95, $X_LV95);
}

list ($Y_LV95, $X_LV95)=geog_to_MN95($lambda, $phi, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

echo $Y_LV95;



#Coordonnées suisses en projection (y, x) --> coordonnées ellipsoïdales (λ, φ) (formules rigoureuses)

# Plan de projection (y, x) --> sphère ( b, l )
# Transformation vers LV03 (600'000, 200'000)
# Pour ne pas utiliser les mêmes noms, nous avons pour un sense utilisé l'appelation LV95 et LV03 et pour l'autre sense, MN95 et MN03. LV95 = MN95 et LV03 = MN03 --> traduction français --> allemand

function MN95_to_sphere($E_MN95, $N_MN95){
    $Y_sphere=$E_MN95-2600000;
    $X_sphere=$N_MN95-1200000;
    return array($Y_sphere, $X_sphere);
}

#list($Y_sphere, $X_sphere)=MN95_to_sphere($Y_LV95, $X_LV95);

#echo $Y_sphere;

# # Calcul de valeurs auxiliaires

function val_aux_1($Y_sphere, $R){
    $l_3=$Y_sphere/$R;
    return array($l_3);
}
#list($l_3)=val_aux_1($Y_sphere, $R);

#echo $l_3;

function val_aux_2($X_sphere, $R){
    $b_3=2*(atan(exp($X_sphere/$R))-pi()/4);
    return array($b_3);
}

#list($b_3)=val_aux_2($X_sphere, $R);

#echo $b_3;

# Système pseudo-équatorial ( b, l ) --> système équatorial (b, l)

function pseudo_equatorial_to_equatorial($b0, $b_3, $l_3){
    $b_equatorial=asin(cos($b0)*sin($b_3)+sin($b0)*cos($b_3)*cos($l_3));
    $l_equatorial=atan(sin($l_3)/(cos($b0)*cos($l_3)-sin($b0)*tan($b_3)));
    return array($b_equatorial, $l_equatorial);
}

#list ($b_equatorial, $l_equatorial)=pseudo_equatorial_to_equatorial($b0, $b_3, $l_3);

#echo $b_equatorial;

# Sphère (b, l) --> ellipsoïde (phi, lambda)

function sphere_to_ellipsoide($lambda_Berne, $alpha, $l_equatorial, $K, $Bessel_e, $b_equatorial){
    $lambda_ellipsoide=$lambda_Berne+$l_equatorial/$alpha;
    $phi_ellipsoide=$b_equatorial;
    $phi_ellipsoide_1=0;
    $S_ellipsoide_round=0;
    $S_ellipsoide_round_1=1;
    while ($S_ellipsoide_round!=$S_ellipsoide_round_1){
        $S_ellipsoide=log(tan(pi()/4+$phi_ellipsoide/2));
        $S_ellipsoide_1=1/$alpha*(log(tan(pi()/4+$b_equatorial/2))-$K)+sqrt($Bessel_e)*log(tan(pi()/4+asin(sqrt($Bessel_e)*sin($phi_ellipsoide))/2));
        $phi_ellipsoide_1=2*atan(exp($S_ellipsoide_1))-pi()/2;
        $phi_ellipsoide=$phi_ellipsoide_1;
        $S_ellipsoide_round=round($S_ellipsoide,15);
        $S_ellipsoide_round_1=round($S_ellipsoide_1,15);
    }
    return array($phi_ellipsoide_1, $lambda_ellipsoide);
}

#list ($phi_ellipsoide_1, $lambda_ellipsoide)=sphere_to_ellipsoide($lambda_Berne, $alpha, $l_equatorial, $K, $Bessel_e, $b_equatorial);

#echo $phi_ellipsoide_1;

# Fonction qui permet de passer des coordonnées suisses en projection (y, x) --> coordonnées ellipsoïdales (λ, φ) (formules rigoureuses)

function MN95_to_geog($E_MN95, $N_MN95, $Y_LV95, $X_LV95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne){
	list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
	list($Y_sphere, $X_sphere)=MN95_to_sphere($Y_LV95, $X_LV95);
	list($l_3)=val_aux_1($Y_sphere, $R);
	list($b_3)=val_aux_2($X_sphere, $R);
	list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
	list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
	list($b_equatorial, $l_equatorial)=pseudo_equatorial_to_equatorial($b0, $b_3, $l_3);
	list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
	list($phi_ellipsoide_1, $lambda_ellipsoide)=sphere_to_ellipsoide($lambda_Berne, $alpha, $l_equatorial, $K, $Bessel_e, $b_equatorial);
	return array($phi_ellipsoide_1, $lambda_ellipsoide);
}

list($phi_ellipsoide_1, $lambda_ellipsoide)=MN95_to_geog($E_MN95, $N_MN95, $Y_LV95, $X_LV95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

#echo $phi_ellipsoide_1;

?>

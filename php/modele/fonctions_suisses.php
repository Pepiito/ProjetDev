<?php 
#HEIG
# Calcul de valeurs auxiliaires

function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}

# Rayon de la sphère de projection [mètre]

function rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a){
	$R=($Bessel_a*sqrt(1-$Bessel_e))/(1-$Bessel_e*sin($phi_Berne)**2);
	return array($R);
}

#Rapport des longitudes (de la sphère à l'ellipsoïde)

function rapport_longitude($phi_Berne, $Bessel_e){
	$alpha=sqrt(1+$Bessel_e/(1-$Bessel_e)*cos($phi_Berne)**4);
	return array($alpha);
}

# Latitude de l'origine sur la sphère [degré decimal]

function latitude_origine_sphere($phi_Berne, $alpha){
	$b0=asin(sin($phi_Berne)/$alpha);
	return array($b0);
}

#Constante de la formule des latitudes

function constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e){
	$K=log(tan(pi()/4+$b0/2))-$alpha*log(tan(pi()/4+$phi_Berne/2))+$alpha*sqrt($Bessel_e)/2*log((1+sqrt($Bessel_e)*sin($phi_Berne))/(1-sqrt($Bessel_e)*sin($phi_Berne)));
	return array($K);
}
?>

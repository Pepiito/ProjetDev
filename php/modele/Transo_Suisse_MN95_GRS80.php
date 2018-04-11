<?php

# D�finition des calculs

#Coordonn�es suisses en projection (y, x) --> coordonn�es ellipso�dales (?, f) (formules rigoureuses)

# Plan de projection (y, x) --> sph�re ( b, l )
# Transformation vers LV03 (600'000, 200'000)
# LV95 = MN95 et LV03 = MN03 --> traduction fran�ais --> allemand

function MN95_to_sphere($E_MN95, $N_MN95){
    $Y_sphere=$E_MN95-2600000;
    $X_sphere=$N_MN95-1200000;
    return array($Y_sphere, $X_sphere);
}



# # Calcul de valeurs auxiliaires

function val_aux_1($Y_sphere, $R){
    $l_3=$Y_sphere/$R;
    return array($l_3);
}


function val_aux_2($X_sphere, $R){
    $b_3=2*(atan(exp($X_sphere/$R))-pi()/4);
    return array($b_3);
}


# Syst�me pseudo-�quatorial ( b, l ) --> syst�me �quatorial (b, l)

function pseudo_equatorial_to_equatorial($b0, $b_3, $l_3){
    $b_equatorial=asin(cos($b0)*sin($b_3)+sin($b0)*cos($b_3)*cos($l_3));
    $l_equatorial=atan(sin($l_3)/(cos($b0)*cos($l_3)-sin($b0)*tan($b_3)));
    return array($b_equatorial, $l_equatorial);
}


# Sph�re (b, l) --> ellipso�de (phi, lambda)

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


# Fonction qui permet de passer des coordonn�es suisses en projection (y, x) --> coordonn�es ellipso�dales (?, f) (formules rigoureuses)

function MN95_to_geog($E_MN95, $N_MN95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne){
	list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);
	list($Y_sphere, $X_sphere)=MN95_to_sphere($E_MN95, $N_MN95);
	list($l_3)=val_aux_1($Y_sphere, $R);
	list($b_3)=val_aux_2($X_sphere, $R);
	list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);
	list($b0)=latitude_origine_sphere($phi_Berne, $alpha);
	list($b_equatorial, $l_equatorial)=pseudo_equatorial_to_equatorial($b0, $b_3, $l_3);
	list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);
	list($phi_ellipsoide_1, $lambda_ellipsoide)=sphere_to_ellipsoide($lambda_Berne, $alpha, $l_equatorial, $K, $Bessel_e, $b_equatorial);
	return array($phi_ellipsoide_1, $lambda_ellipsoide);
}


# Transformation coordonnees geographiques CH1903+ (ellipso�dales) --> coordonnees carthesienne g�ocentrique CH1903+

function geog_CH1903plus_to_cart_CH1903plus($lambda, $phi, $h, $Bessel_a, $Bessel_e){
	$w=sqrt(1-$Bessel_e*(sin($phi))**2);
	$Xcart_CH1903plus=($Bessel_a/$w+$h)*cos($phi)*cos($lambda);
	$Ycart_CH1903plus=($Bessel_a/$w+$h)*cos($phi)*sin($lambda);
	$Zcart_CH1903plus=($Bessel_a/$w*(1-$Bessel_e)+$h)*sin($phi);
	return array($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus);
}


# Transformation coordonnees carthesienne g�ocentrique CH1903+ --> coordonnees carthesienne g�ocentrique ETRS89

function cartCH1903plus_to_cartETRS89($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus,$Bessel_dx,$Bessel_dy,$Bessel_dz){
    $XcartETRS89=$Xcart_CH1903plus-$Bessel_dx;
    $YcartETRS89=$Ycart_CH1903plus-$Bessel_dy;
    $ZcartETRS89=$Zcart_CH1903plus-$Bessel_dz;
    return array($XcartETRS89, $YcartETRS89, $ZcartETRS89);
}


# Transformation coordonnees carthesienne g�ocentrique ETRS89 --> coordonnees geographiques ETRS89

function cartETRS89_to_geogETRS89($XcartETRS89, $YcartETRS89, $ZcartETRS89, $GRS80_a, $GRS80_e, $Epsilon){
    $r=sqrt($XcartETRS89**2+$YcartETRS89**2);
    $lambda=2*atan($YcartETRS89/($XcartETRS89+$r));
    $phi=atan($ZcartETRS89/$r);
	$deviation=1;
    while ($deviation>=$Epsilon){
		$phi0=$phi;
        $wn=sqrt(1-$GRS80_e*(sin($phi))**2);
        $vn=$GRS80_a/$wn;
        $hn=$r*cos($phi0)+$ZcartETRS89*sin($phi0)-$GRS80_a*$wn;
        $phi=atan($ZcartETRS89*($vn+$hn)/($r*($vn*(1-$GRS80_e)+$hn)));
		$deviation=abs($phi-$phi0);
    }
    return array($lambda,$phi,$hn);
}


# Fonction qui permet de passer des coordonnees geographiques CH1903+ (ellipso�dales) --> coordonnees carthesienne g�ocentrique CH1903+ --> coordonnees carthesienne g�ocentrique ETRS89 --> coordonnees geographiques ETRS89

function geogCH1903plus_to_geogETRS89($lambda, $phi, $h, $Bessel_dx,$Bessel_dy,$Bessel_dz, $Bessel_a, $Bessel_e, $GRS80_a, $GRS80_e, $Epsilon){
    list($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus)=geog_CH1903plus_to_cart_CH1903plus($lambda, $phi, $h, $Bessel_a, $Bessel_e);
    list ($XcartETRS89, $YcartETRS89, $ZcartETRS89)=cartCH1903plus_to_cartETRS89($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus, $Bessel_dx, $Bessel_dy, $Bessel_dz);
    list($lambda_ETRS89,$phi_ETRS89,$hn_ETRS89)=cartETRS89_to_geogETRS89($XcartETRS89, $YcartETRS89, $ZcartETRS89, $GRS80_a, $GRS80_e, $Epsilon);
    return array($lambda_ETRS89,$phi_ETRS89,$hn_ETRS89);
}
?>

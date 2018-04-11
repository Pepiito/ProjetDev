<?php
include('variables_suisses.php');
include('fonctions_suisses.php');
include('Transo_Suisse_GRS80_MN95.php');
include('Transo_Suisse_MN95_GRS80.php');

$E_MN95 = 1000000;
$N_MN95 = 200000;

$lambda = 7.5;
$phi = 46.5;
$h = 0;

//Test fonctions_suisses
list($R)=rayon_sphere_projection($phi_Berne, $Bessel_e, $Bessel_a);

list($alpha)=rapport_longitude($phi_Berne, $Bessel_e);

list($b0)=latitude_origine_sphere($phi_Berne, $alpha);

list($K)=constante_formule_latitudes($b0, $alpha, $phi_Berne, $Bessel_e);

//Test MN95 vers GRS80
list($Y_sphere, $X_sphere)=MN95_to_sphere($E_MN95, $N_MN95);

list($l_3)=val_aux_1($Y_sphere, $R);

list($b_3)=val_aux_2($X_sphere, $R);

list ($b_equatorial, $l_equatorial)=pseudo_equatorial_to_equatorial($b0, $b_3, $l_3);

list ($phi_ellipsoide_1, $lambda_ellipsoide)=sphere_to_ellipsoide($lambda_Berne, $alpha, $l_equatorial, $K, $Bessel_e, $b_equatorial);

list($phi, $lambda)=MN95_to_geog($E_MN95, $N_MN95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

list($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus)=geog_CH1903plus_to_cart_CH1903plus($lambda, $phi, $h, $Bessel_a, $Bessel_e);

list ($XcartETRS89, $YcartETRS89, $ZcartETRS89)=cartCH1903plus_to_cartETRS89($Xcart_CH1903plus,$Ycart_CH1903plus,$Zcart_CH1903plus, $Bessel_dx, $Bessel_dy, $Bessel_dz);

list($lambda_ETRS89,$phi_ETRS89,$hn_ETRS89)=cartETRS89_to_geogETRS89($XcartETRS89, $YcartETRS89, $ZcartETRS89, $GRS80_a, $GRS80_e, $Epsilon);

list($lambda_ETRS89,$phi_ETRS89,$hn_ETRS89)=geogCH1903plus_to_geogETRS89($lambda, $phi, $h, $Bessel_dx,$Bessel_dy,$Bessel_dz, $Bessel_a, $Bessel_e, $GRS80_a, $GRS80_e, $Epsilon);

//Test GRS80 vers MN95
list($Xcart_ETRS89,$Ycart_ETRS89,$Zcart_ETRS89)=geog_ETR89_to_cart_ETRS89(deg_rad($lambda), deg_rad($phi), $h, $GRS80_a, $GRS80_e);

list($XcartCH1903plus,$YcartCH1903plus,$ZcartCH1903plus)=carthesienne_ETRS89_to_carthesienne_CH1903plus($Xcart_ETRS89, $Ycart_ETRS89, $Zcart_ETRS89, $Bessel_dx, $Bessel_dy, $Bessel_dz);

list($lambdaCH1903plus,$phiCH1903plus,$hnCH1903plus)=cart_CH1903plus_to_geog_CH1903plus($XcartCH1903plus, $YcartCH1903plus, $ZcartCH1903plus, $Bessel_a, $Bessel_e, $Epsilon);

list($lambda,$phi,$hn)=geogGRS80_to_geogCH1903plus($lambda, $phi, $h, $GRS80_a, $GRS80_e,$Bessel_dx, $Bessel_dy, $Bessel_dz, $Bessel_a, $Bessel_e, $Epsilon);

list($Yprojection, $Xprojection)=ellipsoide_to_sphere($lambda, $phi, $K, $b0, $alpha, $Bessel_e, $lambda_Berne, $R);

list($Y_LV95, $X_LV95)=sphere_to_LV95($Yprojection, $Xprojection);

list ($Y_LV95, $X_LV95)=geog_to_MN95($lambda, $phi, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

?>

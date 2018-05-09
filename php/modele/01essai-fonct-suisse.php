<?php
#HEIG

include('fonctions_suisses.php');
include('Transo_Suisse_API_DLL.php');
include('Transo_Suisse_GRS80_MN95.php');
include('Transo_Suisse_MN95_GRS80.php');
include('variables_suisses.php');
include('Deviation_verticale.php');

list($E_lv95,$N_lv95) = MN03_to_MN95(521005.15, 176033.61);
$H_RAN95 = NF02_to_RAN95(521005.15, 176033.61, 910.20);

echo "<p>mn95 $E_lv95 $N_lv95 $H_RAN95</p>";

list($phi_ch, $lam_ch)=MN95_to_geog($E_lv95, $N_lv95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
$H_bessel = RAN95_to_Bessel($E_lv95, $N_lv95, $H_RAN95);

echo "<p>ch1903geog $lam_ch $phi_ch $H_bessel</p>";

list($X_ch, $Y_ch, $Z_ch)=geog_CH1903plus_to_cart_CH1903plus($lam_ch, $phi_ch, $H_bessel, $Bessel_a, $Bessel_e);

echo "<p>ch1903cart $X_ch $Y_ch $Z_ch</p>";

list($X_etrs, $Y_etrs, $Z_etrs)=cartCH1903plus_to_cartETRS89($X_ch,$Y_ch,$Z_ch,$Bessel_dx,$Bessel_dy,$Bessel_dz);

echo "<p>etrs89cart $X_etrs $Y_etrs $Z_etrs</p>";

list($lamb_etrs, $phi_etrs, $h_etrs)=cartETRS89_to_geogETRS89($X_etrs, $Y_etrs, $Z_etrs, $GRS80_a, $GRS80_e, $Epsilon);

echo "<p>etrs89geog $lamb_etrs $phi_etrs $h_etrs</p>";


list($phi_ch, $lam_ch)=MN95_to_geog(2535000, 120000, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
echo $phi_ch, $lam_ch;
?>
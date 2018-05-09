<?php 
#HEIG
#Variables pour controler les calculs

$lambda=6.60472222;
$phi=46.80277778;
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
$lambda_Berne=0.129845224143161;

$Bessel_dx=-674.374;
$Bessel_dy=-15.056;
$Bessel_dz=-405.346;

$Epsilon=0.0000000001;

# Définition des variables pour deviation verticale
$Bessel_lambda=0.11526939778334;
$Bessel_phi=0.816859119428048;

$GRS80_lambda=0.11526939778334;
$GRS80_phi=0.816859119428048;

$vecteur_suisse_eta=-0.000056044462;
$vecteur_suisse_ksi=0.000027876787;
$vecteur_suisse_zeta=1;

?>

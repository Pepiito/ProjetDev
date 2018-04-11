<?php
#Variables pour controler les calculs
# Définition des variables
$Bessel_lambda=0.11526939778334;
$Bessel_phi=0.816859119428048;

$GRS80_lambda=0.11526939778334;
$GRS80_phi=0.816859119428048;

$vecteur_suisse_eta=-0.000056044462;
$vecteur_suisse_ksi=0.000027876787;
$vecteur_suisse_zeta=1;

# Définition des calculs

# Transformation degre-radian

function deg_rad($a){
	$a_rad=$a*pi()/180;
	return $a_rad;
}

# Déviation de la verticale

# Matrice Bessel de rotation repère terrestre géodésique --> repère local géodésique

$matrice_Bessel_tg_to_lg=array($Bessel_lambda, $Bessel_phi);
$matrice_Bessel_tg_to_lg[0]=array(-sin($Bessel_lambda),cos($Bessel_lambda),0);
$matrice_Bessel_tg_to_lg[1]=array((-sin($Bessel_phi))*cos($Bessel_lambda),(-sin($Bessel_phi))*sin($Bessel_lambda),cos($Bessel_phi));
$matrice_Bessel_tg_to_lg[2]=array(cos($Bessel_phi)*cos($Bessel_lambda),cos($Bessel_phi)*sin($Bessel_lambda),sin($Bessel_phi));

#echo $matrice_Bessel_tg_to_lg[0][1];

# Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique

$matrice_Bessel_tg_to_lg_transpose=array($matrice_Bessel_tg_to_lg);
$matrice_Bessel_tg_to_lg_transpose[0]=array($matrice_Bessel_tg_to_lg[0][0],$matrice_Bessel_tg_to_lg[1][0],$matrice_Bessel_tg_to_lg[2][0]);
$matrice_Bessel_tg_to_lg_transpose[1]=array($matrice_Bessel_tg_to_lg[0][1],$matrice_Bessel_tg_to_lg[1][1],$matrice_Bessel_tg_to_lg[2][1]);
$matrice_Bessel_tg_to_lg_transpose[2]=array($matrice_Bessel_tg_to_lg[0][2],$matrice_Bessel_tg_to_lg[1][2],$matrice_Bessel_tg_to_lg[2][2]);

#echo $matrice_Bessel_tg_to_lg_transpose[0][0];

# Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique

$matrice_GRS80_lg_to_cg=array($GRS80_lambda, $GRS80_phi);
$matrice_GRS80_lg_to_cg[0]=array(-sin($GRS80_lambda),cos($GRS80_lambda),0);
$matrice_GRS80_lg_to_cg[1]=array((-sin($GRS80_phi))*cos($GRS80_lambda),(-sin($GRS80_phi))*sin($GRS80_lambda),cos($GRS80_phi));
$matrice_GRS80_lg_to_cg[2]=array(cos($GRS80_phi)*cos($GRS80_lambda),cos($GRS80_phi)*sin($GRS80_lambda),sin($GRS80_phi));

#echo $matrice_GRS80_lg_to_cg[0][1];

# Matrice du résultat du produit matriciel 
#	--> Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique
#	--> Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique

$matrice_produit_matriciel=array($matrice_GRS80_lg_to_cg, $matrice_Bessel_tg_to_lg_transpose);
$matrice_produit_matriciel[0]=array($matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
									$matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][1], 
									$matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);
$matrice_produit_matriciel[1]=array($matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
									$matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][1],
									$matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);
$matrice_produit_matriciel[2]=array($matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
									$matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][1],
									$matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);

#echo $matrice_produit_matriciel[0][0];

# Matrice du résultat du produit matriciel qui donne le vecteur eta,ksi et zéta de la france en radians
#	--> Résultat du produit matriciel des Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique et Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique
#	--> Vecteur eta, ksi et zéta suisse

$matrice_vecteur_france=array($matrice_produit_matriciel, $vecteur_suisse_eta, $vecteur_suisse_ksi, $vecteur_suisse_zeta);
$matrice_vecteur_france[0]=array($matrice_produit_matriciel[0][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[0][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[0][2]*$vecteur_suisse_zeta);
$matrice_vecteur_france[1]=array($matrice_produit_matriciel[1][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[1][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[1][2]*$vecteur_suisse_zeta);
$matrice_vecteur_france[2]=array($matrice_produit_matriciel[2][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[2][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[2][2]*$vecteur_suisse_zeta);

#echo $matrice_vecteur_france[0][0];

?>
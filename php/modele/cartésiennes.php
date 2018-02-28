<?php
include("variable.php");

/**
* Fonction cartesien_to_geographic
*
* Transforme des coordonnées cartésiens en coordonnées géographiques associées à un éllipsoïde
* Les coordonnées sont entrées en mètres et sont données en radians et en mètres
*
* @param float $X première coordonnées cartésienne
* @param float $Y deuxième coordonnées cartésienne
* @param float $Z troisième coordonnées cartésienne
* @param Ellipse $ellipse
* @return array avec les trois coordonnées géographiques
*/
function cartesien_to_geographic($X, $Y, $Z, $ellipse) {
  $a = $ellipse->__get('a');
  $b = $ellipse->__get('b');
  $e = $ellipse->__get('e');
  $f = $ellipse->__get('f');

  $R = ($X**2 + $Y**2 + $Z**2)**(1/2);
  $mu = atan($Z/($X**2 + $Y**2)**(1/2)*((1-$f)+($e**2*$a/$R)));

  $lambda = atan($Y/$X);
  $phi = atan(($Z*(1-$f)+$e**2*$a*sin($mu)**3)/((1-$f)*(($X**2+$Y**2)**(1/2)-$e**2*$a*cos($mu)**3)));
  $h = (($X**2+$Y**2)**(1/2)*cos($phi)+$Z*sin($phi)-$a*(1-$e**2*sin($phi)**2)**(1/2));

  return array($lambda, $phi, $h);
}

/**
* Fonction geographic_to_cartesien
*
* Transforme des coordonnées géographiques associées à un éllipsoïde en coordonnées cartésiens associées à un éllipsoïde
* Les coordonnées sont entrées en radians et mètres et sont données en mètres
*
* @param float $lambda première coordonnées géographique
* @param float $phi deuxième coordonnées géographique
* @param float $h troisième coordonnées géographique
* @param Ellipse $ellipse
* @return array avec les trois coordonnées cartésiens
*/
function geographic_to_cartesien($lambda, $phi, $h, $ellipse) {
  $a = $ellipse->__get('a');
  $e = $ellipse->__get('e');
  $w = (1-$e**2*sin($phi)**2)**(1/2);
  $N = $a/$w;

  $X = ($N + $h)*cos($lambda)*cos($phi);
  $Y = ($N + $h)*sin($lambda)*cos($phi);
  $Z = $N*(1-$e**2)+$h)*sin($phi);

  return array($X, $Y, $Z);
}

function RGF_to_NTF($X, $Y, $Z) {
  $ellipse = Ellipse("IAG_GRS_1980");
  $geog = cartesien_to_geographic($X, $Y, $Z, $ellipse);
  $grille = lecture_fichier("../../files/gr3df97a.txt");
}
 ?>

<?php
include("variables.php");

/**
* Fonction conique_to_geog
*
* Retourne les deux premières coordonnées projetées en représentation conique sur un cone donnée obtenues à partir des coordonnées géographiques
*
* @param float $X première coordonnée géographique
* @param float $Y deuxième coordonnée géographique
* @param Cone $cone cone de projection
* @param Ellipse $ellipse ellipsoide de référence
* @return array
*/
function proj_sur_conique($lambda, $phi, $cone, $ellipse) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $C = $cone->__get('C');
  $n = $cone->__get('n');
  $R0 = $cone->__get('R0');
  $lambda0 = $cone->__get('lambda0');

  $X = $X0 + $C*exp(-$n * L($phi, $ellipse))*sin($n*($lambda-$lambda0));
  $Y = $Y0 + $R0 - $C*exp(-$n * L($phi, $ellipse))*cos($n*($lambda-$lambda0));
  return array($X, $Y);
}

/**
* Fonction conique_to_geog
*
* Retourne les deux premières coordonnées géographiques obtenues à partir des coordonnées projetées en représentation conique
*
* @param float $X première coordonnée projetée
* @param float $Y deuxième coordonnée projetée
* @param Cone $cone cone de projection
* @param Ellipse $ellipse ellipsoide de référence
* @return array
*/
function conique_to_geog($X, $Y, $cone, $ellipse) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $C = $cone->__get('C');
  $n = $cone->__get('n');
  $R0 = $cone->__get('R0');
  $lambda0 = $cone->__get('lambda0');

  $lambda = $lambda0 + atan(($X - $X0)/($Y0 + $R0 - $Y))/$n;
  $L = -log((($X - $X0)**2 + ($Y0 + $R0 - $Y)**2)**(1/2)/$C)/$n;
  $phi = Linverse($L, $ellipse);

  return array($lambda, $phi)
}

/**
* Fonction L
*
* Retourne la latitude isométrique en radian obtenue à partir de la latitude géographique en radian
*
* @param float $phi
* @param Ellipse $ellipse
* @return float
*/
function L($phi, $ellipse) {
  $e = $ellipse->__get('e');

  return log(tan(pi()/4 + $phi/2)) - $e/2*log((1 + $e*sin($phi))/(1 - $e*sin($phi)));
}

/**
* Fonction Linverse
*
* Retourne la latitude géographique en radian obtenue à partir de la latitude isométrique en radian
*
* @param float $L
* @param Ellipse $ellipse
* @return float
*/
function Linverse($L, $ellipse) {
  $e = $ellipse->__get('e');

  $phi0 = 2*atan(exp($L)) - pi()/2;
  $ecart = 1;
  while($ecart>0.0000000001) {
    $phi1 = 2*atan(((1 + $e*sin($phi0))/(1 - $e*sin($phi0)))**($e/2)*exp($L)) - pi()/2;
    $ecart = abs($phi1 - $phi0);
    $phi0 = $phi1;
  }
  return $phi;
}
 ?>

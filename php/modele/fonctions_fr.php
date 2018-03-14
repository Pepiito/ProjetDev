<?php
include("variables.php");

/**
* Fonction proj_to_CC
*
* Retourne les deux premières coordonnées projetées en représentation conique sur un cone donnée obtenues à partir des coordonnées géographiques
*
* @param float $lambda première coordonnée géographique
* @param float $phi deuxième coordonnée géographique
* @param Cone_CC $cone cone de projection
* @return array
*/
function proj_to_CC($lambda, $phi, $cone) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $phi0 = $cone->__get('phi0');
  $C = $cone->__get('C');
  $n = $cone->__get('n');
  $lambda0 = $cone->__get('lambda0');

  $R = $C*exp(-$n*$cone->L_CC($phi));
  $X = $X0 + $R*sin($n*($lambda-$lambda0));
  $Y = $Y0 + $C*exp(-$n*$cone->L_CC($phi0)) - $R*cos($n*($lambda-$lambda0));
  return array($X, $Y);
}

/**
* Fonction CC_to_geog
*
* Retourne les deux premières coordonnées géographiques obtenues à partir des coordonnées projetées en représentation conique
*
* @param float $X première coordonnée projetée
* @param float $Y deuxième coordonnée projetée
* @param Cone_CC $cone cone de projection
* @return array
*/
function CC_to_geog($X, $Y, $cone) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $phi0 = $cone->__get('phi0');
  $C = $cone->__get('C');
  $n = $cone->__get('n');
  $lambda0 = $cone->__get('lambda0');

  $Ys = $Y0 + $C*exp(-$n*$cone->L_CC($phi0));
  $R = (($X - $X0)**2 + ($Y - $Ys)**2)**(1/2);
  $lambda = $lambda0 + atan(($X - $X0)/($Ys - $Y))/$n;
  $L = -log($R/abs($C))/$n;
  $phi = L_iso_inverse($L, $cone->__get('ellipse'));

  return array($lambda, $phi);
}


/**
* Fonction proj_to_Lambert
*
* Retourne les deux premières coordonnées projetées en représentation conique sur un cone donnée obtenues à partir des coordonnées géographiques
*
* @param float $lambda première coordonnée géographique
* @param float $phi deuxième coordonnée géographique
* @param Cone_Lambert $cone cone de projection
* @return array
*/
function proj_to_Lambert($lambda, $phi, $cone) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $k0 = $cone->__get('k0');
  $phi0 = $cone->__get('phi0');
  $n = $cone->__get('n');
  $lambda0 = $cone->__get('lambda0');
  $ellipse = $cone->__get('ellipse');

  $R0 = $ellipse->__get('a')/(1 - $ellipse->__get('e')**2*sin($phi0)**2)**(1/2)*$k0/tan($phi0);
  $C = $R0*exp($n*L_iso($phi0, $ellipse));
  $X = $X0 + $C*exp(-$n*L_iso($phi, $ellipse))*sin($n*($lambda - $lambda0));
  $Y = $Y0 + $R0 - $C*exp(-$n*L_iso($phi, $ellipse))*cos($n*($lambda - $lambda0));
  return array($X, $Y);
}


/**
* Fonction Lambert_to_geog
*
* Retourne les deux premières coordonnées géographiques obtenues à partir des coordonnées projetées en représentation conique
*
* @param float $X première coordonnée projetée
* @param float $Y deuxième coordonnée projetée
* @param Cone_Lambert $cone cone de projection
* @return array
*/
function Lambert_to_geog($X, $Y, $cone) {
  $X0 = $cone->__get('X0');
  $Y0 = $cone->__get('Y0');
  $k0 = $cone->__get('k0');
  $phi0 = $cone->__get('phi0');
  $n = $cone->__get('n');
  $lambda0 = $cone->__get('lambda0');
  $ellipse = $cone->__get('ellipse');

  $R0 = $ellipse->__get('a')/(1 - $ellipse->__get('e')**2*sin($phi0)**2)**(1/2)*$k0/tan($phi0);
  $C = $R0*exp($n*L_iso($phi0, $ellipse));
  $Ys = $Y0 + $R0;
  $R = (($X - $X0)**2 + ($Y - $Ys)**2)**(1/2);
  $lambda = $lambda0 + atan(($X - $X0)/($Ys - $Y))/$n;
  $L = -log($R/abs($C))/$n;
  $phi = L_iso_inverse($L, $cone->__get('ellipse'));

  return array($lambda, $phi);
}


/**
* Fonction L
*
* Retourne la latitude isométrique en mètres obtenue à partir de la latitude géographique en radian
*
* @param float $phi
* @param Ellipse $ellipse
* @return float
*/
function L_iso($phi, $ellipse) {
  $e = $ellipse->__get('e');

  return log(tan(pi()/4 + $phi/2)) - $e/2*log((1 + $e*sin($phi))/(1 - $e*sin($phi)));
}

/**
* Fonction Linverse
*
* Retourne la latitude géographique en radian obtenue à partir de la latitude isométrique en mètres
*
* @param float $L
* @param Ellipse $ellipse
* @return float
*/
function L_iso_inverse($L, $ellipse) {
  $e = $ellipse->__get('e');

  $phi0 = 2*atan(exp($L)) - pi()/2;
  $ecart = 1;
  while($ecart>0.0000000001) {
    $phi1 = 2*atan(((1 + $e*sin($phi0))/(1 - $e*sin($phi0)))**($e/2)*exp($L)) - pi()/2;
    $ecart = abs($phi1 - $phi0);
    $phi0 = $phi1;
  }
  return $phi0;
}

$a = new Cone_Lambert('Lambert3');
$b = proj_to_Lambert(3.5*pi()/180, 44.1*pi()/180, $a);
echo $b[0]." ".$b[1];
 ?>

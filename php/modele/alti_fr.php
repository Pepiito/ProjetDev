<?php
include("lecture_fichier.php");

/**
* Fonction h_to_alti
*
* Retourne un altitude par rapport aux coordonnÃ©es gÃ©ographique donnÃ©es en radian et mÃ¨tre
*
* @param float $lambda premiÃ¨re coordonnÃ©e gÃ©ographique
* @param float $phi deuxiÃ¨me coordonnÃ©e gÃ©ographique
* @param float $h troisiÃ¨me coordonnÃ©e gÃ©ographique
* @return float
*/
function h_to_alti($lambda, $phi, $h) {
  $N = lecture_grille_alti($lambda, $phi);
  return $h - $N;
}

/**
* Fonction h_to_alti
*
* Retourne un hauteur par rapport aux coordonnÃ©es gÃ©ographique et une altitude donnÃ©es en radian et mÃ¨tre
*
* @param float $lambda premiÃ¨re coordonnÃ©e gÃ©ographique
* @param float $phi deuxiÃ¨me coordonnÃ©e gÃ©ographique
* @param float $H altitude
* @return float
*/
function alti_to_h($lambda, $phi, $H) {
  $N = lecture_grille_alti($lambda, $phi);
  return $H + $N;
}

/**
* Fonction lecture_grille_alti
*
* Retourne l'ondulation du gÃ©oÃ¯de Ã  une position donnÃ©e en coordonnÃ©es gÃ©ographiques
*
* @param float $lambda premiÃ¨re coordonnÃ©e gÃ©ographique
* @param float $phi deuxiÃ¨me coordonnÃ©e gÃ©ographique
* @return float
*/
function lecture_grille_alti($lambda, $phi) {
  $lambda = $lambda*180/pi();
  $phi = $phi*180/pi();
  //lecture du contenu de la grille
  $grille = lecture_fichier("../../files/RAF09.mnt");
  $tab = explode(" ", $grille);

  $lambda0 = $tab[0];
  $lambda1 = $tab[1];
  $phi0 = $tab[2];
  $phi1 = $tab[3];
  $paslambda = $tab[4];
  $pasphi = $tab[5];

  $tab[19] = substr($tab[19], 5);
  $k = 19;

  $l = ($lambda1 - $lambda0)/$paslambda * (1 - ($lambda1 - $lambda)/(($lambda1 - $lambda0)));
  $c = ($phi1 - $phi0)/$pasphi * ($phi1 - $phi)/(($phi1 - $phi0));

  $l0 = floor($l);
  $c0 = floor($c);
  $l1 = ceil($l);
  $c1 = ceil($c);
  if ($c0 == $c1) {
    $c1 += 1;
  }
  if ($l0 == $l1) {
    $l1 += 1;
  }

  $N1 = $tab[$k + ($l0 + 421*$c0)*2];
  $N2 = $tab[$k + ($l1 + 421*$c0)*2];
  $N3 = $tab[$k + ($l0 + 421*$c1)*2];
  $N4 = $tab[$k + ($l1 + 421*$c1)*2];

  $x = ($l - $l0)/($l1 - $l0);
  $y = ($c - $c0)/($c1 - $c0);
  $N = (1 - $x)*(1 - $y)*$N1 + (1 - $x)*$y*$N3 + $x*(1 - $y)*$N2 + $x*$y*$N4;

  return $N;
}
?>

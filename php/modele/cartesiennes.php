<?php

/**
* Fonction cartesien_to_geographic
*
* Transforme des coordonnées cartésiennes en coordonnées géographiques associées à un ellipsoïde
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
* Transforme des coordonnées géographiques associées à un ellipsoïde en coordonnées cartésiennes
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
  $Z = ($N*(1-$e**2)+$h)*sin($phi);

  return array($X, $Y, $Z);
}

/**
* Fonction RGF_to_NTF
*
* Transforme des coordonnées cartésiennes RGF en coordonnées cartésiennes NTF à l'aide d'une grille de transformation
* Les coordonnées sont entrées en mètres et sont données en mètres
*
* @param float $X première coordonnées cartésiennes
* @param float $Y deuxième coordonnées cartésiennes
* @param float $Z troisième coordonnées cartésiennes
* @return array avec les trois coordonnées cartésiennes
*/
function RGF_to_NTF($X, $Y, $Z) {
  $ellipse = new Ellipse("IAG_GRS_1980");
  $geog = cartesien_to_geographic($X, $Y, $Z, $ellipse);

  if ($geog[0]<-0.0959931 || $geog[0]>0.1745329 || $geog[1]<0.7155850 || $geog[1]>0.9075712) { //test de Localisation
    echo "Error 120: Localisation hors de l'emprise de la grille de transformation RGF93/NTF";
    exit;
  } else {
    $translation = lecture_grille($geog);
    return array($X - $translation[0], $Y - $translation[1], $Z - $translation[2]);
  }
}


/**
* Fonction NTF_to_RGF
*
* Transforme des coordonnées cartésiennes NTF en coordonnées cartésiennes RGF à l'aide d'une grille de transformation
* Les coordonnées sont entrées en mètres et sont données en mètres

* @param float $X première coordonnées cartésiennes
* @param float $Y deuxième coordonnées cartésiennes
* @param float $Z troisième coordonnées cartésiennes
* @return array avec les trois coordonnées cartésiennes
*/
function NTF_to_RGF($X, $Y, $Z)  {
  $T0 = array(-168, -60, 320);

  $ellipse = new Ellipse("IAG_GRS_1980");
  $geog = cartesien_to_geographic($X + $T0[0], $Y + $T0[1], $Z + $T0[2], $ellipse);

  if ($geog[0]<-0.0959931 || $geog[0]>0.1745329 || $geog[1]<0.7155850 || $geog[1]>0.9075712) { //test de Localisation
    echo "Error 120: Localisation hors de l'emprise de la grille de transformation RGF93/NTF";
    exit;
  } else {
    $translation = lecture_grille($geog);
    return array($X + $translation[0], $Y + $translation[1], $Z + $translation[2]);
  }
}

/**
* Fonction lecture_grille

* Lit le fichier qui donne les paramètres de translation de la grille pour convertir du RGF vers NTF
* S'interesse en particulier à la maille de la grille correspondant aux coordonnées géographiques recu
* Renvoie le vecteur translation à appliquer en mètre
*
* @param array $geog coordonnées géographiques dans un array en radians
* @return array avec les trois coordonnées cartésiennes de la translation Ã  appliquer
*/
function lecture_grille($geog) {
  //passage du radian au degré
  foreach ($geog as &$elem) {
    $elem = $elem/pi()*180;
  }

//détermination de la maille
  $lambda0 = floor($geog[0]*10)/10;
  $phi0 = floor($geog[1]*10)/10;
  $lambda1 = ceil($geog[0]*10)/10;
  $phi1 = ceil($geog[1]*10)/10;

  if ($lambda0 == $lambda1){
    $lambda1 += 0.1;
  }
  if ($phi0 == $phi1) {
    $phi1 += 0.1;
  }

  //lecture du contenu de la grille
  $grille = lecture_fichier("../../files/gr3df97a.txt");
  //formatage pour lecture de la grille
  if ($lambda0<-1) {
    if ($lambda0 == floor($lambda0)) {
      $lambda0str = strval($lambda0).".000000000";
    } else {
      $lambda0str = strval($lambda0)."00000000";
    }
  } else if ($lambda0<0 && $lambda0>-1) {
    $lambda0str = "-".substr(strval($lambda0), 2)."00000000";
  } else if ($lambda0==0) {
    $lambda0str = "0.000000000";
  } else if ($lambda0<1 && $lambda0>0){
    $lambda0str = " ".substr(strval($lambda0), 1)."00000000";
  } else {
    if ($lambda0 == floor($lambda0)) {
      $lambda0str = " ".strval($lambda0).".000000000";
    } else {
      $lambda0str = " ".strval($lambda0)."00000000";
    }
  }

  if ($lambda1<-1) {
    if ($lambda1 == floor($lambda1)) {
      $lambda1str = strval($lambda1).".000000000";
    } else {
      $lambda1str = strval($lambda1)."00000000";
    }
  } else if ($lambda1<0 && $lambda1>-1) {
    $lambda1str = "-".substr(strval($lambda1), 2)."00000000";
  } else if ($lambda1==0) {
    $lambda1str = "0.000000000";
  } else if ($lambda1<1 && $lambda1>0){
    $lambda1str = " ".substr(strval($lambda1), 1)."00000000";
  } else {
    if ($lambda1 == floor($lambda1)) {
      $lambda1str = " ".strval($lambda1).".000000000";
    } else {
      $lambda1str = " ".strval($lambda1)."00000000";
    }
  }

  $phi0str = strval($phi0);
  $phi1str = strval($phi1);


  //lecture des informations nous interessant dans la grille
  $debutligne0 = substr($grille, strpos($grille, $lambda0str . "   " . $phi0str));
  $ligne0 = substr($debutligne0, 0, strpos($debutligne0, "\n"));
  $tab0 = explode("  ", $ligne0);

  $debutligne1 = substr($grille, strpos($grille, $lambda0str . "   " . $phi1str));
  $ligne1 = substr($debutligne1, 0, strpos($debutligne1, "\n"));
  $tab1 = explode("  ", $ligne1);

  $debutligne2 = substr($grille, strpos($grille, $lambda1str . "   " . $phi0str));
  $ligne2 = substr($debutligne2, 0, strpos($debutligne2, "\n"));
  $tab2 = explode("  ", $ligne2);

  $debutligne3 = substr($grille, strpos($grille, $lambda1str . "   " . $phi1str));
  $ligne3 = substr($debutligne3, 0, strpos($debutligne3, "\n"));
  $tab3 = explode("  ", $ligne3);

  //calcul du vecteur de translation par interpolation bilinéaire
  $x = ($geog[0] - $lambda0)/0.1;
  $y = ($geog[1] - $phi0)/0.1;

  $Tx = (1 - $x)*(1 - $y)*$tab0[2] + (1 - $x)*$y*$tab1[2] + $x*(1 - $y)*$tab2[2] + $x*$y*$tab3[2];
  $Ty = (1 - $x)*(1 - $y)*$tab0[3] + (1 - $x)*$y*$tab1[3] + $x*(1 - $y)*$tab2[3] + $x*$y*$tab3[3];
  $Tz = (1 - $x)*(1 - $y)*$tab0[4] + (1 - $x)*$y*$tab1[4] + $x*(1 - $y)*$tab2[4] + $x*$y*$tab3[4];

  return array($Tx, $Ty, $Tz);
}
 ?>

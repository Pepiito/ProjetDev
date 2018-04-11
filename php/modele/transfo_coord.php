<?php if (!isset($_SESSION)) session_start(); ?>

<?php
include('fonctions_fr.php');
include('Deviation_verticale.php');
include('Transo_Suisse_GRS80_MN95.php');
include('Transo_Suisse_MN95_GRS80.php');
?>

<?php
/*
Le plan global est de recupérer les variables d'un certain type de coordonnées
et de les transformer dans le type demandé.
On doit donc prévoir tous les cas de figure.
*/

// Récupère les variables AJAX dans la variable $_POST au format convenu
// Le recapitulatif des appelations est dans le document /docs/formatage_transfert_donnees.docx
$type_coord_dep = $_POST['t'];
$type_plani_dep = $_POST['P'];
$type_alti_dep = $_POST['T'];

$type_coord_arr = $_POST['_t'];
$type_plani_arr = $_POST['_P'];
$type_alti_arr = $_POST['_T'];
if ($type_coord_arr == 'proj') {
  $type_proj_arr = $_POST['_p'];
}
if ($type_alti_arr == 'a') {
  $sys_alti_arr = $_POST['_A'];
}

// cas où les coordonnées sont planimétriques
if ($type_coord_dep == 'proj') {
  $type_proj_dep = $_POST['p'];


  $E = explode(';', $_POST['E']);
  $N = explode(';', $_POST['N']);

  // on récupère l'information altitude ou hauteur
  if ($type_alti_dep == 'a') {
    $H = explode(';', $_POST['H']);
    $sys_alti_dep = $_POST['A'];
  } else if ($type_alti_dep == 'h') {
    $h = explode(';', $_POST['h']);
  }
  $len = count($E);

// on récupère l'éllipsoïde et le cone
// de projection correspondant pour les projections françaises
  if ($type_plani_dep == 'NTF') {
    $ellipse = new Ellipse('Clarke_1880');
    $cone = new Cone_Lambert($type_proj_dep);
  } else if ($type_plani_dep == 'RGF93') {
    $ellipse = new Ellipse('IAG_GRS_1980');
    $cone = new Cone_CC($type_proj_dep);
  } else if ($type_plani_dep == 'ETRS89') {
    $ellipse = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_dep == 'CH1903') {
    $ellipse = new Ellipse('Bessel_1841');
  } else if ($type_plani_dep == 'CH1903+') {
    $ellipse = new Ellipse('Bessel_1841');
  }

//cas où les coordonnées sont géographiques
} else if ($type_coord_dep == 'geog') {
  $lambda = explode(';', $_POST['l']);
  $phi = explode(';', $_POST['f']);

  // on récupère les informations d'altitude ou de hauteur
  if ($type_alti_dep == 'a') {
    $H = explode(';', $_POST['H']);
    $sys_alti_dep = $_POST['A'];
  } else if ($type_alti_dep == 'h') {
    $h = explode(';', $_POST['h']);
  }
  $len = count($lambda);

// de plus on récupère aussi l'éllipsoïde correspondant au système
  if ($type_plani_dep == 'RGF') {
    $ellipse = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_dep == 'NTF') {
    $ellipse = new Ellipse('Clarke_1880');
  } else if ($type_plani_dep == 'ETRS89') {
    $ellipse = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_dep == 'CH1903') {
    $ellipse = new Ellipse('Bessel_1841');
  } else if ($type_plani_dep == 'CH1903+') {
    $ellipse = new Ellipse('Bessel_1841');
  }

//dernier cas, coordonnées carthésiens
} else if ($type_coord_dep == 'cart') {
  $X = explode(';', $_POST['X']);
  $Y = explode(';', $_POST['Y']);
  $Z = explode(';', $_POST['Z']);
  $len = count($X);
}

// on récupère les informations d'éllipsoïde et de projection si il le faut
// pour savoir comment on doit transformer les données
if ($type_coord_arr == 'proj') {
    if ($type_plani_arr == 'NTF') {
    $ellipse_arr = new Ellipse('Clarke_1880');
    $cone = new Cone_Lambert($type_proj_arr);
  }  else if ($type_plani_arr == 'RGF93') {
    $ellipse_arr = new Ellipse('IAG_GRS_1980');
    $cone = new Cone_CC($type_proj_arr);
  } else if ($type_plani_arr == 'ETRS89') {
    $ellipse_arr = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_arr == 'CH1903') {
    $ellipse_arr = new Ellipse('Bessel_1841');
  } else if ($type_plani_arr == 'CH1903+') {
    $ellipse_arr = new Ellipse('Bessel_1841');
  }

} else if ($type_coord_arr == 'geog') {
  if ($type_plani_arr == 'RGF') {
    $ellipse_arr = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_arr == 'NTF') {
    $ellipse_arr = new Ellipse('Clarke_1880');
  } else if ($type_plani_arr == 'ETRS89') {
    $ellipse_arr = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_arr == 'CH1903') {
    $ellipse_arr = new Ellipse('Bessel_1841');
  } else if ($type_plani_arr == 'CH1903+') {
    $ellipse_arr = new Ellipse('Bessel_1841');
  }

}
?>

<?php
// on transforme toutes les coordonnées reçu
for ($i=0; $i<$len; $i++) {
  // les données prennent la forme X0, Y0, Z0
  if ($type_coord_dep == 'proj') {
    $E0 = $E[$i];
    $N0 = $N[$i];

    // passage vers les coordonnées géographiques
    if ($type_plani_dep == 'NTF') {
      $array_geog = Lambert_to_geog($E0, $N0, $cone);
      $lambda0 = $array_geog[0];
      $phi0 = $array_geog[1];

      // passage en hauteur si nécéssaire pour le système altimétrique IGN69
      if ($type_alti_dep == 'a' && $sys_alti_dep == 'IGN69') {
        $H0 = $H[$i];
        $cst = alti_to_h(48.846211, 2.346199, 0); //N croix du pantheon
        $h0 = alti_to_h($lambda0, $phi0, $H0) + $cst;
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[$i];
      }

    } else if ($type_plani_dep == 'RGF') {
      $array_geog = CC_to_geog($E0, $N0, $cone);
      $lambda0 = $array_geog[0];
      $phi0 = $array_geog[1];

      // passage en hauteur si nécéssaire pour le système altimétrique IGN69
      if ($type_alti_dep == 'a' && $sys_alti_dep == 'IGN69') {
        $H0 = $H[$i];
        $h0 = alti_to_h($lambda0, $phi0, $H0);
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[$i];
      }

    } else if ($type_plani_dep == 'MN95') {
      MN95_to_geog($E0, $N0, )
    } else if ($type_plani_dep == 'MN03') {
// a remplir, passer de E, N à lambda, phi et changer l'alti en hauteur si il faut
    }

    // passage des coordonnées géographiques vers les coordonnées cartésiennes
    $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);

    // passage vers le système de coordonnées cartésiens ETRS89
    if ($type_plani_dep == 'CH1903+' || $type_plani_dep == 'CH1903') {
      $array_cart = carthesienne_CH1903plus_to_carthesienne_ETRS89($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_dep == 'NTF') {
      $array_cart = NTF_to_RGF($array_cart[0], $array_cart[1], $array_cart[2]);
    }

    $X0 = $array_cart[0];
    $Y0 = $array_cart[1];
    $Z0 = $array_cart[2];

  } else if ($type_coord_dep == 'geog') {
    $lambda0 = $lambda[$i];
    $phi0 = $phi[$i];

    if ($type_plani_dep == 'RGF') {
      // passage en hauteur si nécéssaire pour le système altimétrique IGN69
      if ($type_alti_dep == 'a' && $sys_alti_dep == 'IGN69') {
        $H0 = $H[$i];
        $h0 = alti_to_h($lambda0, $phi0, $H0);
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[$i];
      }

    } else if ($type_plani_dep == 'NTF') {
      // passage en hauteur si nécéssaire pour le système altimétrique IGN69
      if ($type_alti_dep == 'a' && $sys_alti_dep == 'IGN69') {
        $H0 = $H[$i];
        $cst = alti_to_h(48.846211, 2.346199, 0); //N croix du pantheon
        $h0 = alti_to_h($lambda0, $phi0, $H0) + $cst;
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[$i];
      }
    } else if ($type_plani_dep == 'CH1903') {
// a remplir, passer de E, N à lambda, phi et changer l'alti en hauteur si il faut
    } else if ($type_plani_dep == 'CH1903+') {
// a remplir, passer de E, N à lambda, phi et changer l'alti en hauteur si il faut
    }

    // passage des coordonnées géographiques vers les coordonnées cartésiennes
    $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);

    // passage vers le système de coordonnées cartésiennes ETRS89
    if ($type_plani_dep == 'CH1903+' || $type_plani_dep == 'CH1903') {
      $array_cart = carthesienne_CH1903plus_to_carthesienne_ETRS89($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_dep == 'NTF') {
      $array_cart = NTF_to_RGF($array_cart[0], $array_cart[1], $array_cart[2]);
    }

    $X0 = $array_cart[0];
    $Y0 = $array_cart[1];
    $Z0 = $array_cart[2];

  } else if ($type_coord_dep == 'cart') {
    $array_cart = array($X[$i], $Y[$i], $Z[$i]);

    // passage vers le système de coordonnées cartésiennes ETRS89
    if ($type_plani_dep == 'CH1903+' || $type_plani_dep == 'CH1903') {
      $array_cart = carthesienne_CH1903plus_to_carthesienne_ETRS89($X0, $Y0, $Z0, $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_dep == 'NTF') {
      $array_cart = NTF_to_RGF($X0, $Y0, $Z0);
    }

    $X0 = $array_cart[0];
    $Y0 = $array_cart[1];
    $Z0 = $array_cart[2];
  }

  // on stocke les résultats dans une variable tampons
  $X_tmp[$i] = $X0;
  $Y_tmp[$i] = $Y0;
  $Z_tmp[$i] = $Z0;
}

// on passe les variables tampons dans le système voulu
for ($i=0; $i<$len; $i++) {
  if ($type_coord_arr == 'cart') {
    // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
    if ($type_plani_arr == 'CH1903+' || $type_plani_arr == 'CH1903') {
      $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_arr[$i], $Y_arr[$i], $Z_arr[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_arr == 'NTF') {
      $array_cart = RGF_to_NTF($X_arr[$i], $Y_arr[$i], $Z_arr[$i]);
    } else {
      $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
    }

    // arrays de sortie
    $X_arr['X'.$i] = $array_cart[0];
    $Y_arr['Y'.$i] = $array_cart[1];
    $Z_arr['Z'.$i] = $array_cart[2];

  } else if ($type_coord_arr == 'geog') {
    // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
    if ($type_plani_arr == 'CH1903+' || $type_plani_arr == 'CH1903') {
      $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_arr == 'NTF') {
      $array_cart = RGF_to_NTF($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
    } else {
      $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
    }

    // passage vers les coordonnées géographiques
    $array_geog = cartesien_to_geographic($array_cart[0], $array_cart[1], $array_cart[2], $ellipse_arr);

    // arrays de sortie
    $lambda_arr['lambda'.$i] = $array_geog[0];
    $phi_arr['phi'.$i] = $array_geog[1];

    if ($type_alti_arr == 'h') {
      $h_arr['h'.$i] = $array_geog[2];

    } else if ($type_alti_arr == 'a') {
      if ($type_plani_arr == 'RGF') {
        $H_arr['H'.$i] = h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]);
      } else if ($type_plani_arr == 'NTF') {
        $cst = alti_to_h(48.846211, 2.346199, 0);
        $H_arr['H'.$i] = h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]) - $cst;
      } else if ($type_plani_arr == 'CH1903') {
        // a remplir pour passer dans les systèmes altimétriques suisses
      } else if ($type_plani_arr == 'CH1903+') {
        // a remplir pour passer dans les systèmes altimétriques suisses
      }
    }

  } else if ($type_coord_arr == 'proj') {
    // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
    if ($type_plani_arr == 'CH1903+' || $type_plani_arr == 'CH1903') {
      $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
    } else if ($type_plani_arr == 'NTF') {
      $array_cart = RGF_to_NTF($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
    } else {
      $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
    }

    // passage vers les coordonnées géographiques
    $array_geog = cartesien_to_geographic($array_cart[0], $array_cart[1], $array_cart[2], $ellipse_arr);

    if ($type_alti_arr == 'h') {
      $h_arr['h'.$i] = $array_geog[2];

    } else if ($type_alti_arr == 'a' && $sys_alti_arr == 'IGN69') {
      if ($type_plani_arr == 'RGF') {
        $H_arr['H'.$i] = h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]);
      } else if ($type_plani_arr == 'NTF') {
        $cst = alti_to_h(48.846211, 2.346199, 0);
        $H_arr['H'.$i] = h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]) - $cst;
      } else if ($type_plani_arr == 'CH1903') {
        // a remplir pour passer dans les systèmes altimétriques suisses
      } else if ($type_plani_arr == 'CH1903+') {
        // a remplir pour passer dans les systèmes altimétriques suisses
      }
    }

    if ($type_plani_arr == 'NTF') {
      $array_plani = geog_to_Lambert($array_geog[0], $array_geog[1], $cone);
      $E_arr['E'.$i] = $array_plani[0];
      $N_arr['N'.$i] = $array_plani[1];
    } else if ($type_plani_arr == 'RGF93') {
      $array_plani = proj_to_CC($array_geog[0], $array_geog[1], $cone);
      $E_arr['E'.$i] = $array_plani[0];
      $N_arr['N'.$i] = $array_plani[1];
    } else if ($type_plani_arr == 'CH1903') {
// a remplir, passer de array_geog (lambda, phi) aux coordonnées projetées
    } else if ($type_plani_arr == 'CH1903+') {
// a remplir, passer de array_geog (lambda, phi) aux coordonnées projetées
    }

  }
}

if ($type_coord_arr == 'cart') {
  $echo['cart'] = array('X' => $X_arr, 'Y' => $Y_arr, 'Z' => $Z_arr);
} else if ($type_coord_arr == 'geog') {
  if ($type_alti_arr == 'h') {
    $echo['geog'] = array('lambda' => $lambda_arr, 'phi' => $phi_arr, 'h' => $h_arr);
  } else if ($type_alti_arr == 'a') {
    $echo['geog'] = array('lambda' => $lambda_arr, 'phi' => $phi_arr, 'H' => $H_arr);
  }
} else if ($type_coord_arr == 'proj') {
  if ($type_alti_arr == 'h') {
    $echo['proj'] = array('E' => $E_arr, 'N' => $N_arr, 'h' => $h_arr);
  } else if ($type_alti_arr == 'a') {
    $echo['proj'] = array('E' => $E_arr, 'N' => $N_arr, 'H' => $H_arr);
  }
}
?>

<?php  /* Renvoie une chaine de caractère au format var1=foo&var2=bar.
       L'ajax récupèrera le contenu intégral de ce qui est renvoyé,
       au format "Error XXX: description" si erreur ou "var1=foo&var2=bar" si ok.

       Ce fichier sert de boite de réception / d'envoi de la couche modèle : ne pas le renommer.
       Le contenu pourra être contenu dans des fichiers externes reliés par un include().
       */
echo json_encode($echo);
?>

<?php
function traitement_vers_milieu($POST) {
  include('variables_suisses.php');
  $association_ellipse = array("NTF" => "Clarke_1880", "RGF93" => "IAG_GRS_1980", "ETRS89" => "IAG_GRS_1980", "CH1903" => "Bessel_1841", "CH1903plus" => "Bessel_1841"); //en raison de l'encodage, le + devient " "

  // Récupère les variables AJAX dans la variable $POST au format convenu
  // Le recapitulatif des appelations est dans le document /docs/formatage_transfert_donnees.docx
  $type_coord_dep = $POST['t'];
  $type_plani_dep = $POST['P'];
  if (!($type_coord_dep == 'cart')) {
    $type_alti_dep = $POST['T'];
  }

  // cas où les coordonnées sont planimétriques
  if ($type_coord_dep == 'proj') {
    $type_proj_dep = $POST['p'];


    $E = explode(';', $POST['E']);
    $N = explode(';', $POST['N']);

    // on récupère l'information altitude ou hauteur
    if ($type_alti_dep == 'a') {
      $H = explode(';', $POST['H']);
      $sys_alti_dep = $POST['A'];
    } else if ($type_alti_dep == 'h') {
      $h = explode(';', $POST['h']);
    }
    $len = count($E);

  // on récupère l'éllipsoïde et le cone
  // de projection correspondant pour les projections françaises
    $ellipse = new Ellipse($association_ellipse[$type_plani_dep]);
    if ($type_plani_dep == 'NTF') {
      $cone = new Cone_Lambert($type_proj_dep);
    } else if ($type_plani_dep == 'RGF93') {
      $cone = new Cone_CC($type_proj_dep);
    }

  //cas où les coordonnées sont géographiques
  } else if ($type_coord_dep == 'geog') {
    $lambda = explode(';', $POST['l']);
    $phi = explode(';', $POST['f']);

    // on récupère les informations d'altitude ou de hauteur
    if ($type_alti_dep == 'a') {
      $H = explode(';', $POST['H']);
      $sys_alti_dep = $POST['A'];
    } else if ($type_alti_dep == 'h') {
      $h = explode(';', $POST['h']);
    }
    $len = count($lambda);

  // de plus on récupère aussi l'éllipsoïde correspondant au système
    $ellipse = new Ellipse($association_ellipse[$type_plani_dep]);

  //dernier cas, coordonnées carthésiens
  } else if ($type_coord_dep == 'cart') {
    $X = explode(';', $POST['X']);
    $Y = explode(';', $POST['Y']);
    $Z = explode(';', $POST['Z']);
    $len = count($X);
  }


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
          $cst = alti_to_h(48.846211*pi()/180, 2.346199*pi()/180, 0); //N croix du pantheon
          $h0 = alti_to_h($lambda0, $phi0, $H0) - $cst;
        } else if ($type_alti_dep == 'h') {
          $h0 = $h[$i];
        }

      } else if ($type_plani_dep == 'RGF93') {
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

      } else if ($type_plani_dep == 'CH1903plus' && $type_proj_dep == 'MN95') {
        list($phi0, $lambda0) = MN95_to_geog($E0, $N0, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
        if ($type_alti_dep == 'a' && $sys_alti_dep == 'RAN95') {
          $H0 = $H[$i];

          list($E_MN03, $N_MN03) = MN95_to_MN03($E0, $N0);
          $h0 = RAN95_to_Bessel($E_MN03, $N_MN03, $H0);
        } else if ($type_alti_dep == 'a' && $sys_alti_dep == 'NF02'){
          $H0 = $H[$i];

          list($E_MN03, $N_MN03) = MN95_to_MN03($E0, $N0);
          $h0 = NF02_to_Bessel($E_MN03, $N_MN03, $H0);
        } else if ($type_alti_dep == 'h') {
          $h0 = $h[$i];
        }
      } else if ($type_plani_dep == 'CH1903' && $type_proj_dep == 'MN03') {
        list($E_MN95, $N_MN95) = MN03_to_MN95($E0, $N0);
        list($phi0, $lambda0) = MN95_to_geog($E_MN95, $N_MN95, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);

        if ($type_alti_dep == 'a' && $sys_alti_dep == 'RAN95') {
          $H0 = $H[$i];
          $h0 = RAN95_to_Bessel($E0, $N0, $H0);
        } else if ($type_alti_dep == 'a' && $sys_alti_dep == 'NF02'){
          $H0 = $H[$i];
          $h0 = NF02_to_Bessel($E0, $N0, $H0);
        } else if ($type_alti_dep == 'h') {
          $h0 = $h[$i];
        }
      }

      // passage des coordonnées géographiques vers les coordonnées cartésiennes
      $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);

      // passage vers le système de coordonnées cartésiens ETRS89
      if ($type_plani_dep == 'CH1903' || $type_plani_dep == 'CH1903plus') {
        $array_cart = cartCH1903plus_to_cartETRS89($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_dx,$Bessel_dy,$Bessel_dz);
      } else if ($type_plani_dep == 'NTF') {
        $array_cart = NTF_to_RGF($array_cart[0], $array_cart[1], $array_cart[2]);
      }

      $X0 = $array_cart[0];
      $Y0 = $array_cart[1];
      $Z0 = $array_cart[2];

    } else if ($type_coord_dep == 'geog') {
      $lambda0 = $lambda[$i];
      $phi0 = $phi[$i];

      if ($type_plani_dep == 'RGF93') {
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
          $cst = alti_to_h(48.846211*pi()/180, 2.346199*pi()/180, 0); //N croix du pantheon
          $h0 = alti_to_h($lambda0, $phi0, $H0) - $cst;
        } else if ($type_alti_dep == 'h') {
          $h0 = $h[$i];
        }
      } else if ($type_plani_dep == 'CH1903' || $type_plani_dep == 'CH1903plus') {
        if ($type_alti_dep == 'a' && $sys_alti_dep == 'RAN95') {
          $H0 = $H[$i];

          list($Y_LV95, $X_LV95) = geog_to_MN95($lambda0, $phi0, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
          list($E_MN03, $N_MN03) = MN95_to_MN03($Y_LV95, $X_LV95);

          $h0 = RAN95_to_Bessel($E_MN03, $N_MN03, $H0);
        } else if ($type_alti_dep == 'a' && $sys_alti_dep == 'NF02') {
          $H0 = $H[$i];

          list($Y_LV95, $X_LV95) = geog_to_MN95($lambda0, $phi0, $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
          list($E_MN03, $N_MN03) = MN95_to_MN03($Y_LV95, $X_LV95);

          $h0 = NF02_to_Bessel($E_MN03, $N_MN03, $H0);
        } else if ($type_alti_dep == 'h') {
          $h0 = $h[$i];
        }
      }

      // passage des coordonnées géographiques vers les coordonnées cartésiennes
      $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);

      // passage vers le système de coordonnées cartésiennes ETRS89
      if ($type_plani_dep == 'CH1903plus' || $type_plani_dep == 'CH1903') {
        $array_cart = cartCH1903plus_to_cartETRS89($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_dx,$Bessel_dy,$Bessel_dz);
      } else if ($type_plani_dep == 'NTF') {
        $array_cart = NTF_to_RGF($array_cart[0], $array_cart[1], $array_cart[2]);
      }

      $X0 = $array_cart[0];
      $Y0 = $array_cart[1];
      $Z0 = $array_cart[2];

    } else if ($type_coord_dep == 'cart') {
      $array_cart = array($X[$i], $Y[$i], $Z[$i]);

      // passage vers le système de coordonnées cartésiennes ETRS89
      if ($type_plani_dep == 'CH1903plus' || $type_plani_dep == 'CH1903') {
        $array_cart = cartCH1903plus_to_cartETRS89($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_dx,$Bessel_dy,$Bessel_dz);
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
  return array($X_tmp, $Y_tmp, $Z_tmp, $len);
}

function conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr) {
  include('variables_suisses.php');
  $association_ellipse = array("NTF" => "Clarke_1880", "RGF93" => "IAG_GRS_1980", "ETRS89" => "IAG_GRS_1980", "CH1903" => "Bessel_1841", "CH1903plus" => "Bessel_1841");
  $len = count($X_tmp);
  include('variables_suisses.php');

  // on récupère les informations d'éllipsoïde et de projection si il le faut
  // pour savoir comment on doit transformer les données
  if ($type_coord_arr == 'proj') {
    $ellipse_arr = new Ellipse($association_ellipse[$type_plani_arr]);
    if ($type_plani_arr == 'NTF') {
      $cone = new Cone_Lambert($type_proj_arr);
    }  else if ($type_plani_arr == 'RGF93') {
      $cone = new Cone_CC($type_proj_arr);
    }

  } else if ($type_coord_arr == 'geog') {
    $ellipse_arr = new Ellipse($association_ellipse[$type_plani_arr]);
  }

  // on passe les variables tampons dans le système voulu
  for ($i=0; $i<$len; $i++) {
    if ($type_coord_arr == 'cart') {
      // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
      if ($type_plani_arr == 'CH1903plus' || $type_plani_arr == 'CH1903') {
        $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
      } else if ($type_plani_arr == 'NTF') {
        $array_cart = RGF_to_NTF($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      } else {
        $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      }

      // arrays de sortie
      $X_arr['X'.$i] = round($array_cart[0], 4);
      $Y_arr['Y'.$i] = round($array_cart[1], 4);
      $Z_arr['Z'.$i] = round($array_cart[2], 4);

    } else if ($type_coord_arr == 'geog') {
      // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
      if ($type_plani_arr == 'CH1903plus' || $type_plani_arr == 'CH1903') {
        $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
      } else if ($type_plani_arr == 'NTF') {
        $array_cart = RGF_to_NTF($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      } else {
        $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      }

      // passage vers les coordonnées géographiques
      if ($type_plani_arr == 'CH1903plus' || $type_plani_arr == 'CH1903') {
        $array_geog = cart_CH1903plus_to_geog_CH1903plus($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_a, $Bessel_e, $Epsilon);
      } else {
        $array_geog = cartesien_to_geographic($array_cart[0], $array_cart[1], $array_cart[2], $ellipse_arr);
      }

      // arrays de sortie
      $lambda_arr['lambda'.$i] = $array_geog[0];
      $phi_arr['phi'.$i] = $array_geog[1];

      if ($type_alti_arr == 'h') {
        $h_arr['h'.$i] = round($array_geog[2], 4);

      } else if ($type_alti_arr == 'a') {
        if (($type_plani_arr == 'RGF93' || $type_plani_arr == 'ETRS89') && $sys_alti_arr == 'IGN69') {
          $H_arr['H'.$i] = round(h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]), 4);
        } else if ($type_plani_arr == 'NTF' && $sys_alti_arr == 'IGN69') {
          $cst = alti_to_h(2.346199*pi()/180, 48.846211*pi()/180, 0);
          $H_arr['H'.$i] = round(h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]) + $cst, 4);
        } else if (($type_plani_arr == 'CH1903' || $type_plani_arr == 'CH1903plus') && $sys_alti_arr == 'RAN95') {
          list($E_MN95, $N_MN95) = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
          list($E_MN03, $N_MN03) = MN95_to_MN03($E_MN95, $N_MN95);
          $H_arr['H'.$i] = round(Bessel_to_RAN95($E_MN03, $N_MN03, $array_geog[2]), 4);
        } else if (($type_plani_arr == 'CH1903' || $type_plani_arr == 'CH1903plus') && $sys_alti_arr == 'NF02') {
          list($E_MN95, $N_MN95) = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
          list($E_MN03, $N_MN03) = MN95_to_MN03($E_MN95, $N_MN95);
          $H_arr['H'.$i] = round(Bessel_to_NF02($E_MN03, $N_MN03, $array_geog[2]), 4);
        }
      }

    } else if ($type_coord_arr == 'proj') {
      // passage des coordonnées cartesiennes ETRS89 vers les systèmes cartésiens voulu
      if ($type_plani_arr == 'CH1903plus' || $type_plani_arr == 'CH1903') {
        $array_cart = carthesienne_ETRS89_to_carthesienne_CH1903plus($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i], $Bessel_dx, $Bessel_dy, $Bessel_dz);
      } else if ($type_plani_arr == 'NTF') {
        $array_cart = RGF_to_NTF($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      } else {
        $array_cart = array($X_tmp[$i], $Y_tmp[$i], $Z_tmp[$i]);
      }

      // passage vers les coordonnées géographiques
      if ($type_plani_arr == 'CH1903plus' || $type_plani_arr == 'CH1903') {
        $array_geog = cart_CH1903plus_to_geog_CH1903plus($array_cart[0], $array_cart[1], $array_cart[2], $Bessel_a, $Bessel_e, $Epsilon);
      } else {
        $array_geog = cartesien_to_geographic($array_cart[0], $array_cart[1], $array_cart[2], $ellipse_arr);
      }

      if ($type_alti_arr == 'h') {
        $h_arr['h'.$i] = round($array_geog[2], 4);

      } else if ($type_alti_arr == 'a' && $sys_alti_arr == 'IGN69') {
        if ($type_plani_arr == 'RGF93') {
          $H_arr['H'.$i] = round(h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]), 4);
        } else if ($type_plani_arr == 'NTF') {
          $cst = alti_to_h(2.346199*pi()/180, 48.846211*pi()/180, 0);
          $H_arr['H'.$i] = round(h_to_alti($array_geog[0], $array_geog[1], $array_geog[2]) + $cst, 4);
        }
      } else if ($type_alti_arr == 'a' && ($type_plani_arr == 'CH1903' || $type_plani_arr == 'CH1903plus') && $sys_alti_arr == 'RAN95') {
        list($E_MN95, $N_MN95) = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
        list($E_MN03, $N_MN03) = MN95_to_MN03($E_MN95, $N_MN95);
        $H_arr['H'.$i] = round(Bessel_to_RAN95($E_MN03, $N_MN03, $array_geog[2]), 4);
      } else if ($type_alti_arr == 'a' && ($type_plani_arr == 'CH1903' || $type_plani_arr == 'CH1903plus') && $sys_alti_arr == 'NF02') {
        list($E_MN95, $N_MN95) = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
        list($E_MN03, $N_MN03) = MN95_to_MN03($E_MN95, $N_MN95);
        $H_arr['H'.$i] = round(Bessel_to_NF02($E_MN03, $N_MN03, $array_geog[2]), 4);
      }

      if ($type_plani_arr == 'NTF') {
        $array_plani = geog_to_Lambert($array_geog[0], $array_geog[1], $cone);
      } else if ($type_plani_arr == 'RGF93') {
        $array_plani = proj_to_CC($array_geog[0], $array_geog[1], $cone);
      } else if ($type_plani_arr == 'CH1903' && $type_proj_arr == 'MN03') {
        $array_plani = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
        $array_plani = MN95_to_MN03($array_plani[0], $array_plani[1]);
      } else if ($type_plani_arr == 'CH1903plus' && $type_proj_arr == 'MN95') {
        $array_plani = geog_to_MN95($array_geog[0], $array_geog[1], $phi_Berne, $Bessel_e, $Bessel_a, $lambda_Berne);
      }
      $E_arr['E'.$i] = round($array_plani[0], 4);
      $N_arr['N'.$i] = round($array_plani[1], 4);

    }
  }

  if ($type_coord_arr == 'cart') {
    $echo = array('X' => $X_arr, 'Y' => $Y_arr, 'Z' => $Z_arr);

  } else if ($type_coord_arr == 'geog') {
      if ($type_alti_arr == 'h') {
      $echo = array('lambda' => $lambda_arr, 'phi' => $phi_arr, 'h' => $h_arr);
    } else if ($type_alti_arr == 'a') {
      $echo = array('lambda' => $lambda_arr, 'phi' => $phi_arr, 'H' => $H_arr);
    }
  } else if ($type_coord_arr == 'proj') {
    if ($type_alti_arr == 'h') {
      $echo = array('E' => $E_arr, 'N' => $N_arr, 'h' => $h_arr);
    } else if ($type_alti_arr == 'a') {
      $echo = array('E' => $E_arr, 'N' => $N_arr, 'H' => $H_arr);
    }
  }

  return $echo;
}
?>

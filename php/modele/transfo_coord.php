<?php if (!isset($_SESSION)) session_start(); ?>

<?php
include('cartesiennes.php');
include('fonctions_fr.php');
include('Deviation_verticale.php');
include('Transfo_geogr_ellips_CH1903+_to_CH1903+.php');
include('Transfo_geogr_to_geogr_ellips_CH1903+.php');
?>

<?php // Récupère les variables AJAX dans la variable $_POST au format convenu
$type_coord_dep = $_POST['t'];
$type_plani_dep = $_POST['P']
$type_alti_dep = $_POSt['T'];

$type_coord_arr = $_POST['_t'];
$type_plani_arr = $_POST['_P']
$type_alti_arr = $_POSt['_T'];
if ($type_coord_arr = 'proj') {
  $type_proj_arr = $_POST['_p'];
}

$nom = explode(';', $_POST['n']);

if ($type_coord_dep = 'proj') {
  $type_proj_dep = $_POST['p'];

  $E = explode(';', $_POST['E']);
  $N = explode(';', $_POST['N']);
  if ($type_alti_dep = 'a') {
    $H = explode(';', $_POST['H']);
  } else if ($type_alti_dep = 'h') {
    $h = explode(';', $_POST['h']);
  }
  $len = count($E);

  if ($type_plani_dep == 'NTF') {
    $ellipse = new Ellipse('Clarke_1880');
    $cone = new Cone_Lambert($type_proj_dep);
  }  else if ($type_plani_dep == 'RGF')
    $ellipse = new Ellipse('IAG_GRS_1980');
    $cone = new Cone_CC($type_proj_dep);
  }

} else if ($type_coord_dep = 'geog') {
  $lambda = explode(';', $_POST['l']);
  $phi = explode(';', $_POST['f']);
  if ($type_alti_dep = 'a') {
    $H = explode(';', $_POST['H']);
    $sys_alti = $_POST['A'];
  } else if ($type_alti_dep = 'h') {
    $h = explode(';', $_POST['h']);
  }
  $len = count($lambda);

  if ($type_plani_dep == 'RGF') {
    $ellipse = new Ellipse('IAG_GRS_1980');
  } else if ($type_plani_dep == 'NTF') {
    $ellipse = new Ellipse('Clarke_1880');
  }

} else if ($type_coord_dep == 'cart') {
  $X = explode(';', $_POST['X']);
  $Y = explode(';', $_POST['Y']);
  $Z = explode(';', $_POST['Z']);
  $len = count($X);
}
?>

<?php
for ($i=0; $i<$len; $i++) {
  if ($type_coord_dep == 'proj') {
    $E0 = $E[i];
    $N0 = $N[i];

    if ($type_plani_dep == 'NTF') {
      $array_geog = Lambert_to_geog($E0, $N0, $cone);
      $lambda0 = $array_geog[0];
      $phi0 = $array_geog[1];

      if ($type_alti_dep == 'a' && $sys_alti == 'IGN69') {
        $H0 = $H[i];
        $cst = alti_to_h(48.846211, 2.346199, 0); //N croix du pantheon
        $h0 = alti_to_h($lambda0, $phi0, $H0) + $cst;
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[i];
      }

    } else if ($type_plani_dep == 'RGF')
      $array_geog = CC_to_geog($E0, $N0, $cone);
      $lambda0 = $array_geog[0];
      $phi0 = $array_geog[1];

      if ($type_alti_dep == 'a' && $sys_alti == 'IGN69') {
        $H0 = $H[i];
        $h0 = alti_to_h($lambda0, $phi0, $H0);
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[i];
      }
    }

    $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);
    $X0 = $array_cart[0];
    $Y0 = $array_cart[1];
    $Z0 = $array_cart[2];

  } else if ($type_coord_dep == 'geog') {
    $lambda0 = $lambda[i];
    $phi0 = $phi[i];

    if ($type_plani_dep == 'RGF') {
      if ($type_alti_dep == 'a' && $sys_alti == 'IGN69') {
        $H0 = $H[i];
        $h0 = alti_to_h($lambda0, $phi0, $H0);
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[i];
      }

    } else if ($type_plani_dep == 'NTF') {
      if ($type_alti_dep == 'a' && $sys_alti == 'IGN69') {
        $H0 = $H[i];
        $cst = alti_to_h(48.846211, 2.346199, 0); //N croix du pantheon
        $h0 = alti_to_h($lambda0, $phi0, $H0) + $cst;
      } else if ($type_alti_dep == 'h') {
        $h0 = $h[i];
      }
    }

    $array_cart = geographic_to_cartesien($lambda0, $phi0, $h0, $ellipse);
    $X0 = $array_cart[0];
    $Y0 = $array_cart[1];
    $Z0 = $array_cart[2];

  } else if ($type_coord_dep == 'cart') {
    $X0 = $X[i];
    $Y0 = $Y[i];
    $Z0 = $Z[i];
  }
}
?>

<?php  /* Renvoie une chaine de caractère au format var1=foo&var2=bar.
       L'ajax récupèrera le contenu intégral de ce qui est renvoyé,
       au format "Error XXX: description" si erreur ou "var1=foo&var2=bar" si ok.

       Ce fichier sert de boite de réception / d'envoi de la couche modèle : ne pas le renommer.
       Le contenu pourra être contenu dans des fichiers externes reliés par un include().
       */
       ?>

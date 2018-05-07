<?php if (!isset($_SESSION)) session_start();

/* Renvoie une chaine de caractère au format json.
     L'ajax récupèrera le contenu intégral de ce qui est renvoyé,
     au format "Error XXX: description" si erreur ou un json si ok.

     Ce fichier sert de boite de réception / d'envoi de la couche modèle : ne pas le renommer.
     Le contenu pourra être contenu dans des fichiers externes reliés par un include().
     */

include('gestion_donnee.php');
include('cartesiennes.php');
include('fonctions_fr.php');
include('alti_fr.php');
include('variables.php');
include('lecture_fichier.php');

include('variables_suisses.php');
include('fonctions_suisses.php');
include('Deviation_verticale.php');
include('Transo_Suisse_GRS80_MN95.php');
include('Transo_Suisse_MN95_GRS80.php');
include('Transo_Suisse_API_DLL.php');



/*
L'idée' est de recupérer les variables d'un certain type de coordonnées
et de les transformer dans tous les types.
On doit donc prévoir tous les cas de figure.
*/

if (isset($_POST['addMap'])) {
  $addmap = is_true($_POST['addMap']);
  if (is_true($_POST['addMap'])) {
    include("../vue/write_to_postgis.php");
  }
} else {
  $addmap = FALSE;
}

if (isset ($_POST['n'])) {
  $nom = explode(';', $_POST['n']);
} else {
  $nom = array();
  for ($i=0; $i<$len;$i++) {
    $nom['n'.$i] = 'point'.str_pad($i.'', 4, '0', STR_PAD_LEFT);
  }
}

list($X_tmp, $Y_tmp, $Z_tmp) = traitement_vers_milieu($_POST);

$echo = array('n' => $nom);

if ($addmap) {
  // Je fais des arrays pour tous les cas possibles
  $coord = array('cart', 'geog', 'proj');
  $plani = array('RGF93', 'NTF', 'ETRS89', 'CH1903+');
  $alti = array('a', 'h');
  $sys_alti = array('RGF93' => array('IGN69'), 'NTF' => array('IGN69'), 'ETRS89' => array(), 'CH1903+' => array('RAN95', 'NF02'), 'CH1903' => array('RAN95', 'NF02'));
  $proj = array('RGF93' => array('CC42', 'CC43', 'CC44', 'CC45', 'CC46', 'CC47', 'CC48', 'CC49', 'CC50', 'Lambert93'), 'NTF' => array('Lambert1', 'Lambert2', 'Lambert2etendu', 'Lambert3', 'Lambert4'), 'CH1903' => array('MN03', 'MN95'), 'CH1903+' => array('MN95', 'MN03'), 'ETRS89' => array());


  foreach($coord as $cas_coord) {
    foreach($plani as $cas_plani) {
      if (!($cas_coord == 'cart')) {
        foreach($alti as $cas_alti)  {
          if ($cas_alti == 'a') {
            foreach ($sys_alti[$cas_plani] as $cas_sys) {
              if ($cas_coord == 'proj') {
                foreach ($proj[$cas_plani] as $cas_proj) {
                  $echo[$cas_coord][$cas_plani][$cas_proj][$cas_alti][$cas_sys] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $cas_coord, $cas_plani, $cas_alti, $cas_proj, $cas_sys);
                }
              } else {
                $echo[$cas_coord][$cas_plani][$cas_alti][$cas_sys] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $cas_coord, $cas_plani, $cas_alti, 0, $cas_sys);
              }
            }
          } else {
            if ($cas_coord == 'proj') {
              foreach ($proj[$cas_plani] as $cas_proj) {
                $echo[$cas_coord][$cas_plani][$cas_proj][$cas_alti] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $cas_coord, $cas_plani, $cas_alti, $cas_proj, 0);
              }
            } else {
              $echo[$cas_coord][$cas_plani][$cas_alti] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $cas_coord, $cas_plani, $cas_alti, 0, 0);
            }
          }
        }
      } else {
          $echo[$cas_coord][$cas_plani] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $cas_coord, $cas_plani, 0, 0, 0);
      }
    }
  }
} else {
  $type_coord_arr = $_POST['_t'];
  $type_plani_arr = $_POST['_P'];
  if (!($type_coord_arr == 'cart')) {
    $type_alti_arr = $_POST['_T'];
    if ($type_alti_arr == 'a') {
      $sys_alti_arr = $_POST['_A'];
    } else {
      $sys_alti_arr = 0;
    }
  } else {
    $type_alti_arr = 0;
  }
  if ($type_coord_arr == 'proj') {
    $type_proj_arr = $_POST['_p'];
  } else {
    $type_proj_arr = 0;
  }

  if ($type_coord_arr == 'cart') {
    $echo[$type_coord_arr][$type_plani_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr);
  } else {
    if ($type_alti_arr == 'a') {
      if ($type_coord_arr == 'proj') {
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr][$sys_alti_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr);
      } else {
        $echo[$type_coord_arr][$type_plani_arr][$type_alti_arr][$sys_alti_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr);
      }
    } else {
      if ($type_coord_arr == 'proj') {
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr);
      } else {
        $echo[$type_coord_arr][$type_plani_arr][$type_alti_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, $sys_alti_arr);
      }
    }
  }

}

echo json_encode($echo);



function is_true($val, $return_null=false){
    $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
    return ( $boolval===null && !$return_null ? false : $boolval );
}
?>

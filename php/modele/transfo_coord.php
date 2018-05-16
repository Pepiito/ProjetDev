<?php
//@author Hugo Lecomte

set_time_limit(60);
if (!isset($_SESSION)) session_start();

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

//on choisi si on doit calculer toutes les coordonnées possible ou non
$addmap = isset($_POST['addMap']) ? is_true($_POST['addMap']) : FALSE;

//on obtient les coordonnées cartesiennes en ETRS89/RGF93 de tous les points
list($X_tmp, $Y_tmp, $Z_tmp, $len) = traitement_vers_milieu($_POST);

//on ajoute le nom de chaque point à l'array de sortie ou on le crée
if (isset($_POST['n'])) {
  //récupération du nom
  $nom = explode(';', $_POST['n']);
} else {
  //création du nom
  $nom = array();
  for ($i=0; $i<$len;$i++) {
    $nom[$i] = 'point'.str_pad($i.'', 4, '0', STR_PAD_LEFT);
  }
}

//on commence à forme l'array de sortie
$echo = array('n' => $nom);

//on récupère le format de la donnée de sortie
$type_coord_arr = $_POST['_t'];
$type_plani_arr = $_POST['_P'];
if (!($type_coord_arr == 'cart')) {
  $type_alti_arr = $_POST['_T'];
  if ($type_alti_arr == 'a') {
    if (!(isset($_POST['_A'])) || $_POST['_A'] == 'false') {
      exit("Erreur 133: Cas d'utilisation impossible. Vérifier le système altimétrique en sortie");
    } else {
      $sys_alti_arr = $_POST['_A'];
    }
  } else {
    $sys_alti_arr = 0;
  }
} else {
  $type_alti_arr = 0;
}
if ($type_coord_arr == 'proj') {
  if (!(isset($_POST['_p'])) || $_POST['_p'] == 'false') {
    exit("Erreur 131: Cas d'utilisation impossible. Vérifier la projection de sortie");
  } else {
    $type_proj_arr = $_POST['_p'];
  }
} else {
  $type_proj_arr = 0;
}

if ($addmap) {
  // Je fais des arrays pour tous les cas possibles pour l'ajout à la carte et à la BDD
  $coord = array('cart', 'geog', 'proj');
  $plani = array('RGF93', 'NTF', 'ETRS89', 'CH1903plus', 'CH1903');
  $alti = array('a', 'h');
  $sys_alti = array('RGF93' => array('IGN69'), 'NTF' => array('IGN69'), 'ETRS89' => array(), 'CH1903plus' => array('RAN95', 'NF02'), 'CH1903' => array('RAN95', 'NF02'));
  $proj = array('RGF93' => array('CC42', 'CC43', 'CC44', 'CC45', 'CC46', 'CC47', 'CC48', 'CC49', 'CC50', 'Lambert93'), 'NTF' => array('Lambert1', 'Lambert2', 'Lambert2etendu', 'Lambert3', 'Lambert4'), 'CH1903' => array('MN03'), 'CH1903plus' => array('MN95'), 'ETRS89' => array());


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
  // je calcul seulement le cas demandé
  if ($type_coord_arr == 'cart') {
    $echo[$type_coord_arr][$type_plani_arr] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, $type_coord_arr, $type_plani_arr, $type_alti_arr, $type_proj_arr, 0);
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
  //avec le cas ETRS89 en plus pour ensuite calculer la déviation de la verticale si besoin
  $echo['ETRS89']['geog']['h'] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, 'geog', 'ETRS89', 'h', 0, 0);
  $echo['CH1903plus']['geog']['h'] = conversion_vers_sortie($X_tmp, $Y_tmp, $Z_tmp, 'geog', 'CH1903plus', 'h', 0, 0);

}

//calcul de la dévition de la verticale si besoin
if (isset($_POST['c']) && isset($_POST['x'])) {
  if ($_POST['c'] != 'false' && $_POST['x'] != 'false') {
    $eta_dep = explode(';', $_POST['c']);
    $ksi_dep = explode(';', $_POST['x']);
    if ($_POST['t'] == 'proj' && $_POST['P'] != 'NTF') {
    //choix du mode
      if ($type_coord_arr == 'proj' && ($type_plani_arr == 'RGF93' && ($_POST['P'] == 'CH1903' || $_POST['P'] == 'CH1903plus'))) {
        $mode = 'fr';
      } else if ($type_coord_arr == 'proj' && ($type_plani_arr == 'CH1903' || $type_plani_arr == 'CH1903plus') && $_POST['P'] == 'RGF93') {
        $mode = 'ch';
      } else {
        exit("Erreur 130: Les coordonnées de sortie doivent être projetées du RGF93 vers CH1903(+) ou inversement pour calculer la déviation de la verticale");
      }
    } else {
      exit("Erreur 129: Les coordonnées d'entrée doivent être projetées dans un système autre que NTF");
    }
    //calcul pour tous les points
    for ($i=0; $i<$len; $i++) {
	  $Bessel_lambda = $echo['CH1903plus']['geog']['h']['lambda']['lambda'.$i];
      $Bessel_phi = $echo['CH1903plus']['geog']['h']['phi']['phi'.$i];
      $lambda = $echo['ETRS89']['geog']['h']['lambda']['lambda'.$i];
      $phi = $echo['ETRS89']['geog']['h']['phi']['phi'.$i];
      list($eta, $ksi, $zeta) = deviation_verticale($mode, $Bessel_lambda, $Bessel_phi, $lambda, $phi, $eta_dep[$i], $ksi_dep[$i]);
      if ($type_alti_arr == 'a') {
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr][$sys_alti_arr]['eta']['eta'.$i] = $eta;
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr][$sys_alti_arr]['ksi']['ksi'.$i] = $ksi;
      } else if ($type_alti_arr == 'h') {
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr]['eta']['eta'.$i] = $eta;
        $echo[$type_coord_arr][$type_plani_arr][$type_proj_arr][$type_alti_arr]['ksi']['ksi'.$i] = $ksi;
      }
    }
  }

}

//on écrit les points dans la BDD si on veut ajouter le point à la carte
if ($addmap) include("../vue/write_to_postgis.php");

//on renvoie un json en format défini avec la ou les coordonnées voulues
echo json_encode($echo);


//fonction pour vérifier la validité d'une variable
function is_true($val, $return_null=false){
    $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
    return ( $boolval===null && !$return_null ? false : $boolval );
}
?>

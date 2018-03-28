<?php if (!isset($_SESSION)) session_start(); ?>

<?php
include('cartesiennes.php');
include('fonctions_fr.php');
include('Deviation_verticale.php');
include('Transfo_geogr_ellips_CH1903+_to_CH1903+.php');
include('Transfo_geogr_to_geogr_ellips_CH1903+.php');
?>

<?php // Récupère les variables AJAX dans la variable $_POST au format convenu
$type_coord = $_POST['t'];
$type_alti = $_POSt['T'];
?>

<?php
if ( == 'alti') {
  $H = ;
  $h = ;
} else if ( == 'hauteur') {
  $h = ;
}

if ($type_coord == 'proj') {
  $E = ;
  $N = ;
  if ( == 'NTF') {
    $lambda = ;
    $phi = ;
  } else if ( == 'RGF') {
    $lambda = ;
    $phi = ;
  }

} else if ($type_coord == 'geog') {
  $lambda = floatval($_POST['l']);
  $phi = floatval($_POST['f']);
  if ( == 'RGF') {
    $ellipse = new Ellipse('IAG_GRS_1980');
    if ($type_alti == 'a') {
      $H = floatval($_POST['H']);
      $h = alti_to_h($lambda, $phi, $H);
    } else if ($type_alti == 'h') {
      $h = floatval($_POST['h']);
    }
  } else if ( == 'NTF') {
    $ellipse = new Ellipse('Clarke_1880');
  }
  $array_cart = geographic_to_cartesien($lambda, $phi, $h, $ellipse);
  $X = $array_cart[0];
  $Y = $array_cart[1];
  $Z = $array_cart[2];

} else if ($type_coord == 'cart') {
  $X = floatval($_POST['X']);
  $Y = floatval($_POST['Y']);
  $Z = floatval($_POST['Z']);
}
?>

<?php  /* Renvoie une chaine de caractère au format var1=foo&var2=bar.
       L'ajax récupèrera le contenu intégral de ce qui est renvoyé,
       au format "Error XXX: description" si erreur ou "var1=foo&var2=bar" si ok.

       Ce fichier sert de boite de réception / d'envoi de la couche modèle : ne pas le renommer.
       Le contenu pourra être contenu dans des fichiers externes reliés par un include().
       */
       ?>

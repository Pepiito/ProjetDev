<?php

$separateur = (isset($_POST['separateur'])) ? $_POST['separateur'] : NULL;
$start =  (isset($_POST['start'])) ? $_POST['start'] : NULL;
$format =  (isset($_POST['format'])) ? $_POST['format'] : NULL;
$file = (isset($_FILES['file'])) ? $_FILES['file'] : NULL;

if (!(($separateur != NULL) && ($start != NULL) && $format && $file)) {
  echo "Error 202 : Paramètres invalides";
  exit;
}

if ($file['error'] != 0) {
  echo 'Error 203 : Fichier illisible';
  exit;
}

if (($f = file($file['tmp_name'])) !== FALSE) {

  $data = [];

  for ($i = 0; $i < strlen($format); $i++) {
    $data[$format[$i]] = [];
  }

  $nb_points = count($f) - $start;

  for ($line = $start; $line < $nb_points; $line++) {

    if (!preg_match("/\d+/", $f[$line])) continue; // Si la ligne ne contient pas un seule nombre on ne la traite pas.

    // suppression des multi-spaces et tabulations puis séparation selon le séparateur
    if ($separateur == "false") $separateur = " ";

    $escaped_sep = addslashes($separateur);
    $coords = preg_split("/$escaped_sep+/", $f[$line]);

    if (!$coords[count($coords)-1]) array_pop($coords);

    if (count($coords) != strlen($format)) {
      echo "Error 204 : Ligne $line non valide (". strlen($format) ." valeurs attendues, ". count($coords) ." données). Vérifier le séparateur";
      exit;
    }

    for ($i = 0; $i < strlen($format); $i++) {

      $data[$format[$i]][$line] = preg_replace('/\s+/', '', $coords[$i]);

    }

  }
  echo json_encode($data);
}
else {
  echo 'Error 203 : Fichier illisible';
  exit;
}





 ?>

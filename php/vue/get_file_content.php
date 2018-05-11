<?php

$separateur = (isset($_POST['separateur'])) ? $_POST['separateur'] : NULL;
$start =  (isset($_POST['start'])) ? intval($_POST['start']) : NULL;
$format =  (isset($_POST['format'])) ? $_POST['format'] : NULL;
$file = (isset($_FILES['file'])) ? $_FILES['file'] : NULL;

if (($separateur == NULL) && ($start == NULL) && ($format == NULL) && ($file == NULL)) {
  echo "Error 202 : Paramètres invalides";
  exit;
}

if ($file['error'] != 0) {
  echo 'Error 203 : Fichier illisible';
  exit;
}

$extension = pathinfo($file['name'])['extension'];
$extensions_autorisees = array('txt', 'csv', 'in');
if (!in_array($extension, $extensions_autorisees)) {
  echo "Error 209 : Le format utilisé n'est pas permit. Préférez .txt, .csv";
  exit;
}

if (($f = file($file['tmp_name'])) !== FALSE) {

  $data = [];

  for ($i = 0; $i < strlen($format); $i++) {
    $data[$format[$i]] = [];
  }
  $nb_lines = count($f);
  $nbmaxpoints = 10;
  $nb_points = 0;

  for ($line = $start; $line < $nb_lines; $line++) {

    if (!preg_match("/\d+/", $f[$line])) continue; // Si la ligne ne contient pas un seule nombre on ne la traite pas.
    if ($f[$line][0] == "#" || $f[$line][0] == "*") continue; // SI la ligne commence avec un dièse ou une étoile, skip.

    // suppression des multi-spaces et tabulations puis séparation selon le séparateur
    if ($separateur == "false") $separateur = " ";

    $escaped_sep = addslashes($separateur);
    $coords = preg_split("/$escaped_sep+/", preg_replace("/^\s+/", "", $f[$line]));

    if (!preg_match("/\d+/", $coords[count($coords)-1])) array_pop($coords);

    if (count($coords) != strlen($format)) {
      echo "Error 204 : Ligne ".($line+1)." non valide (". strlen($format) ." valeurs attendues, ". count($coords) ." données). Vérifier le séparateur";
      exit;
    }

    for ($i = 0; $i < strlen($format); $i++) {

      $data[$format[$i]][$line] = preg_replace('/\s+/', '', $coords[$i]);

    }

    $nb_points++;
    if ($nb_points > $nbmaxpoints) {
      echo 'Error 208 : Le nombre de point est trop grand. Maximum : '.$nbmaxpoints.' points. Séparez en plusieurs fichiers';
      exit;
    }

  }
  echo json_encode($data);
}
else {
  echo 'Error 203 : Fichier illisible';
  exit;
}





 ?>

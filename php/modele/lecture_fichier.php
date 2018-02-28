<?php
function lecture_fichier($fichier){
  $file = fopen($fichier, "r");
  if (FALSE === $file) {
    exit("Echec lors de l'ouverture du flux vers l'URL");
  } else {
    $contenu = fread($file, filesize($file));
    $fclose($file);
  }
  return $contenu;
}
?>

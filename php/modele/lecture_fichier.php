<?php
function lecture_fichier($fichier){
  $file = fopen($fichier, "r");
  if (FALSE === $file) {
    echo("Error 100: Echec lors de l'ouverture du flux vers l'URL");
    exit;
  } else {
    $contenu = fread($file, filesize($fichier));
    fclose($file);
  }
  return $contenu;
}
?>

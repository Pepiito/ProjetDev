<?php
include("lecture_fichier.php");
class Ellipse {
  private $_nom;
  private $_a;
  private $_b;
  private $_e;
  private $_f;

  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/ellipsoide.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("L'éllipsoïde demandé n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne)

      $tab = explode(" ", $ligne);
      $this->nom = $nom;
      $this->a = $tab[1];
      $this->b = $tab[2];

      $this->f = ($this->a - $this->b)/$this->a;
      $this->e = (($this->a**2 - $this->b**2)/($this->b**2))**(1/2);
    }
  }

  /**
 * Methode __get()
 *
 * Retourne la valeur de l'attribut appelée
 *
 * @param string $att
 * @return int $age
 * @throws Exception
 */
public function __get($att) {

  if('a' === $att) {
    return $this->a;
  }
  elseif ('b' === $att) {
    return $this->b;
  }
  elseif ('e' === $att) {
    return $this->e;
  }
  elseif ('f' === $att) {
    return $this->f;
  }
  elseif ('nom' === $att) {
    return $this->nom;
  }
  else {
    echo('Error 110: Attribut de Ellipse invalide !');
    exit;
  }
}

  /**
 * Methode __set()
 *
 * Fixe la valeur de l'attribut appelée
 *
 * @param string $att
 * @param mixed $value
 * @return void
 * @throws Exception
 */
public function __set($att, $value) {

  if('a' === $att) {
    $this->a = (float) $value;
  }
  elseif ('b' === $att){
    $this->b = (float) $value;
  }
  elseif ('f' === $att){
    $this->f = (float) $value;
  }
  elseif ('b' === $att){
    $this->e = (float) $value;
  }
  elseif ('nom' === $att){
    $this->nom = (string) $value;
  }
  else {
    echo('Error 110: Attribut de Ellipse invalide !');
    exit;
  }
}
}
?>

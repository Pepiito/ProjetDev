<?php
include("lecture_fichier.php");
class Ellipse {
  private $_nom;
  private $_a;
  private $_b;
  private $_e;
  private $_f;

  public fonction __construct($nom) {
    $contenu = lecture_fichier("../../files/ellipsoide.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("L'éllipsoïde demandé n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne)

      $tab = explode(" ", $ligne);
      $this->_nom = $nom;
      $this->_a = $tab[1];
      $this->_b = $tab[2];

      $this->_f = ($this->_a - $this->_b)/$this->_a;
      $this->_e = (($this->_a**2 - $this->_b**2)/($this->_b**2))**(1/2);
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
    return $this->_a;
  }
  elseif ('b' === $att) {
    return $this->_b;
  }
  elseif ('e' === $att) {
    return $this->_e;
  }
  elseif ('f' === $att) {
    return $this->_f;
  }
  elseif ('nom' === $att) {
    return $this->_nom;
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
    $this->_a = (float) $value;
  }
  elseif ('b' === $att){
    $this->_b = (float) $value;
  }
  elseif ('f' === $att){
    $this->_f = (float) $value;
  }
  elseif ('b' === $att){
    $this->_e = (float) $value;
  }
  elseif ('nom' === $att){
    $this->_nom = (string) $value;
  }
  else {
    echo('Error 110: Attribut de Ellipse invalide !');
    exit;
  }
}
}
?>

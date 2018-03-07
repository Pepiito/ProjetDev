<?php
include("lecture_fichier.php");

class Ellipse {
  private $nom;
  private $a;
  private $b;
  private $e;
  private $f;

  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/ellipsoide.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("Erreur 121: L'éllipsoïde demandé n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne);

      $tab = explode(" ", $ligne);
      $this->nom = $nom;
      $this->a = $tab[1];
      $this->b = $tab[2];

      $this->f = ($this->a - $this->b)/$this->a;
      $this->e = (($this->a**2 - $this->b**2)/($this->a**2))**(1/2);
    }
  }

  /**
 * Methode __get()
 *
 * Retourne la valeur de l'attribut appelée
 *
 * @param string $att
 * @return mixed
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
    echo('Error 110: Unexpected Error');
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
    echo('Error 110: Unexpected Error');
    exit;
  }
 }
}

class Cone {
  private $nom;
  private $lambda0;
  private $phi0;
  private $X0;
  private $Y0;
  private $k0;
  private $ellipse;
  private $C;
  private $n;
  private $R0;


  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/projection_conique.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("Erreur 122: La projection conique demandée n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne);

      $tab = explode(" ", $ligne);
      $this->nom = $nom;
      $this->lambda0 = $tab[1];
      $this->phi0 = $tab[2];
      $this->X0 = $tab[3];
      $this->Y0 = $tab[4];
      $this->k0 = $tab[5];
      $this->ellipse = new Ellipse($tab[6]);

      $a = $this->ellipse->__get('a');
      $b = $this->ellipse->__get('b');
      $e = $this->ellipse->__get('e');

      $this->n = sin($phi0);
      $this->R0 = $b**2/$a*cos(atan($b/$a*tan($this->phi0)))/sin($this->phi0) * $this->k0;
      $this->C = $this->R0 * exp($this->n * log(tan(pi()/4 + $this->phi0/2)) - $e/2*log((1 + $e*sin($this->phi0))/(1 - $e*sin($this->phi0))));
    }
  }

  /**
 * Methode __get()
 *
 * Retourne la valeur de l'attribut appelée
 *
 * @param string $att
 * @return mixed
 */
 public function __get($att) {

  if('nom' === $att) {
    return $this->nom;
  }
  elseif ('lambda0' === $att) {
    return $this->lambda0;
  }
  elseif ('phi0' === $att) {
    return $this->phi0;
  }
  elseif ('X0' === $att) {
    return $this->X0;
  }
  elseif ('Y0' === $att) {
    return $this->Y0;
  }
  elseif ('ellipse' === $att) {
    return $this->ellipse;
  }
  elseif ('k0' === $att) {
    return $this->k0;
  }
  elseif ('C' === $att) {
    return $this->C;
  }
  elseif ('n' === $att) {
    return $this->n;
  }
  elseif ('R0' === $att) {
    return $this->R0;
  }
  else {
    echo('Error 110: Unexpected Error');
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
 */
 public function __set($att, $value) {

  if('nom' === $att) {
    $this->nom = (string) $value;
  }
  elseif ('lambda0' === $att){
    $this->lambda0 = (float) $value;
  }
  elseif ('phi0' === $att){
    $this->phi0 = (float) $value;
  }
  elseif ('X0' === $att){
    $this->X0 = (float) $value;
  }
  elseif ('Y0' === $att){
    $this->Y0 = (float) $value;
  }
  elseif ('k0' === $att){
    $this->k0 = (float) $value;
  }
  elseif ('ellipse' === $att){
    $this->ellipse = $value;
  }
  elseif ('C' === $att){
    $this->C = (float) $value;
  }
  elseif ('n' === $att){
    $this->n = (float) $value;
  }
  elseif ('R0' === $att){
    $this->R0 = (float) $value;
  }
  else {
    echo('Error 110: Unexpected Error');
    exit;
  }
 }
}
?>

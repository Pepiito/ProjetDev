<?php
//@author Hugo Lecomte

class Ellipse {
  private $nom;
  private $a;
  private $b;
  private $e;
  private $f;

  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/ellipsoide.txt");
    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("Erreur 121: L'ellipsoide demandé n'existe pas");
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

class Cone_CC  {
  private $nom;
  private $lambda0;
  private $phi0;
  private $phi1;
  private $phi2;
  private $X0;
  private $Y0;
  private $ellipse;
  private $C;
  private $n;


  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/cone_CC.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("Erreur 122: La projection demandée n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne);

      $tab = explode(" ", $ligne);
      $this->nom = $nom;
      $this->lambda0 = $tab[1]*pi()/180;
      $this->phi0 = $tab[2]*pi()/180;
      $this->phi1 = $tab[3]*pi()/180;
      $this->phi2 = $tab[4]*pi()/180;
      $this->X0 = $tab[5];
      $this->Y0 = $tab[6];
      $this->ellipse = new Ellipse(substr($tab[7], 0, strlen($tab[7])-1));

      $a = $this->ellipse->__get('a');
      $b = $this->ellipse->__get('b');
      $e = $this->ellipse->__get('e');

      $this->n = log((1-$e**2*sin($this->phi1)**2)**(1/2)*cos($this->phi2)/(1-$e**2*sin($this->phi2)**2)**(1/2)/cos($this->phi1))
      /($this->L_CC($this->phi1) - $this->L_CC($this->phi2));
      $this->C = $a*cos($this->phi1)/(1-$e**2*sin($this->phi1)**2)**(1/2)/$this->n*exp($this->n*$this->L_CC($this->phi1));
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
  elseif ('phi1' === $att) {
    return $this->phi1;
  }
  elseif ('phi2' === $att) {
    return $this->phi2;
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
  elseif ('C' === $att) {
    return $this->C;
  }
  elseif ('n' === $att) {
  return $this->n;
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
  elseif ('phi1' === $att){
    $this->phi1 = (float) $value;
  }
  elseif ('phi2' === $att){
    $this->phi2 = (float) $value;
  }
  elseif ('X0' === $att){
    $this->X0 = (float) $value;
  }
  elseif ('Y0' === $att){
    $this->Y0 = (float) $value;
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
  else {
    echo('Error 110: Unexpected Error');
    exit;
  }
 }

 /**
 * Méthode L_CC
 *
 * Retourne la donnée L en mÃ¨tre obtenue Ã  partir de la latitude géographique en radian
 *
 * @param float $phi
 * @param Ellipse $ellipse
 * @return float
 */
 public function L_CC($phi) {
   $e = $this->ellipse->__get('e');

   return log((1 + sin($phi))/(1 - sin($phi)))/2 - $e/2*log((1 + $e*sin($phi))/(1 - $e*sin($phi)));
 }
}

class Cone_Lambert  {
  private $nom;
  private $lambda0;
  private $phi0;
  private $X0;
  private $Y0;
  private $k0;
  private $ellipse;
  private $n;


  public function __construct($nom) {
    $contenu = lecture_fichier("../../files/cone_Lambert.txt");

    if (($indice = strpos($contenu, $nom)) === FALSE) {
      exit("Erreur 122: La projection demandée n'existe pas");
    } else {
      $contenureduit = substr($contenu, $indice);
      $sortieligne = strpos($contenureduit, "\n");
      $ligne = substr($contenureduit, 0, $sortieligne);

      $tab = explode(" ", $ligne);
      $this->nom = $nom;
      $this->lambda0 = $tab[1]*pi()/180;
      $this->phi0 = $tab[2]*pi()/180;
      $this->X0 = $tab[3];
      $this->Y0 = $tab[4];
      $this->k0 = $tab[5];
      $this->ellipse = new Ellipse(substr($tab[6], 0, strlen($tab[6])-1));

      $a = $this->ellipse->__get('a');
      $b = $this->ellipse->__get('b');
      $e = $this->ellipse->__get('e');

      $this->n = sin($this->phi0);
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
  elseif ('k0' === $att) {
    return $this->k0;
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
  elseif ('n' === $att) {
    return $this->n;
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
  elseif ('k0' === $att){
    $this->k0 = (float) $value;
  }
  elseif ('phi2' === $att){
    $this->phi2 = (float) $value;
  }
  elseif ('X0' === $att){
    $this->X0 = (float) $value;
  }
  elseif ('Y0' === $att){
    $this->Y0 = (float) $value;
  }
  elseif ('ellipse' === $att){
    $this->ellipse = $value;
  }
  elseif ('n' === $att){
    $this->n = (float) $value;
  }
  else {
    echo('Error 110: Unexpected Error');
  exit;
  }
 }

}
?>

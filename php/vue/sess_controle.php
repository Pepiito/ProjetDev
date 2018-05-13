<?php
// Hachage du mot de passe

include("connexion_postgis.php");

$pseudo = pg_escape_string($conn, $_POST['pseudo']);


// Vérification des identifiants
$res = pg_query($conn, "SELECT password FROM session WHERE pseudo='$pseudo';");
$password = pg_fetch_result($res,0,0);
if(isset($password)){
	if(password_verify($_POST['password'], $password)){
		session_start();
		$_SESSION['pseudo'] = $pseudo;
		$res = pg_query($conn, "SELECT id_sess FROM session WHERE pseudo='".$pseudo."';");
		$_SESSION['id_sess'] = pg_fetch_result($res,0,0);
    echo 'Success';
    exit;
	}else{
      echo 'connexion password';
      exit;
	}
}else{

      echo 'connexion pseudo';
      exit;
}

?>

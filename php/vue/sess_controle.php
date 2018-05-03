<?php 
// Hachage du mot de passe

include("connexion_postgis.php");
    
$pseudo = pg_escape_string($conn, $_POST['pseudo']);


// Vérification des identifiants
$res = pg_query($conn, "SELECT password FROM session WHERE pseudo='$pseudo';");
$password = pg_fetch_result($res,0,0);
if(isset($password)){
	if(password_verify($_POST['password'], $password)){
		echo 'connexion réussie';
		session_start();
		$_SESSION['pseudo'] = $pseudo;
		header('Location: ../../index.php');
	}else{
		header('Location: ../../session.php?errc=pass');
	}
}else{
	header('Location: ../../session.php?errc=pseudo');
}

?>
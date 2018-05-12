<?php
include("./php/vue/connexion_postgis.php");

if(isset($_SESSION['pseudo'])){
	$res = pg_query($conn, "SELECT id_sess FROM session WHERE pseudo='".$_SESSION['pseudo']."';");
	$_SESSION['id_sess'] = pg_fetch_result($res,0,0);
}elseif(!isset($_SESSION['id_aleat'])){
	$res = pg_query($conn, 'SELECT max(id_aleat) FROM "Points_session";');
	$_SESSION['id_aleat'] = pg_fetch_result($res,0,0)+1;
}
echo 'Success';
?>

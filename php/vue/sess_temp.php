<?php
include("connexion_postgis.php");


session_start();
$res = pg_query($conn, 'SELECT max(id_aleat) FROM "Points_session";');
$_SESSION['id_aleat'] = pg_fetch_result($res,0,0)+1;

echo 'Success';
?>

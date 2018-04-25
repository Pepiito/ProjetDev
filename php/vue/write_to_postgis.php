<?php
$conn = pg_connect("host=localhost port=5432 dbname=ProjetDEV user=postgres password=postgres");

if (isset ($_POST['commune'])){
	pg_query($conn, 'INSERT INTO "Points_session"(
	id_ptsess, id_aleat, aleatoire, id_sess, num_pt, chantier, "X_ETRS89", "Y_ETRS89", "Z_ETRS89", "long_ETRS89", "lat_ETRS89", "h_ETRS89", "X_CH1903plus", "Y_CH1903plus", "Z_CH1903plus", "long_CH1903plus", "lat_CH1903plus", "h_CH1903plus", "E_CH1903plus", "N_CH1903plus", "E_CH1903", "N_CH1903", "alt_RAN95", "alt_NF02", "E_RGF", "N_RGF", "X_NTF", "Y_NTF", "Z_NTF", "long_NTF", "lat_NTF", "h_NTF", "E_NTF", "N_NTF", "num_NTF", "alt_IGN69", geom)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
}



?>
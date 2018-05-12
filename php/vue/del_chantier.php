<?php
            include("connexion_postgis.php");
			
			$id_sess = pg_escape_string($conn, $_GET['id_sess']);
			$date_chantier = pg_escape_string($conn, $_GET['date_chantier']);

			

			pg_query($conn, "DELETE FROM \"Points_session\"
				WHERE date_chantier='$date_chantier' AND id_sess=$id_sess;
				");


			
			
			
			header('Location: ../../gestion-compte.php?id_sess='.$id_sess);
?>
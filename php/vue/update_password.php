<?php
            include("connexion_postgis.php");
			
			$id_sess = pg_escape_string($conn, $_POST['id_sess']);
			$password = pg_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT));
            $pass2 = pg_escape_string($conn, $_POST['pass2']);
			
			

			if (password_verify($pass2, $password)) {
				pg_query($conn, "UPDATE session
				SET password='$password'
				WHERE id_sess =$id_sess"); 
			header('Location: ../../gestion-compte.php?id_sess='.$id_sess);
			}else{
				echo 'mot de passe pas identique';
			header('Location: ../../gestion-compte.php?id_sess='.$id_sess.'&err=1');
			}
			
			
			
?>
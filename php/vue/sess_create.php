<?php
            include("connexion_postgis.php");

            // Example imported from : http://www.tutorialrepublic.com/php-tutorial/php-mysql-insert-query.php
            // Escape user inputs for security

			$pseudo = pg_escape_string($conn, $_POST['pseudo']);
            $password = pg_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT));
            $pass2 = pg_escape_string($conn, $_POST['pass2']);

            $sql = "INSERT INTO session (pseudo, password) VALUES ('$pseudo', '$password');";
            if (password_verify($pass2, $password)) {
                if(pg_query($conn, $sql)){
                    session_start();
                    $_SESSION['pseudo'] = $pseudo;
                    echo 'Success';
                    exit;
                } else{
                    echo 'inscription pseudo';
                    exit;

                }

            } else{
  				        echo 'insciption password';
                  exit;
            }
?>

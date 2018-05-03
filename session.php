<?php 
if (isset($_SESSION)) session_destroy();

?>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>projet Dev</title>
    <meta name="author" content="Benoit Messiaen, Hugo Lecomte, Della Casa Bruno, Bobillier Quentin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./images/terre.png" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
	<link rel="stylesheet" href="css/style_sess.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <!--<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>-->
    <script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>
    <script src='./js/main.js'></script>
	  <script type="text/javascript" src="https://openlayers.org/en/master/build/ol.js"></script>
	  <link rel="stylesheet" type="text/css" href="https://openlayers.org/en/master/css/ol.css" />
	  <link href="http://allfont.net/allfont.css?fonts=greek" rel="stylesheet" type="text/css" />
  </head>
	<body>
		<div id="title">
		<h1 style="margin:auto;text-align: center;text-transform: uppercase;font-size: 45px;">Weblogiciel de transformation de coordonnées Franco-Suisse</h1>
		</div>
			<div id="form_conn">
			<div class="container">
            <!-- zone de connexion -->
            
            <form action="php/vue/sess_controle.php" method="POST">
                <h1>Connexion</h1>
                
                <label><b>Pseudo</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="pseudo" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>
				<?php
				if(isset($_GET['errc'])){
					echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
				}
				?>
                <input type="submit" id='submit' value="S'IDENTIFIER" >

            </form>
			
			</div>
			
			<div class="container">
			<form action="php/vue/sess_create.php" method="POST">
                <h1>S'inscrire</h1>
                
                <label><b>Pseudo</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="pseudo" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>
				<label><b>Répéter le mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="pass2" required>
				<?php
				if(isset($_GET['erri'])){
					$err = $_GET['erri'];
					if($err=='pseudo'){
						echo "<p style='color:red'>Pseudo déjà existant</p>";
					}else{
						echo "<p style='color:red'>Mots de passe différents</p>";
					}
				}
				?>
                <input type="submit" id='submit' value="S'INSCRIRE" >

            </form>
			
			</div>
			<div class="container">
			<form action="index.php" style="margin-top:40%;" method="POST">
                <input type="hidden" value="aleat" name="aleat">

                <input type="submit" id='submit' value='Continuer sans connexion' >

            </form>
			</div>
			</div>
			<img id="img-ensg" src="images/ensg.png" > 
			<img id="img-heig" src="images/heig.png"> 
			<div id="div-img-logo">
			<img id="img-logo" src="images/logo.png" height=150px> 
			</div>
 </body>
</html>
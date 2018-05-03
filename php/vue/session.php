<?php 
if (isset($_SESSION)) session_destroy();
include("./php/vue/head.php");
?>
	<div style="display:flex;">
		<div id="container">
            <!-- zone de connexion -->
            
            <form action="sess_controle.php" method="POST">
                <h1>Connexion</h1>
                
                <label><b>Nom d'utilisateur</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>

                <input type="submit" id='submit' value='LOGIN' >

            </form>
                <?php
                if(isset($_GET['erreur'])){
                    $err = $_GET['erreur'];
                    if($err==1 || $err==2)
                        echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
                }
            ?>
        </div>
        <div style="padding: 10px 10px 10px 10px; width: 40%;">
        <div class="insert-compte">
        <h4>Création de compte</h4>
            <form method="POST" action="sess_create.php">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="pseudo" name="pseudo" id="pseudo" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="pass2">Répéter mot de passe</label>
                    <input type="password" name="pass2" id="pass2" class="form-control" required />
                </div>
                <input type="submit" value="Créer">
            </form>
        </div>
		<div>
			<a href="../../index.php">Continuer sans connexion</a>
		</div>
	</div>
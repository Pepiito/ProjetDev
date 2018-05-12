<?php include("./php/vue/head_gestion.php");
include("./php/vue/connexion_postgis.php");?>
<body id="body">
<div id="form_gestion" class="form_gestion">
	<div class="container_gestion">
		<form method="post" action="php/vue/update_password.php">
			<h1 id="change_password">Changement de mot de passe</h1>
			<label><b>Mot de passe</b></label>
			<input type="password" placeholder="Entrer le mot de passe" name="password" required>
			<label><b>Répéter le mot de passe</b></label>
			<input type="password" placeholder="Entrer le mot de passe" name="pass2" required>
			<input type="hidden" name="id_sess" value="<?php echo $_GET['id_sess']?>">
			<input style="margin-top:15px" type="submit" style="width:135px"  value="Modifer" />
			<?php
				if(isset($_GET['err'])){
					echo "<p style='color:red'>Mot de passe pas identique</p>";
				}
				?>
		</form>
	</div>
	<div class="container_gestion">
	<form>
	<h1 id="liste_chantier">Liste des chantiers</h1>
		<?php
		echo '<table width="80%">';
		echo '<thead><tr><th>Date du chantier</th><th></th></thead><tbody>';
		$id_sess = $_GET['id_sess'];
		$result = pg_query($conn, "SELECT date_chantier FROM \"Points_session\" WHERE id_sess=".$id_sess." GROUP BY date_chantier");
		
		while ($row = pg_fetch_row($result)) {
				$date = strval($row[0]);
				echo '<tr>';
				echo '<td>',$row[0],'</td>';
				echo "<td style=\"margin:0 auto;\" onclick=\"del_conf($id_sess,'$date')\" ><img src=\"images/supp.jpg\" height =\"20 px\" width =\"25 px\"  /></td>";
				
				echo '</tr>';
			}
		echo '</tbody>';
		?>
	</form>
	</div>
		
	</div>
</div>
<script>
		function del_conf(id_sess, date_chantier){
			if (confirm("Voulez-vous supprimer ce chantier?")) {
				location.assign("./php/vue/del_chantier.php?id_sess="+id_sess+"&date_chantier="+date_chantier)
			} else {
				txt = "You pressed Cancel!";
			}
		}
		
</script>
</body>
</html>
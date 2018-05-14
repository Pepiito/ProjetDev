<?php
include("./php/vue/connexion_postgis.php");
if (!isset($_SESSION)) session_start();
echo '<p></p>';
 ?>
<?php
include("./php/vue/data_functions.php");
include("./php/vue/postgis_to_geojson.php");
?>
<!DOCTYPE html>
<?php include('./php/vue/head.php'); ?>
	<body>
    <div class=corps>
			<div class="legende">
				<?php include("./php/vue/legende.php"); ?>
			</div>
			<div style="width:100%;position:relative;">
				<div id="map"></div>
				<div>
					<div class="button_transfo"><input class="button_tran" id="button_tran" type="submit" value="Transformer vos coordonnées"/></div>
					<?php
					if(isset($_SESSION['pseudo'])){
						echo '<div class="button_gestion"><input class="button_gest" id="button_gest" type="submit" value="Gestion"/></div>';
					}
					?>
					<div class=home><img src=./images/home.png alt=Accueil height=50% style=margin:25%></div>
					<div class="school"><img src="images/ensg-heig.png" width="350" height="81"/></div>
				</div>
			</div>
			<div id="popup-map" class="ol-popup">
	      <a href="#" id="popup-map-closer" class="ol-popup-closer"></a>
	      <div id="popup-map-content"></div>
	    </div>
		</div>
		<?php include("./php/vue/change_coord.php"); ?>
		<script type="text/javascript">
		var geojson_pfp1=<?php echo $geojson_pfp1; ?>;
		var geojson_pfa1=<?php echo $geojson_pfa1; ?>;
		var geojson_ptsess=<?php echo $geojson_ptsess; ?>;		
		<?php 
		if(isset($_SESSION['pseudo'])){
			echo 'var id_sess='.$_SESSION['id_sess'].';';
			echo 'var connexion_ut = true;';
		}else{
			echo 'var connexion_ut = false;';
		}
		?>
		</script>
		<script src="js/map.js"></script>
		<script src="lib/proj4.js"></script>
    <script src="lib/proj4-epsg21781.js"></script>
    <script src="lib/proj4-epsg2056.js"></script>
		<script src="lib/proj4-epsg2154.js"></script>
		<script src="lib/proj4-epsg4275.js"></script>
    <script type=text/javascript src=./js/controleur.js></script>
  </body>
</html>

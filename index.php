<?php if (!isset($_SESSION)) session_start(); ?>
<?php
include("./php/vue/head.php");
include("./php/vue/data_functions.php"); 
include("./php/vue/connexion_postgis.php");
include("./php/vue/postgis_to_geojson.php");
?>


  <body>
    <div class="corps">
	<div class="legende">
	<?php include("./php/vue/legende.php"); ?>
	</div>
	<div style="width:100%;position:relative;">
	<div id="map">
	</div>
	
	<div>
	<div class="button_transfo"><input class="button_tran" type="submit" onclick="Open_transfo()" value="Changement de coordonnées"/></div>
	<div class="school"><img src="images/ensg-heig.png" width="350" height="81"/></div>
	</div>
	</div>
	<div id="popup-map" class="ol-popup">
      <a href="#" id="popup-map-closer" class="ol-popup-closer"></a>
      <div id="popup-map-content"></div>
    </div>
	<?php include("./php/vue/change_coord.php"); ?>
	<script src="lib/proj4.js"></script>
    <script src="lib/proj4-epsg21781.js"></script>
    <script src="lib/proj4-epsg2056.js"></script>
	<script src="lib/proj4-epsg2154.js"></script>
	<script src="lib/proj4-epsg4275.js"></script>
    <?php include("./js/map.php"); ?>
    <script type="text/javascript" src="./js/controleur.js"></script>
  </body>
</html>

<?php if (!isset($_SESSION)) session_start(); ?>
<?php include("./php/vue/head.php"); ?>
  <body>
    <div class="corps">
	<div class="legende">
	<?php include("./php/vue/legende.php"); ?>
	</div>
	<div style="width:100%;position:relative;">
	<div id="map">
	</div>
	<div class="button_transfo">
		<input type="submit" value="Changement de coordonnées"/>
	</div>
	</div>
	</div>
	<script src="lib/proj4.js"></script>
    <script src="lib/proj4-epsg21781.js"></script>
    <script src="lib/proj4-epsg2056.js"></script>
	<script src="lib/proj4-epsg2154.js"></script>
    <script src="js/map.js"></script>
	
    <script type="text/javascript" src="./js/controleur.js"></script>
  </body>
</html>

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
	<script type="text/javascript">
            var map = new ol.Map({
                target: "map",

                // Couches
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.OSM()
                    })
                ],

                // Vue
                view: new ol.View({
                    center: ol.proj.fromLonLat([6.6, 46.6]),
                    zoom: 10
                })
            });
			// Affichage des coordonnées
            var mousePositionControl = new ol.control.MousePosition({
                projection: "EPSG:4326",
                coordinateFormat: ol.coordinate.createStringXY(6),
                className: "custom-mouse-position",
                target: document.getElementById("coordonnees")//coordonnees renvoie l'endroit où on affiche les coordonnées, ligne 78
            });

            map.addControl(mousePositionControl);

            // Récupération des projections suisses depuis Proj4JS
            ol.proj.get("EPSG:21781");
            ol.proj.get("EPSG:2056");
			ol.proj.get("EPSG:2154");
        </script>
	
	</div>
	<script src="lib/proj4.js"></script>
        <script src="lib/proj4-epsg21781.js"></script>
        <script src="lib/proj4-epsg2056.js"></script>
		<script src="lib/proj4-epsg2154.js"></script>
        <script type="text/javascript">//chargement des systèmes de coordonnées
            function changeProjection(code) {
                console.log("changeProjection(\"" + code + "\")");

                var digit;

                // Définition de l'unité et de la précision
                if (code == "EPSG:21781" || code == "EPSG:2056" || code == "EPSG:2154" ) {
                    digit = 2;
                }
                else if (code == "EPSG:4326") {
                    digit = 6;
                }

                // Création de la projection
                var projection = new ol.proj.Projection({
                    code: code
                });

                mousePositionControl.setProjection(projection);
                mousePositionControl.setCoordinateFormat(ol.coordinate.createStringXY(digit));
            }
        </script>
	
    <script type="text/javascript" src="./js/controleur.js"></script>
  </body>
</html>

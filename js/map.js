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

            // Récupération des projections depuis Proj4JS
            ol.proj.get("EPSG:21781");
            ol.proj.get("EPSG:2056");
			ol.proj.get("EPSG:2154");
			ol.proj.get("EPSG:4275");



function changeProjection(code) {
	console.log("changeProjection(\"" + code + "\")");

	var digit;

	// Définition de l'unité et de la précision
	if (code == "EPSG:21781" || code == "EPSG:2056" || code == "EPSG:2154" ) {
		digit = 0;
	}
	else if (code == "EPSG:4326" || code == "EPSG:4275" ) {
		digit = 2;
	}

	// Création de la projection
	var projection = new ol.proj.Projection({
		code: code
	});

	mousePositionControl.setProjection(projection);
	mousePositionControl.setCoordinateFormat(ol.coordinate.createStringXY(digit));
}
			
var modal = document.getElementById('popup');
var span = document.getElementsByClassName("close")[0];

function Open_transfo() {
	modal.style.display="block";
}
span.onclick = function() {
	modal.style.display = "none";
}
function close_popup() {
	modal.style.display = "none";
}
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	} 
}

//Chargement de vecteur de GeoServer en GeoJSON
var proxyUrl = "http://localhost/cgi-bin/proxy.cgi?url=";
var pfp1Url = "http://localhost:8080/geoserver/cite/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=cite:PFP1&maxFeatures=50&outputFormat=application%2Fjson";
var encodedUrlpfp1 = encodeURIComponent(pfp1Url);
var wfsPFP1 = new ol.layer.Vector({
                source: new ol.source.Vector({
                    format: new ol.format.GeoJSON(),
                    url: proxyUrl + encodedUrlpfp1
                }),
            });
			

var pfa1Url = "http://localhost:8080/geoserver/cite/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=cite:PFA1&maxFeatures=50&outputFormat=application%2Fjson";
var encodedUrlpfa1 = encodeURIComponent(pfa1Url);
var wfsPFA1 = new ol.layer.Vector({
                source: new ol.source.Vector({
                    format: new ol.format.GeoJSON(),
                    url: proxyUrl + encodedUrlpfa1
                }),
            });
			

var popup_map = new ol.Overlay(({
                autoPan: true
            }));
			
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
                    zoom: 10,
                }),
				overlays: [popup_map]
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
			
//Ajouter à la carte les points fixes
map.addLayer(wfsPFP1);
map.addLayer(wfsPFA1);


map.on('click', function(e) {
              popup_map.setPosition();
              var features = map.getFeaturesAtPixel(e.pixel);
			  
              if (features) {
				console.log(features)
                var coords = e.coordinate;
                
				console.log(coords);
				//window.open("dossier.php?id="+iddoss);
              }
            });

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
	modal.style.display="flex";
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
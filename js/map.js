
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

//Variable de la popup			
var container = document.getElementById('popup-map');
var content = document.getElementById('popup-map-content');
var closer = document.getElementById('popup-map-closer');
	  
	  
var popup_map = new ol.Overlay(({
	element: document.getElementById("popup-map"),
	autoPan: true
}));
closer.onclick = function() {
	popup_map.setPosition(undefined);
	closer.blur();
	return false;
};
	  
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
				overlays: [popup_map],
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
document.getElementById('point_fixe_map').addEventListener('click', (event) => {
	console.log('points_to_map');
	map.removeLayer(wfsPFP1);
	map.removeLayer(wfsPFA1);
	if (document.getElementById('PFP1_leg').checked == true){
	map.addLayer(wfsPFP1);
	};
	if (document.getElementById('PFA1_leg').checked == true){
	map.addLayer(wfsPFA1);
	};
	
});


map.on('click', function(e) {
              
              var features = map.getFeaturesAtPixel(e.pixel);
			  
              if (features) {
				console.log(features)
                var coords = e.coordinate;
				var num_pf = features[0].N.num_pf;
				var E_CH1903 = features[0].N.E_CH1903;
				var N_CH1903 = features[0].N.N_CH1903;
				var E_CH1903plus = features[0].N.E_CH1903plus;
				var N_CH1903plus = features[0].N.N_CH1903plus;
				var E_RGF = features[0].N.E_RGF;
				var N_RGF = features[0].N.N_RGF;
				var E_NTF = features[0].N.E_NTF;
				var N_NTF = features[0].N.N_NTF;
				
				var description = '<h4><u>Coordonnées du points '+num_pf+'</u></h4>';
				if(document.getElementById('ch1903_proj_map').checked == true){
					description +='<p>Coordonnées projetées CH1903: ' + E_CH1903 + ' / '+N_CH1903 + '</p></br>';
				};
				if(document.getElementById('rgf_proj_map').checked == true){
					description +='<p>Coordonnées projetées RGF: ' + E_RGF + ' / '+N_RGF + '</p></br>';
				};
				if(document.getElementById('ntf_proj_map').checked == true){
					description +='<p>Coordonnées projetées NTF: ' + E_NTF + ' / '+N_NTF + '</p></br>';
				};
				
                content.innerHTML = description;
				console.log(coords);
				popup_map.setPosition(coords);
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
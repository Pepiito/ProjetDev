// HEIG-VD


//Chargement de vecteur de GeoServer en GeoJSON
var proxyUrl = "http://localhost/cgi-bin/proxy.cgi?url=";


	var pfp1Url = "http://localhost:8080/geoserver/cite/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=cite:PFP1&outputFormat=application%2Fjson";
	var encodedUrlpfp1 = encodeURIComponent(pfp1Url);
	var wfsPFP1 = new ol.layer.Vector({
		source: new ol.source.Vector({
			format: new ol.format.GeoJSON(),
			url: proxyUrl + encodedUrlpfp1
		}),
		style: new ol.style.Style({
			image: new ol.style.Icon({
				src: 'icon_map/PFP1.svg'
			})
		}),
	});
			

	var pfa1Url = "http://localhost:8080/geoserver/cite/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=cite:PFA1&outputFormat=application%2Fjson";
	var encodedUrlpfa1 = encodeURIComponent(pfa1Url);
	var wfsPFA1 = new ol.layer.Vector({
		source: new ol.source.Vector({
			format: new ol.format.GeoJSON(),
			
			url: proxyUrl + encodedUrlpfa1
		}),
		style: new ol.style.Style({
			image: new ol.style.Icon({
				src: 'icon_map/PFA1.svg'
			})
		}),
	});

	var wfsPtSession = new ol.layer.Vector({
		source: new ol.source.Vector({
			features: (new ol.format.GeoJSON()).readFeatures({"id": 1, "type": "Feature", "geometry": {"type": "Point", "coordinates": [741297.639895659, 5906129.20063417]}, "properties": {"E_NTF": 36.000, "E_RGF": 28.000, "N_NTF": 37.000, "N_RGF": 29.000, "X_NTF": 30.000, "Y_NTF": 31.000, "Z_NTF": 32.000, "h_NTF": 35.000, "num_pt": "1", "id_sess": null, "lat_NTF": 34.000000, "num_NTF": 38, "E_CH1903": 525731.170, "N_CH1903": 180593.680, "X_ETRS89": 4.000, "Y_ETRS89": 5.000, "Z_ETRS89": 6.000, "alt_NF02": 1587.050, "chantier": null, "h_ETRS89": 9.000, "id_aleat": 2, "long_NTF": 33.000000, "aleatoire": "'alet'", "alt_IGN69": 39.000, "alt_RAN95": 1587.000, "id_ptsess": 1, "lat_ETRS89": 8.000000, "long_ETRS89": 7.000000, "E_CH1903plus": 2525730.890, "N_CH1903plus": 1180594.340, "X_CH1903plus": 10.000, "Y_CH1903plus": 11.000, "Z_CH1903plus": 12.000, "h_CH1903plus": 15.000, "lat_CH1903plus": 14.000000, "long_CH1903plus": 13.000000}}),
		}),
		style: new ol.style.Style({
			image: new ol.style.Icon({
				src: 'icon_map/Pt_session.svg'
			})
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
map.addLayer(wfsPtSession);

map.on('click', function(e) {
              
              var features = map.getFeaturesAtPixel(e.pixel);
			  
              if (features) {
				console.log(features)
                var coords = e.coordinate;
				var num_pt = features[0].N.num_pt;
				var E_CH1903 = features[0].N.E_CH1903;
				var N_CH1903 = features[0].N.N_CH1903;
				var E_CH1903plus = features[0].N.E_CH1903plus;
				var N_CH1903plus = features[0].N.N_CH1903plus;
				var E_RGF = features[0].N.E_RGF;
				var N_RGF = features[0].N.N_RGF;
				var E_NTF = features[0].N.E_NTF;
				var N_NTF = features[0].N.N_NTF;
				var num_NTF = features[0].N.num_NTF;
				
				var long_ETRS89 = features[0].N.long_ETRS89;
				var lat_ETRS89 = features[0].N.lat_ETRS89;
				var long_CH1903plus = features[0].N.long_CH1903plus;
				var lat_CH1903plus = features[0].N.lat_CH1903plus;
				var long_RGF = features[0].N.long_RGF;
				var lat_RGF = features[0].N.lat_RGF;
				var long_NTF = features[0].N.long_NTF;
				var lat_NTF = features[0].N.lat_NTF;
				
				var X_ETRS89 = features[0].N.X_ETRS89;
				var Y_ETRS89 = features[0].N.Y_ETRS89;
				var Z_ETRS89 = features[0].N.Z_ETRS89;
				var X_CH1903plus = features[0].N.X_CH1903plus;
				var Y_CH1903plus = features[0].N.Y_CH1903plus;
				var Z_CH1903plus = features[0].N.Z_CH1903plus;
				var X_RGF = features[0].N.X_RGF;
				var Y_RGF = features[0].N.Y_RGF;
				var Z_RGF = features[0].N.Z_RGF;
				var X_NTF = features[0].N.X_NTF;
				var Y_NTF = features[0].N.Y_NTF;
				var Z_NTF = features[0].N.Z_NTF;
				
				var alt_NF02 = features[0].N.alt_NF02;
				var alt_RAN95 = features[0].N.alt_RAN95;
				var alt_IGN69 = features[0].N.alt_IGN69;
				
				var hbessel_map = features[0].N.h_CH1903plus;
				var hgrs80_map = features[0].N.h_ETRS89;
				
				var description = '<h4><u>Coordonnées du points '+num_pt+'</u></h4>';
				if(document.getElementById('ch1903_proj_map').checked == true || document.getElementById('ch1903plus_proj_map').checked == true || document.getElementById('rgf_proj_map').checked == true || document.getElementById('ntf_proj_map').checked == true){
					description+='<u>Coordonnées projetées [m]</u>';
					if(document.getElementById('ch1903_proj_map').checked == true){
						description +='<p>CH1903: ' + E_CH1903 + ' / '+N_CH1903 + '</p>';
					};
					if(document.getElementById('ch1903plus_proj_map').checked == true){
						description +='<p>CH1903+: ' + E_CH1903plus + ' / '+N_CH1903plus + '</p>';
					};
					if(document.getElementById('ntf_proj_map').checked == true){
						description +='<p>NTF: ' + E_NTF + ' / '+N_NTF + '</p>';
					};
					if(document.getElementById('rgf_proj_map').checked == true){
						description +='<p>RGF: ' + E_RGF + ' / '+N_RGF + '   Lambert '+num_NTF+'</p>';
					};
				};
				if(document.getElementById('etrs89_geog_map').checked == true || document.getElementById('ch1903_geog_map').checked == true || document.getElementById('ntf_geog_map').checked == true){
					description+='<u>Coordonnées géographiques [degrés]</u>';
					if(document.getElementById('etrs89_geog_map').checked == true){
						description +='<p>ETRS89: ' + long_ETRS89 + ' / '+lat_ETRS89 + '</p>';
					};
					if(document.getElementById('ch1903_geog_map').checked == true){
						description +='<p>CH1903+: ' + long_CH1903plus + ' / '+lat_CH1903plus + '</p>';
					};
					if(document.getElementById('ntf_geog_map').checked == true){
						description +='<p>NTF: ' + long_NTF + ' / '+lat_NTF + '</p>';
					};
					// if(document.getElementById('rgf_geog_map').checked == true){
						// description +='<p>RGF: ' + long_RGF + ' / '+lat_RGF + '</p>';
					// };
				};
				if(document.getElementById('etrs89_cart_map').checked == true || document.getElementById('ch1903_cart_map').checked == true || document.getElementById('ntf_cart_map').checked == true){
					description+='<u>Coordonnées cartésiennes [m]</u>';
					if(document.getElementById('etrs89_cart_map').checked == true){
						description +='<p>ETRS89: ' + X_ETRS89 + ' / '+ Y_ETRS89 +' / '+ Z_ETRS89 +  '</p>';
					};
					if(document.getElementById('ch1903_cart_map').checked == true){
						description +='<p>CH1903+: ' + X_CH1903plus + ' / '+Y_CH1903plus + ' / '+Z_CH1903plus + '</p>';
					};
					if(document.getElementById('ntf_cart_map').checked == true){
						description +='<p>NTF: ' + X_NTF + ' / '+Y_NTF + ' / '+Z_NTF + '</p>';
					};
					// if(document.getElementById('rgf_cart_map').checked == true){
						// description +='<p>RGF: ' + X_RGF + ' / '+Y_RGF + ' / '+Z_RGF + '</p>';
					// };
				};
				if(document.getElementById('ign69_map').checked == true || document.getElementById('ran95_map').checked == true || document.getElementById('nf02_map').checked == true){
					description+='<u>Altitudes [m]</u>';
					if(document.getElementById('nf02_map').checked == true){
						description +='<p>NF02: ' + alt_NF02 + '</p>';
					};
					if(document.getElementById('ran95_map').checked == true){
						description +='<p>RAN95: ' + alt_RAN95 + '</p>';
					};
					if(document.getElementById('ign69_map').checked == true){
						description +='<p>IGN69: ' + alt_IGN69 + '</p>';
					};
					
				};
				if(document.getElementById('hgrs80_map').checked == true || document.getElementById('hbessel_map').checked == true){
					description+='<u>Hauteur [m]</u>';
					if(document.getElementById('hbessel_map').checked == true){
						description +='<p>Ellipsoïde de Bessel: ' + hbessel_map + '</p>';
					};
					if(document.getElementById('hgrs80_map').checked == true){
						description +='<p>Ellipsoïde de GRS80: ' + hgrs80_map + '</p>';
					};

					
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
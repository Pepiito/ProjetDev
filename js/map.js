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
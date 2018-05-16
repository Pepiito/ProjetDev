/*

Fichier contenant tous les écouteurs d'évènements et les fonctions à éxécuter,
ainsi que les déclarations de variables.
Les fontions sont définies dans le main.js

*/

/*
* Variables à déclarer
*/

var allVar = new Array(); // stocke tous les éléments du DOM utiles
allVar['type-transfo-selected'] = 'point';

var inputs = document.getElementById('pop_body').getElementsByTagName('input');
var selects =  document.getElementById('pop_body').getElementsByTagName('select');

var allHTMLElem = Array.from(inputs).concat(Array.from(selects));

// Eléments du DOM dont l'affichage dépend du type de coordonnée choisi
getAllElementsByClass(['cart', 'geog', 'proj'], 'typeCoord');

// Eléments du DOM dont l'affichage dépend du système plani choisi
getAllElementsByClass(['ETRS89', 'CH1903', 'CH1903+', 'RGF93', 'NTF'], 'systemePlani');

getAllElementsByClass(['proj-alti', 'proj-hauteur', 'geog-alti', 'geog-hauteur'], 'typeAlti')
// Inputs de coordonnées

registerAllData();


var points_fixes_title = document.getElementById('points_fixes_title');
var sys_plani_title = document.getElementById('sys_plani_title');
var sys_alti_title = document.getElementById('sys_alti_title');

/*
* Fonctions à éxécuter au lancement
*/

['in', 'out'].forEach( function (inout) {
  ['point', 'file'].forEach( function (data) {

    //initialisation en coordonnées géographiques
    adaptDisplay(allVar.typeCoord, 'geog', data, inout);
    adaptDisplay(allVar.systemePlani, 'RGF93', data, inout);
    hideAlti(inout);
  });
});


/*
Ecouteurs
*/

['in', 'out'].forEach( function (inout) {
  document.getElementById('type-alti-altitude-point-' + inout).addEventListener('change', (event) => {
    adaptDisplay(allVar.typeAlti, document.getElementById('type-coord-point-' + inout).value + '-alti', 'point', inout);
  });
  document.getElementById('type-alti-hauteur-point-' + inout).addEventListener('change', (event) => {
    adaptDisplay(allVar.typeAlti, document.getElementById('type-coord-point-' + inout).value + '-hauteur', 'point', inout);
  });

  ['point', 'file'].forEach( function (data) {

    document.getElementById('type-coord-' + data + '-' + inout).addEventListener('change', (event) => {
      adaptDisplay(allVar.typeCoord, event.target.value, data, inout);
      hideAlti(inout);
    }, false);
    document.getElementById('systeme-plani-' + data + '-' + inout).addEventListener('change', (event) => {
      adaptDisplay(allVar.systemePlani, event.target.value, data, inout);
      hideAlti(inout);
    })

  });
});

document.getElementById('systeme-plani-point-in').addEventListener('change', (event) => {
  var eta = document.getElementById('coord-proj-eta-point-in');
  var xi = document.getElementById('coord-proj-xi-point-in');
  console.log(event.target.value);
  if (event.target.value == 'NTF') {
    eta.disabled = true;
    xi.disabled = true;
  }
  else {
    eta.disabled = false;
    xi.disabled = false;
  }
}, false);

document.getElementById('calcul-point').addEventListener('click', (event) => {
  processTransformation('point', false)
}, false);

document.getElementById("ajout-carte-point").addEventListener('click', (event) => {
  processTransformation('point', true)
});

document.getElementById('dl-file').addEventListener('click', (event) => {
  processTransformation('file', false);
}, false);

document.getElementById("ajout-carte-file").addEventListener('click', (event) => {
  processTransformation('file', true);
});


document.addEventListener('keydown', (event) => {
  if (event.key == 'Escape') {
    if (document.getElementById('loader-filtre').style.visibility == "visible") {
      endLoading();
    }
  }
});

document.getElementById('input-file-in').addEventListener('change', showNamesFiles, false);

document.getElementById('reset-input-file-in').addEventListener('click', (event) => {
  document.getElementById('input-file-in').value = "";
  document.getElementById('dropped-files').value = "";
  document.getElementById('name-import-file-in').value = "Déposer un fichier ici...";
}, false)

function showNamesFiles() {
  var val = "";
  files().forEach( function (file) { val += file.name + " / "});
  document.getElementById('name-import-file-in').value = val.substr(0, val.length-3);
}

document.getElementById('fichier-de-depart').addEventListener('dragover', function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.dataTransfer.dropEffect = 'copy';
  document.getElementById('name-import-file-in').style.borderStyle = "dashed";
})

document.getElementById('fichier-de-depart').addEventListener('drop', function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.dataTransfer.dropEffect = 'copy';
  var files = e.dataTransfer.files;

  document.getElementById('dropped-files').files = files;
  document.getElementById('name-import-file-in').style.borderStyle = "none";

  showNamesFiles();

});

window.ondragover = function () {
  document.getElementById('name-import-file-in').style.borderStyle = "dashed";
}

document.getElementById('nom-export-file-out').addEventListener('input', (event) => {
  event.target.value = event.target.value.replace(/[\\/:"*?<>|\.]+/, "_");
})

window.ondragleave = function () {
  document.getElementById('name-import-file-in').style.borderStyle = "none";
}

document.getElementById('head_point').addEventListener('click', (event) => {
	toggleHead('fichier');
  allVar['type-transfo-selected'] = 'point';
}, false);

document.getElementById('head_fichier').addEventListener('click', (event) => {
	toggleHead('point')
  allVar['type-transfo-selected'] = 'file';
}, false);

document.getElementById('head_fichier').onmouseover = function () {styleHead("+");}
document.getElementById('head_point').onmouseover = function () {styleHead("-");}

function styleHead(sens) {
  var head = document.getElementById('pop_header')
  head.style.backgroundColor = "#df9999";
  head.style.boxShadow = "inset "+sens+"300px 0 150px 2px #f5f5f5";
}

document.getElementById('pop_header').addEventListener('mouseleave', (event) => {
  event.target.style.backgroundColor = "#f5f5f5";
}, false);

document.getElementById("error-ok").addEventListener('click', endLoading, false);

Array.from(document.getElementById('loader-filtre').getElementsByClassName('close')).forEach (function (close) {
  close.addEventListener('click', endLoading, false);
});


//HEIG-VD Affichage de la légende de la carte
document.getElementById('points_fixes_title').addEventListener('click', (event) => {
	var style = document.getElementById('point_fixe_map');
	var symbol = document.getElementById('symbol_list_pts');

  displayOptionCarte(style, symbol);
}, false);

document.getElementById('sys_plani_title').addEventListener('click', (event) => {
	var style = document.getElementById('sys_plani_leg');
	var symbol = document.getElementById('symbol_list_plani');
	displayOptionCarte(style, symbol);

}, false);

document.getElementById('sys_alti_title').addEventListener('click', (event) => {
	var style = document.getElementById('sys_alti_leg');
	var symbol = document.getElementById('symbol_list_alti');
	displayOptionCarte(style, symbol);

}, false);

function displayOptionCarte(style, symbol) {
  if(style.style.opacity != 1){
    style.style.visibility = "visible";
		style.style.opacity = 1;
    style.style.maxHeight = "1000px";
		symbol.style.transform = 'rotate(-90deg)';
	}else{
    style.style.visibility = "hidden";
		style.style.opacity = 0;
    style.style.maxHeight = "0px";
		symbol.style.transform = 'rotate(180deg)';
  }
}

points_fixes_title.onmousemove = function (e) {
    var x = e.clientX,
        y = e.clientY;
    document.getElementById('span_pts').style.top = (y) + 'px';
    document.getElementById('span_pts').style.left = (x+10) + 'px';
};
sys_plani_title.onmousemove = function (e) {
    var x = e.clientX,
        y = e.clientY;
    document.getElementById('span_plani').style.top = (y) + 'px';
    document.getElementById('span_plani').style.left = (x+10) + 'px';
};
sys_alti_title.onmousemove = function (e) {
    var x = e.clientX,
        y = e.clientY;
    document.getElementById('span_alti').style.top = (y) + 'px';
    document.getElementById('span_alti').style.left = (x+10) + 'px';
};

document.getElementsByClassName('home')[0].onclick = goToHomePage; // bouton home de la page principale.

if(connexion_ut){
	document.getElementById('liste_chantier').addEventListener('change', (event) => {
		var value = document.getElementById('liste_chantier').value;
		console.log(value)
		source.clear();
		sendAjax(change_chantier, "./php/vue/postgis_change_chantier.php", "value_chantier=" + value);
	}, false)

	document.getElementById('button_gest').addEventListener('click', (event) => {
	window.open('./gestion-compte.php?id_sess='+id_sess);
	}, false);
};

function change_chantier(reponse){
	var geojson_ptsess = reponse;
	console.log('changement de chantier')
	console.log(geojson_ptsess)
	if (isPHPErrorType(reponse)) {
    console.log("Erreur sur la réponse AJAX :\n" + reponse);
    showError(reponse);
	}
	else if (isErrorType(reponse)) {
		raiseError(reponse);
	}else{

	var features_ptsess2 = new ol.format.GeoJSON().readFeatures(geojson_ptsess);
	source.addFeatures(features_ptsess2)
	console.log(features_ptsess2)
	}
};

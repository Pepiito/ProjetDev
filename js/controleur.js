/*

Fichier contenant tous les écouteurs d'évènements et les fonctions à éxécuter,
ainsi que les déclarations de variables.
Les fontions sont définies dans le main.js

*/

/*
Variables à déclarer
*/

window.addEventListener('load', (event) => {

  /*
  * variables à déclarer
  */

  window.allVar = new Array(); // stocke tous les éléments du DOM utiles
  allVar['type-transfo-selected'] = 'point';

  var inputs = document.getElementById('pop_body').getElementsByTagName('input');
  var selects =  document.getElementById('pop_body').getElementsByTagName('select');

  window.allHTMLElem = Array.from(inputs).concat(Array.from(selects))

  // Eléments du DOM dont l'affichage dépend du type de coordonnée choisi
  getAllElementsByClass(['cart', 'geog', 'proj'], 'typeCoord');

  // Eléments du DOM dont l'affichage dépend du système plani choisi
  getAllElementsByClass(['ETRS89', 'CH1903', 'CH1903+', 'RGF93', 'NTF'], 'systemePlani');

  getAllElementsByClass(['proj-alti', 'proj-hauteur', 'geog-alti', 'geog-hauteur'], 'typeAlti')
  // Inputs de coordonnées

  registerAllData();

  /*
  * fonction à éxécuter au lancement
  */

  ['in', 'out'].forEach( function (inout) {
    ['point', 'file'].forEach( function (data) {

      //initialisation en coordonnées géographiques
      adaptDisplay(allVar.typeCoord, 'geog', data, inout);
      adaptDisplay(allVar.systemePlani, 'RGF93', data, inout);
      hideAlti(inout);
    });
  });

}, false);

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

document.getElementById('calcul-point').addEventListener('click', (event) => {
  var errordata = validAndSetData();
  if (errordata != 'Success') raiseError("Erreur 205 : Les informations suivantes sont manquantes pour la compilation : ", errordata);
}, false);

document.getElementById('dl-file').addEventListener('click', (event) => {
  document.getElementById("input-file-in").files.forEach( function (file) {
    getFileContent(file);
  });
}, false);

document.getElementById("ajout-carte-file").addEventListener('click', (event) => {
  document.getElementById("input-file-in").files.forEach( function (file) {
    getFileContent(file, true);
  });
});

document.getElementById("ajout-carte-point").addEventListener('click', (event) => {
  var errordata = validAndSetData(true);
  if (errordata != 'Success') raiseError("Erreur 205 : Les informations suivantes sont manquantes pour la compilation : ", errordata);

});

document.getElementById('head_trans_coord').addEventListener('click', (event) => {
	toggleHead('left');
  allVar['type-transfo-selected'] = 'point';
}, false);

document.getElementById('head_trans_fichier').addEventListener('click', (event) => {
	toggleHead('right');
  allVar['type-transfo-selected'] = 'file';
}, false);

document.getElementById("error-ok").addEventListener('click', endLoading, false);

Array.from(document.getElementById('loader-filtre').getElementsByClassName('close')).forEach (function (close) {
  close.addEventListener('click', endLoading, false);
});

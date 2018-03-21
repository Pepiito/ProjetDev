/*

Fichier contenant tous les écouteurs d'évènements et les fonctions à éxécuter,
ainsi que les déclarations de variables.
Les fontions sont définies dans le main.js

*/

/*
Variables à déclarer
*/

window.addEventListener('load', (event) => {

  // variables à déclarer

  var input_fieldset = document.getElementById('fichier-de-depart');
  var output_fieldset = document.getElementById('fichier-d-arrivee');

  window.projection_in_params = input_fieldset.getElementsByClassName('projection');
  window.cartesien_in_params = input_fieldset.getElementsByClassName('cartesien');
  window.geog_in_params = input_fieldset.getElementsByClassName('geographique');

  window.projection_out_params = output_fieldset.getElementsByClassName('projection');
  window.cartesien_out_params = output_fieldset.getElementsByClassName('cartesien');
  window.geog_out_params = output_fieldset.getElementsByClassName('geographique');


  window.alti_suisse = document.getElementsByClassName('Alt_suisse');
  window.alti_francais = document.getElementsByClassName('Alt_francais');
  window.alti_suisse2 = document.getElementsByClassName('Alt_suisse2');
  window.alti_francais2 = document.getElementsByClassName('Alt_francais2');

  window.actionType = new Array();
  ['Cart', 'Geog', 'Projetées'].forEach( function (type) {
    window.actionType[type.substr(0,4)] = new Array();
    ['point', 'file'].forEach( function (data) {
      window.actionType[type.substr(0,4)][data] = new Array();
      ['in', 'out'].forEach( function (action) {
        console.log(type +'-'+ data + '-' + action);
        window.actionType[type.substr(0,4)][data][action] = document.getElementsByClassName(type +'-'+ data + '-' + action);
      });
    });
  });

  ['in', 'out'].forEach( function (action) {
    ['point', 'file'].forEach( function (data) {

      //initialisation à coordonnées Projetées
      adaptInputFileParams('Projetées', actionType['Cart'][data][action], actionType['Geog'][data][action], actionType['Proj'][data][action]);
    });
  });

  // fonction à éxécuter au lancement

  adaptInputFileParams("Projetées", cartesien_in_params, geog_in_params, projection_in_params);

  adaptInputFileParams("Projetées", cartesien_out_params, geog_out_params, projection_out_params);


  disable(alti_suisse);
  disable(alti_francais);
  document.getElementById("altimetrieChoice_alti").disabled = true;
  document.getElementById("type_altimetre_projetee_coord").style.color = "gray";
  document.getElementById("type_altimetre_projetee_coord").style.borderColor = "gray";
  document.getElementById("altimetrieChoice_hauteur").checked = true;
  document.getElementById('sys_alti_depart_coord').style.display="none";

  document.getElementById("altimetrieChoice_alti2").disabled = true;
  document.getElementById("type_altimetre_projetee_coord2").style.color = "gray";
  document.getElementById("type_altimetre_projetee_coord2").style.borderColor = "gray";
  document.getElementById("altimetrieChoice_hauteur2").checked = true;
  document.getElementById('sys_alti_arrivee_coord').style.display="none";
}, false);

/*
Ecouteurs
*/

document.addEventListener('keydown', (event) => {
  console.log(event.key);
}, false);

['in', 'out'].forEach( function (action) {
  ['point', 'file'].forEach( function (data) {

    document.getElementById('type-coord-' + data + '-' + action).addEventListener('change', (event) => {
      console.log(actionType);
      adaptInputFileParams(event.target.value, actionType['Cart'][data][action], actionType['Geog'][data][action], actionType['Proj'][data][action]);
    }, false);

  });
});

document.getElementById('head1').addEventListener('click', (event) => {
	document.getElementById('trans_coord').style.display="block";
    document.getElementById('trans_fichier').style.display="none";
    document.getElementById('head1').style.borderWidth="0px 1px 0px 0px";
    document.getElementById('head2').style.borderWidth="0px 0px 2px 1px";
}, false);

document.getElementById('head2').addEventListener('click', (event) => {
	document.getElementById('trans_coord').style.display="none";
    document.getElementById('trans_fichier').style.display="block";
    document.getElementById('head1').style.borderWidth="0px 1px 2px 0px";
    document.getElementById('head2').style.borderWidth="0px 0px 0px 1px";
}, false);

document.getElementById('altimetrieChoice_alti').addEventListener('click', (event) => {
	document.getElementById('sys_alti_depart_coord').style.display="block";
	document.getElementById("label_input_alti_projetee_coord").innerHTML = "Altitude [m]";
}, false);
document.getElementById('altimetrieChoice_hauteur').addEventListener('click', (event) => {
	document.getElementById('sys_alti_depart_coord').style.display="none";
	document.getElementById("label_input_alti_projetee_coord").innerHTML = "Hauteur [m]";
}, false);

document.getElementById('systeme_plani_coord').addEventListener('change', (event) => {
  console.log(event.target.value);
  if(event.target.value=="ETRS89"){
	document.getElementById("altimetrieChoice_alti").disabled = true;
	document.getElementById("type_altimetre_projetee_coord").style.color = "gray";
	document.getElementById("type_altimetre_projetee_coord").style.borderColor = "gray";
	document.getElementById("altimetrieChoice_hauteur").checked = true;
	document.getElementById('sys_alti_depart_coord').style.display="none";
	document.getElementById("label_input_alti_projetee_coord").innerHTML = "Hauteur [m]";
  }else{
	document.getElementById("altimetrieChoice_alti").disabled = false;
	document.getElementById("type_altimetre_projetee_coord").style.color = "black";
	document.getElementById("type_altimetre_projetee_coord").style.borderColor = "black";
  };
  switch(event.target.value) {
    case "ETRS89":
      disable(alti_suisse);
      disable(alti_francais);
	  document.getElementById("altimetrie_RAN95_coord").selected = false;
	  document.getElementById("altimetrie_IGN69_coord").selected = false;

      break;
	case "CH1903+":
      enable(alti_suisse);
      disable(alti_francais);
	  document.getElementById("altimetrie_RAN95_coord").selected = true;
	  document.getElementById("altimetrie_IGN69_coord").selected = false;
      break;
	case "CH1903":
      enable(alti_suisse);
      disable(alti_francais);
	  document.getElementById("altimetrie_RAN95_coord").selected = true;
	  document.getElementById("altimetrie_IGN69_coord").selected = false;
      break;
	case "RGF93":
      disable(alti_suisse);
      enable(alti_francais);
	  document.getElementById("altimetrie_RAN95_coord").selected = false;
	  document.getElementById("altimetrie_IGN69_coord").selected = true;
      break;
    case "NTF":
      disable(alti_suisse);
      enable(alti_francais);
	  document.getElementById("altimetrie_RAN95_coord").selected = false;
	  document.getElementById("altimetrie_IGN69_coord").selected = true;
      break;
  };
}, false);


// Fonctions pour l'affichage dans la popup de transformation de coordonnées pour le système d'arrivée
// ---------------------------------------------------------------------------------------------------

document.getElementById('altimetrieChoice_alti2').addEventListener('click', (event) => {
	document.getElementById('sys_alti_arrivee_coord').style.display="block";
	document.getElementById("label_input_alti_projetee_coord2").innerHTML = "Altitude [m]";
}, false);
document.getElementById('altimetrieChoice_hauteur2').addEventListener('click', (event) => {
	document.getElementById('sys_alti_arrivee_coord').style.display="none";
	document.getElementById("label_input_alti_projetee_coord2").innerHTML = "Hauteur [m]";
}, false);

document.getElementById('systeme_plani_coord2').addEventListener('change', (event) => {
  console.log(event.target.value);
  if(event.target.value=="ETRS89"){
	document.getElementById("altimetrieChoice_alti2").disabled = true;
	document.getElementById("type_altimetre_projetee_coord2").style.color = "gray";
	document.getElementById("type_altimetre_projetee_coord2").style.borderColor = "gray";
	document.getElementById("altimetrieChoice_hauteur2").checked = true;
	document.getElementById('sys_alti_arrivee_coord').style.display="none";
	document.getElementById("label_input_alti_projetee_coord2").innerHTML = "Hauteur [m]";
  }else{
	document.getElementById("altimetrieChoice_alti2").disabled = false;
	document.getElementById("type_altimetre_projetee_coord2").style.color = "black";
	document.getElementById("type_altimetre_projetee_coord2").style.borderColor = "black";
  };
  switch(event.target.value) {
    case "ETRS89":
      disable(alti_suisse2);
      disable(alti_francais2);
	  document.getElementById("altimetrie_RAN95_coord2").selected = false;
	  document.getElementById("altimetrie_IGN69_coord2").selected = false;

      break;
	case "CH1903+":
      enable(alti_suisse2);
      disable(alti_francais2);
	  document.getElementById("altimetrie_RAN95_coord2").selected = true;
	  document.getElementById("altimetrie_IGN69_coord2").selected = false;
      break;
	case "CH1903":
      enable(alti_suisse2);
      disable(alti_francais2);
	  document.getElementById("altimetrie_RAN95_coord2").selected = true;
	  document.getElementById("altimetrie_IGN69_coord2").selected = false;
      break;
	case "RGF93":
      disable(alti_suisse2);
      enable(alti_francais2);
	  document.getElementById("altimetrie_RAN95_coord2").selected = false;
	  document.getElementById("altimetrie_IGN69_coord2").selected = true;
      break;
    case "NTF":
      disable(alti_suisse2);
      enable(alti_francais2);
	  document.getElementById("altimetrie_RAN95_coord2").selected = false;
	  document.getElementById("altimetrie_IGN69_coord2").selected = true;
      break;
  };
}, false);

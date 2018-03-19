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

  window.projection_params = document.getElementsByClassName('projection');
  window.cartesien_params = document.getElementsByClassName('cartesien');
  window.geog_params = document.getElementsByClassName('geographique');

  // fonction à éxécuter au lancement

  disable(cartesien_params);
  disable(geog_params);
  enable(projection_params);
}, false);

/*
Ecouteurs
*/

document.addEventListener('keydown', (event) => {
  console.log(event.key);
}, false);

document.getElementById('type_coord_transfo_fichier').addEventListener('change', (event) => {
  console.log(event.target.value);
  switch(event.target.value) {
    case "Projetées":
      disable(cartesien_params);
      disable(geog_params);
      enable(projection_params);
      break;
    case "Geog":
      disable(cartesien_params);
      enable(geog_params);
      disable(projection_params);
      break;
    case "Cart":
      enable(cartesien_params);
      disable(geog_params);
      disable(projection_params);
      break;
  };
}, false);

document.getElementById('type_coord').addEventListener('change', (event)=> {
	console.log(event.target.value);
	switch(event.target.value){
		case "Projetées":
			Projetées.style.display="block";
			Geog.style.display="none";
			Cart.style.display="none";
			break;
		case "Geog":
			Projetées.style.display="none";
			Geog.style.display="block";
			Cart.style.display="none";
			break;
		case "Cart":
			Projetées.style.display="none";
			Geog.style.display="none";
			Cart.style.display="block";
			break;
	};
}, false);
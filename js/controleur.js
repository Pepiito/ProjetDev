/*

Fichier contenant tous les écouteurs d'évènements et les fonctions à éxécuter,
ainsi que les déclarations de variables.
Les fontions sont définies dans le main.js

*/

/*
Variables à déclarer
*/

var boutons = Array.from(document.getElementsByTagName('button')); // Liste des boutons au format Array


/*
Ecouteurs
*/

boutons.forEach( function (bouton) {
  bouton.addEventListener('click', (event) => {
    Test();
  }, false);
});

document.addEventListener('keydown', (event) => {
  console.log(event.key);
}, false);

/*

Fichier contenant les fonctions.
Les écouteurs d'évènements sont contenues dans le controleur.js

*/

function Test() {

  console.log("Working");

}


function Ajax(callback, file, data) {
  /*
  Requete AJAX pour ouvrir le fichier sélectionné. Prend en paramètrre :
   * function callback, exécuté avec le paramètre responsetext une fois la requete AJAX effectué correctement
   * file, fichier de destination de la requete
   * data, données envoyés avec la requête (optionnel)
  */

  data = data || "";

  var ajax = new XMLHttpRequest();
  ajax.open('POST', file, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  ajax.addEventListener('readystatechange', function(e) {

    // test du statut de retour de la requête AJAX
    if (ajax.readyState == 4 && (ajax.status == 200 || ajax.status == 0)) {
        // récupération du contenu du fichier et envoi de la fonction de callback
        callback(ajax.responseText);

    }
  });
  ajax.send(data);
}


function sendDataToModel(data) {

  Ajax(receiveDataFromModel, "transfo_coord.php", data)
}

function receiveDataFromModel(reponse) {
  if (isPHPErrorType(reponse)) {
    console.log("Erreur sur la réponse AJAX :\n" + reponse);
  }
  else if (isErrorType(reponse)) {
    // Instruction en cas d'erreur du modèle
  }
  else {
    // Instruction si tout va bien
  }
}

function isErrorType(string) {
  return string.substr(0,5) == "Error"
}

function isPHPErrorType(string) {
  return /<b>/.test(string)
}


function modify(htmlCollection, display) {
  Array.from(htmlCollection).forEach( function(htmlelement) {
    htmlelement.style = "display:"+display;
  });
}
function enable(htmlCollection) {
  modify(htmlCollection, "block");
}
function disable(htmlCollection) {
  modify(htmlCollection, "none");
}

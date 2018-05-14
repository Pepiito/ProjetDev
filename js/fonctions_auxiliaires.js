function sendAjax(callback, file, data, isFormData) {
  /**
  * Requete AJAX pour ouvrir le fichier sélectionné. Prend en paramètrre :
  * function callback, exécuté avec le paramètre responsetext une fois la requete AJAX effectué correctement
  * file, fichier de destination de la requete
  * data, données envoyés avec la requête (optionnel)
  **/

  data = data || "";
  isFormData = isFormData || false;

  window.ajax = new XMLHttpRequest();
  ajax.open('POST', file, true);

  if (!isFormData) ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  ajax.addEventListener('readystatechange', function(e) {

    // test du statut de retour de la requête AJAX
    if (ajax.readyState == 4 && (ajax.status == 200 || ajax.status == 0)) {
        // récupération du contenu du fichier et envoi de la fonction de callback
        return callback(ajax.responseText);

    }
  });
  ajax.send(data);
}

function sendDataToModel() {
  /*
  Envoie les données au modèle (fichier transfo_coord.php) par une requête AJAX.
  Une fois la requête terminée, la fonction receiveDataFromModel est éxécuté.
  Les données à envoyer sont comprises dans la supervariable allVar.
  */

  data = 'formatage=' + allVar['head_data']['in'] + '-' + allVar['head_data']['out'] + allVar['data'];
  console.log(data);
  return sendAjax(receiveDataFromModel, "./php/modele/transfo_coord.php", data)

}

function addPointsToMap() {
  // Ajoute les points recus par le modèle à la carte.

  source.clear();
  var geojson_ptsess = coordonnees['geojson_ptsess'];
  var features_ptsess3 = new ol.format.GeoJSON().readFeatures(geojson_ptsess);

  // Ajout du point à la carte
  source.addFeatures(features_ptsess3);

  var date = getDate();
  var select =  document.getElementById('liste_chantier').innerHTML;
  if (select.indexOf(date) == -1) {
	  console.log('salut')
    list_chantier = "<option value=" + date + "selected>" + date + "</option>" + select;
	document.getElementById('liste_chantier').innerHTML = list_chantier;
  }
}

function getDate() {
    // Renvoie la date du jour au format aaaa-mm-jj
    var today = new Date();

    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) dd = '0'+dd;

    if(mm<10) mm = '0'+mm;

    return yyyy + '-' + mm + '-' + dd;
  }

function createline(array, largeur_colonne, line_start) {
  /**
  * Crée un ligne de fichier à partir du tableau array.
  * :array: tableau d'éléments à ajouter à la ligne (dans l'ordre)
  * :largeur_colonne: espacement entre 2 éléments. Valeur par défaut
  * :line_start: Début de ligne. " " par défaut.
  **/
  var line = line_start || " ";
  var l_colonne = largeur_colonne || 16;

  // fichier en sortie attendu : .csv
  if (allVar['file']['out']['extension'] == 'csv') {
    return line + array.join(";") + '\n';
  }

  for (i in array) { // cas .txt
    if (array[i] === false) continue;
    line += pad(array[i], l_colonne, " ", true);
  }
  line += "\r\n";
  return line;
}

function pad(n, width, z, bafter) {
  /*
  Renvoie un string comprenant l'entier ou string n précédé ou suivi d'un nombre de 0 (ou z si renseigné) de longueur width.
  bafter: bool si false : fill avant. si true: fill après
  */

  z = z || '0';
  n = n + '';
  bafter = bafter || false;
  return n.length >= width ? n : bafter ? n + new Array(width - n.length + 1).join(z) : new Array(width - n.length + 1).join(z) + n ;
}

function isErrorType(string) {
  // Détecte si le string contient une erreur levé pendant les transformations du modèle
  return /Err[euo]+r /.test(string) || string == "";
}

function isPHPErrorType(string) {
  // Détecte si le string contient une erreur PHP
  return /<b>/.test(string)
}

function setCoordValue(type, coord, value, unite) {
  // Met la valeur dans l'élément du DOM qui lui correspond
  unite = unite || 'none';
  value = radToAngle(value, unite); // Changement d'unité si renseigné

  document.getElementById('coord-' + type + '-' + coord + '-point-out').value = value;
}

function angleToRad(alpha, nb) {
  /*
  Permet le passage de n'importe quelle valeur réelle à un angle en radian compris entre -pi et pi
  */
  alpha = parseFloat(alpha);
  while (!(alpha >= 0 && alpha < nb)) {
    if (alpha < 0) alpha += nb;
    else if (alpha >= nb) alpha -= nb;
  }
  return alpha*2*Math.PI/nb;
}

function radToAngle(alpha, unite) {
  /*
  Permet le passage d'un angle en radians dans le système d'unité décrit.
  unite : deg (degré), grad (gradient), rad (radians) ou none (pas de transformation).
  Si  un transformation est éxécuté, le résultat est tronqué à la 9ème décimale.
  */

  alpha = parseFloat(alpha);
  if (unite == "deg") nb = 360;
  else if (unite == "grad") nb = 400;
  else if (unite == "rad") nb = 2*Math.PI;
  else return alpha;
  return precisionRound(alpha*nb/(2*Math.PI), 9);
}

function precisionRound(number, precision) {
  // Tranque number à la décimale precision
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}

function enable(htmlCollection) { // rend visible les éléments HTML de htmlCollection
  modify(htmlCollection, true);
}

function disable(htmlCollection) { // cache les éléments HTML de htmlCollection
  modify(htmlCollection, false);
}

function modify(htmlCollection, display) {
  /*
  Appelé pour afficher ou cacher les éléments HTMLCollection du DOM,elle permet aussi, dans le cas
  où ces éléments sont des options d'un select, de sélectionner un des éléments à afficher et de
  désélectionner les autres.
  Notamment, cette fonction permet d'adapter le choix par défaut de la projection ou du système alti
  lié au système de référence choisi.
  */

  if (htmlCollection instanceof HTMLCollection) { // liste d'élement
  var first = true;
  var id_select = "";

    Array.from(htmlCollection).forEach( function(htmlelement) { // parcours des éléments HTML
      if (htmlelement.tagName == 'OPTION') { // cas d'une option d'un select

        // cas ou le select correspondant n'a pas encore été traité : on réinitialise le premier élément
        if (htmlelement.parentNode.parentNode.id != id_select) {
          first = true;
          id_select = htmlelement.parentNode.parentNode.id;
        }
        htmlelement.disabled = !display; // Affichage de l'élément
        if (display && first) { // cas où on veut afficher l'élément et que c'est lepremier du select
          htmlelement.selected = true; // on le séléctionne
          first = false;
        }
        else htmlelement.selected = false; // sinon on le désélectionne
      }
      else htmlelement.style = "display:" + (display ? "block" : "none !important");
    });
  }
  else { // unique element
    if (htmlCollection.tagName == 'OPTION') {
      htmlCollection.disabled = !display;
      htmlelement.selected = true; // on l'affiche
    }
    else htmlCollection.style = "display:" + (display ? "block" : "none !important");
  }
}

function inArray(elem, arr) {
  return (arr.indexOf(elem) != -1);
}

function addToData(key, value) {
  /**
  * Ajoute un élément dans la superVariable data. Si cet élément existe déjà, ne fait rien.
  * Renseigne également l'en-tête.
  * :key: string descriptif de la valeur
  * :value: valeur à enregistrer
  **/

  if (key[0] == '_') { // Paramètres de sortie. Renseigné dans l'en-tête partie 'out'
    if (!inArray(key[1], allVar['head_data']['out'])) {
      allVar['head_data']['out'] += key[1];
    }
    else return;
  }
  else { // Sinon, paramètre d'entré ( partie 'in').
    if (!inArray(key, allVar['head_data']['in'])) {
      allVar['head_data']['in'] += key;
    }
    else return;
  }

  allVar['data'] += '&' + key + '=' + value; // Si tout s'est bien passé, ajout ela valeur.
}

function validateCoord(coord, unite) {
  /**
  Valide la coordonnée en entrée et la transforme si nécessaire en radian.
  La coordonnée doit être un nombre, transformable en flottant.
  Revnoie la coordonnée si elle est valide et false sinon
  **/

  unite = unite || 'none';

  // Traitement dms
  if (coord === "0") return "0"; // cas particulier pour "0" où les tests considère 0 comme invalide
  if (!coord) return false;
  coord = coord.replace(',', '.').replace('/\D[^\.]/', '') // Garde uniquement nombre et points. Virgules tranformés en points

  if (isNaN(parseFloat(coord))) { // test du passage en flottant
    raiseError("Erreur 206: Le paramètre suivant n'est pas valide : ", coord);
    return false;
  }

  // change d'unité si nécessaire
  if (unite == 'grad') return angleToRad(coord, 400);
  else if (unite == 'deg') return angleToRad(coord, 360);
  else if (unite == 'rad') return angleToRad(coord, 2*Math.PI);
  else return coord + "";
}

function raiseError(code, complement) {
  /*
  Est appelé lorsqu'une erreur est levé et doit être montrer à l'utilisateur.
  Le complément optionnel peut ajouter des informations complémentaires.
  */

  complement = complement || "";

  code = /Err[euo]+r/.test(code) ? code : 'Erreur : ' + code; // Erreur ou Error sont traités
  console.log(code); // log
  showError(code + "<br><center>" + complement + "</center>"); // affichage dans le DOM

  return code; // renvoie le code d'erreur
}

function showError(message) {
  // Affiche l'erreur dans le DOM.

  showLoader("error");
  document.getElementById('error-message').innerHTML = message;
}

function download(data, filename, type) {
  /**
  * Lance le téléchargement d'un fichier
  * data : contenu du fichier
  * filename : nom du fichier
  * type : type de fichier (text/plain si non renseigné)
  * Source : StackOverflow
  **/

    type = type || "text/plain";

    var file = new Blob([data], {type: type});

    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others

        var a = document.createElement("a");
        var url = URL.createObjectURL(file);

        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();

        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 0);
    }
}

function hideAlti(inout) {
  /**
  Gère l'affichage des cases altitude et hauteur en particulier,
  car elle se prêtent mal à la méthode générale.
  **/

  alti_checked = document.getElementById('type-alti-altitude-point-'+inout).checked;
  if (alti_checked) { // on désactive les 'cases hauteur'
    disable(document.getElementsByClassName('proj-hauteur-point-' + inout));
    disable(document.getElementsByClassName('geog-hauteur-point-' + inout));
  }
  else { // on désactive les 'cases altitude'
    disable(document.getElementsByClassName('proj-alti-point-' + inout));
    disable(document.getElementsByClassName('geog-alti-point-' + inout));
  }
}

// raccourci
files = () => Array.from(document.getElementById('input-file-in').files).concat(Array.from(document.getElementById('dropped-files').files));

function goToMainPage() { // Page de garde vers page principale
  document.location.href = './geofs.php';
}
function goToHomePage() { // Page principale vers page de garde
  document.location.href = './';
}

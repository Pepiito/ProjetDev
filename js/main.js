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


function sendDataToModel() {

  data = 'formatage=' + allVar['head_data']['in'] + '-' + allVar['head_data']['out'] + allVar['data'];
  console.log(data);
  Ajax(receiveDataFromModel, "./php/modele/transfo_coord.php", data)
}

function receiveDataFromModel(reponse) {

  if (isPHPErrorType(reponse)) {
    console.log("Erreur sur la réponse AJAX :\n" + reponse);
  }
  else if (isErrorType(reponse)) {
    // Instruction en cas d'erreur du modèle
    console.log('Error');
  }
  else {
    // Instruction si tout va bien
    coordonnees = JSON.parse(reponse)
    console.log(coordonnees);
  }

}

function isErrorType(string) {
  return string.substr(0,5) == "Error"
}

function isPHPErrorType(string) {
  return /<b>/.test(string)
}


function modify(htmlCollection, display, select) {
  if (htmlCollection instanceof HTMLCollection) { // liste d'élement
    first = true;
    Array.from(htmlCollection).forEach( function(htmlelement) {
      if (select & first) {
        if (htmlelement.tagName == 'OPTION') {
          htmlelement.selected = true;
          first = false;
        }
      }
      else if (!select) htmlelement.selected = false;
      htmlelement.style = "display:"+display;
    });
  }
  else { // unique element
    htmlCollection.style = "display:"+display;
    htmlCollection.selected = select;
  }
}
function enable(htmlCollection) {
  modify(htmlCollection, "block", true);
}
function disable(htmlCollection) {
  modify(htmlCollection, "none !important", false);
}

function adaptDisplay(array, toDisplay, data, inout) {
  /*
  *
  *  toggle l'affichage pour n'afficher que les éléments de la classe souhaitée (toDisplay et caches les autres)
  *  Cache tous les éléments puis affiche ceux désirés
  *
  * array : tableau global déclarant toutes les options selon file/point in/out (ex : coordType)
  * toDisplay : classe d'éléments à afficher (ex : 'cart')
  * data : point / file
  * inout : in / out
  *
  */

  for (elem in array) {
    disable(array[elem][data][inout]);
  }
  for (elem in array) {
    if (elem == toDisplay) enable(array[elem][data][inout]);
  }

}

function hideAlti(inout) {
  alti_checked = document.getElementById('type-alti-altitude-point-'+inout).checked;
  if (alti_checked) {
    disable(document.getElementsByClassName('proj-hauteur-point-' + inout));
    disable(document.getElementsByClassName('geog-hauteur-point-' + inout));
  }
  else {
    disable(document.getElementsByClassName('proj-alti-point-' + inout));
    disable(document.getElementsByClassName('geog-alti-point-' + inout));
  }
}

function getAllElementsByClass(list_elements, globalArray) {
  window.allVar[globalArray] = new Array();
  list_elements.forEach( function (elem) {
    window.allVar[globalArray][elem] = new Array();
    ['point', 'file'].forEach( function (data) {
      window.allVar[globalArray][elem][data] = new Array();
      ['in', 'out'].forEach( function (inout) {
        window.allVar[globalArray][elem][data][inout] = document.getElementsByClassName(elem +'-'+ data + '-' + inout);
      });
    });
  });
}

function toggleAltiHauteur(inout) {
  var elems_to_show = document.getElementsByClassName(class_alti_hauteur);
  Array.from(elems_to_toggle).forEach( function (elem) {
    if (elem.style.display != "block") elem.style.display = "block";
    else elem.style.display = "none";
  });
}

function setCoordValue(type, coord, value) {
  document.getElementById('coord' + type + coord + '-point-out').value = parseFloat(value);
}

function registerAllData() {
  console.log('registering');
  allVar['file'] = new Array();
  allVar['point'] = new Array();
  allVar['file']['in'] = new Array();
  allVar['point']['in'] = new Array();
  allVar['file']['out'] = new Array();
  allVar['point']['out'] = new Array();

  allHTMLElem.forEach( function (input) {

    var id = input.id;
    var inout = '';
    if (id.substr(-4) == "-out") {
      inout = 'out';
      id = id.slice(0, -4);
    }
    else if (id.substr(-3) == '-in') {
      inout = 'in';
      id = id.slice(0, -3);
    }
    else {console.log(input.id + " non traité");return;}

    if (id.substr(-5) == "-file") {
      data = 'file';
      id = id.slice(0, -5);
    }
    else if (id.substr(-6) == '-point') {
      data = 'point';
      id = id.slice(0, -6);
    }
    else {console.log(input.id + " non traité");return;}

    if (input.tagName == "SELECT" & input.value == 'false'){
      allVar[data][inout][id] = false;
      return;
    }

    if (input.type == "checkbox" | input.type == "radio") allVar[data][inout][id] = input.checked;
    else {
      allVar[data][inout][id] = input.value || false;
    }
  });

}

function addToData(key, value) {

  if (key[0] == '_') {
    if (!inArray(key[1], allVar['head_data']['out'])) {
      allVar['head_data']['out'] += key[1];
    }
    else return;
  }
  else {
    if (!inArray(key, allVar['head_data']['in'])) {
      allVar['head_data']['in'] += key;
    }
    else return;
  }

  allVar['data'] += '&' + key + '=' + value;
}

function inArray(elem, arr) {
  return (arr.indexOf(elem) != -1);
}

function validAndSetData() {
  data = allVar['type-transfo-selected'];

  allVar['data'] = '';
  allVar['head_data'] = new Array;
  allVar['head_data']['in'] = '';
  allVar['head_data']['out'] = '';

  // Pour la dénomination des variables, se référer à la doc docs/formatages_transfert_donnees.docx
  var t = allVar[data]['in']['type-coord'];

  addToData('t', t);
  addToData('P', allVar[data]['in']['systeme-plani']);

  if ((t == 'proj') | (t == 'geog')) {
    var T = allVar[data]['in']['type-alti-altitude']
    if (T) {
      T = 'a'
      var H = validateCoord(allVar[data]['in']['coord-altitude']);
    if (H) addToData('H', H);
      else return;
      addToData('A', allVar[data]['in']['systeme-alti']);
    }
    else {
      T = 'h'
      var h = validateCoord(allVar[data]['coord-hauteur']);
      if (h) addToData('h', h);
      else return;
    }
    addToData('T', T);
  }

  if (data == 'point') {

    switch (t) {
      case 'cart':
        var X = validateCoord(allVar[data]['in']['coord-x']);
        var Y = validateCoord(allVar[data]['in']['coord-y']);
        var Z = validateCoord(allVar[data]['in']['coord-z']);
        if (X && Y && Z) {
          addToData('X', X);
          addToData('Y', Y);
          addToData('Z', Z);
        }
        else return;
        break;

      case 'geog':
        var l = validateCoord(allVar[data]['in']['coord-lng']);
        var f = validateCoord(allVar[data]['in']['coord-lat']);
        console.log(l, f);
        if (l && f) {
          addToData('l', l);
          addToData('f', f);
        }
        else return;
        break;

      case 'proj':
        var p = allVar[data]['in']['projection'];
        var E = validateCoord(allVar[data]['in']['coord-est']);
        var N = validateCoord(allVar[data]['in']['coord-nord']);
        var c = validateCoord(allVar[data]['in']['coord-eta']);
        var x = validateCoord(allVar[data]['in']['coord-xi']);
        var C = validateCoord(allVar[data]['in']['coord-cote']);
        console.log(p, E, N);
        if (p && E && N) {
          addToData('p', p);
          addToData('E', E);
          addToData('N', N);
          addToData('c', c);
          addToData('x', x);
          addToData('C', C);
        }
        else return;
        break;
    }

    var _t = allVar[data]['out']['type-coord'];

    addToData('_t', _t);
    addToData('_P', allVar[data]['out']['systeme-plani']);

    switch (_t) {
      case 'cart':
        break;
      case 'proj':
        addToData('_p', allVar[data]['out']['projection'])
      default:
      var _T = allVar[data]['out']['type-alti-altitude'];
      if (_T) {
        _T = 'a';
        addToData('_A', allVar[data]['out']['systeme-alti']);
      }
      else _T = 'h';
      addToData('_T', _T);
    }

  }
  sendDataToModel();
}

function validateCoord(coord) {

  // Traitement dms
  if (!coord) return false;
  coord = coord.replace(',', '.').replace('/\D[^\.]/', '') // Garde uniquement nombre et points
  if (isNaN(parseFloat(coord))) {
    raiseError('600');
    return false;
  }
  return coord;
}

function raiseError(code) {
  console.log(code);
  throw 'Error'
}

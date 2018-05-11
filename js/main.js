/*

Fichier contenant les fonctions.
Les écouteurs d'évènements sont contenues dans le controleur.js

*/

function Test() {

  console.log("Working");

}


function sendAjax(callback, file, data, isFormData) {
  /*
  Requete AJAX pour ouvrir le fichier sélectionné. Prend en paramètrre :
   * function callback, exécuté avec le paramètre responsetext une fois la requete AJAX effectué correctement
   * file, fichier de destination de la requete
   * data, données envoyés avec la requête (optionnel)
  */

  data = data || "";
  isFormData = isFormData || false;

  var ajax = new XMLHttpRequest();
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

  data = 'formatage=' + allVar['head_data']['in'] + '-' + allVar['head_data']['out'] + allVar['data'];
  console.log(data);
  return sendAjax(receiveDataFromModel, "./php/modele/transfo_coord.php", data)
}

function receiveDataFromModel(reponse) {

  if (isPHPErrorType(reponse)) {
    console.log("Erreur sur la réponse AJAX :\n" + reponse);
    showError(reponse);
  }
  else if (isErrorType(reponse)) {
    raiseError(reponse);
  }
  else {
    // Instruction si tout va bien
    try {
      window.coordonnees = JSON.parse(reponse);
    }
    catch (e) {
      return raiseError(reponse);
    }

    if (allVar['type-transfo-selected'] == 'point') {

      var t_out = allVar['point']['out']['type-coord'];
      var P_out = (allVar['point']['out']['systeme-plani'] == "CH1903+") ? "CH1903plus" : allVar['point']['out']['systeme-plani'];
      var T_out = allVar['point']['out']['type-alti-altitude'];
      var A_out =  allVar['point']['out']['systeme-alti'];
      var p_out = allVar['point']['out']['projection'];

      var unite_out = allVar['point']['out']['geog-unite'];

      console.log(coordonnees);

      switch (t_out) {
        case 'cart':
          setCoordValue(t_out, 'x', coordonnees[t_out][P_out]['X']['X0']);
          setCoordValue(t_out, 'y', coordonnees[t_out][P_out]['Y']['Y0']);
          setCoordValue(t_out, 'z', coordonnees[t_out][P_out]['Z']['Z0'])
          break;
        case 'geog':
          var c = T_out ? coordonnees[t_out][P_out]['a'][A_out] : coordonnees[t_out][P_out]['h'];
          setCoordValue(t_out, 'lng', c['lambda']['lambda0'], unite_out);
          setCoordValue(t_out, 'lat', c['phi']['phi0'], unite_out);
          T_out ? setCoordValue(t_out, 'altitude', c['H']['H0']) : setCoordValue(t_out, 'hauteur', c['h']['h0']);

          break;
        case 'proj':
          var c = T_out ? coordonnees[t_out][P_out][p_out]['a'][A_out] : coordonnees[t_out][P_out][p_out]['h'];
          setCoordValue(t_out, 'est', c['E']['E0']);
          setCoordValue(t_out, 'nord', c['N']['N0']);
          T_out ? setCoordValue(t_out, 'altitude', c['H']['H0']) : setCoordValue(t_out, 'hauteur', c['h']['h0']);
          if (c['eta']) setCoordValue(t_out, 'eta', c['eta']['eta0']);
          if (c['ksi']) setCoordValue(t_out, 'eta', c['ksi']['ksi0']);

      }

    }
    else { // cas fichier

      var t_out = allVar['file']['out']['type-coord'];
      var P_out = allVar['file']['out']['systeme-plani'];
      var T_out = allVar['file']['out']['type-alti-altitude'];
      var A_out = allVar['file']['out']['systeme-alti'];
      var p_out = allVar['file']['out']['projection'];
      var format_out = allVar['file']['out']['selection-formatage'];

      var unite_out = allVar['file']['out']['geog-unite'];

      applyLoading("Ecriture du fichier...");

      var fileContent = fillFile(t_out, P_out, T_out, A_out, p_out, format_out, unite_out);
      var extension = allVar['file']['out']['extension'];
      var filename = allVar['file']['out']['nom-export'] + "." + extension || "coordonnees_" + P_out + "_" + T_out + "." + extension;

      if (!/\.\w+$/.test(filename)) raiseError("Nom de fichier en sortie non valide. Vérifiez l'extension");

      applyLoading("Téléchargement en cours...");
      download(fileContent, filename);


    }

    endLoading();

    if (coordonnees['geojson_ptsess']) { // cas add map
		var geojson_ptsess = coordonnees['geojson_ptsess'];
		var features_ptsess3 = new ol.format.GeoJSON().readFeatures(geojson_ptsess);
		source.addFeatures(features_ptsess3)
		close_popup();
	  
    }
  }

}

function fillFile(_t, _P, _T, _A, _p, format, _u) {

  var explicite = {"cart":"Cartésiennes", "proj": "Projetées", "geog": "Géographiques", "H": "altitude", "h": "hauteur ellipsoïdale"}

  file_content = "";
  file_content += "#" + pad(pad("GEO FS", 46, "*", true), 99, "*") + "\r\n";
  file_content += "# Fichier réalisé grâce au service en ligne GEOFS retrouvable à l'adresse geofs.ensg.eu . \r\n"+
                  "# Ce site a été réalisé dans le cadre d'un projet en partenariat entre l'ENSG Paris et HEIG Canton de Vaud (Suisse) .\r\n";
  file_content += "#\r\n# Coordonnées exprimées :\r\n#\r\n" ;
  file_content += createline(["Coordonnées :", explicite[_t]], 30, "# ") + "#\r\n";
  file_content += createline(["SYSTEME :", _P], 30, "# ") + "#\r\n";
  if (_t == "proj") file_content += createline(["PROJECTION :", _p], 30, "# ") + "#\r\n";
  if (_t != "cart") {
    file_content += createline(["Coordonnées en  :", explicite[(_T ? "H" : "h")]], 30, "# ") + "#\r\n";
    if (_T) file_content += createline(["SYSTEME ALTIMETRIQUE :", _A], 30, "# ") + "#\r\n";
  }
  file_content += "#" + pad("", 99, "-") + "\r\n";

  coords = coordonnees[_t][_P];

  console.log(coordonnees);
  if (_t == "cart") {
    file_content += createline(['Nom', 'X', 'Y', 'Z'], undefined, "# ");

    for (var i = 0; i < Object.keys(coords['X']).length; i++) {
      file_content += createline([coordonnees['n']['n'+i], coords['X']['X'+i], coords['Y']['Y'+i], coords['Z']['Z'+i]]);
    }
  }
  else if (_t == "geog") {
    var c = _T ? coords['a'][_A] : coords['h'];
    file_content += createline(['Nom', 'lambda', 'phi', (_T ? 'H' : 'h')], undefined, "# ");

    for (var i = 0; i < Object.keys(c['lambda']).length; i++) {
      file_content += createline([coordonnees['n']['n'+i], radToAngle(c['lambda']['lambda'+i], _u), radToAngle(c['phi']['phi'+i], _u), (_T ? c['H']['H'+i] : c['h']['h'+i]) ]);
    }
  }
  else if (_t == "proj") {
      var c = _T ? coords[_p]['a'][_A] : coords[_p]['h'];
      file_content += createline(['Nom', 'E', 'N', (_T ? 'H' : 'h')], undefined, "# ");

      for (var i = 0; i < Object.keys(c['E']).length; i++) {
        file_content += createline([coordonnees['n']['n'+i], c['E']['E'+i], c['N']['N'+i], (_T ? c['H']['H'+i] : c['h']['h'+i]) ]);
      }
  }
  else {
    raiseError("Erreur inconnue");
  }

  return file_content;
}

function createline(array, largeur_colonne, line_start) {
  var line = line_start || " ";
  var l_colonne = largeur_colonne || 16;

  for (i in array) {
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
  return /Error /.test(string)
}

function isPHPErrorType(string) {
  return /<b>/.test(string)
}

function setCoordValue(type, coord, value, unite) {
  unite = unite || 'none';
  value = radToAngle(value, unite)

  document.getElementById('coord-' + type + '-' + coord + '-point-out').value = value;
}


function modify(htmlCollection, display) {
  if (htmlCollection instanceof HTMLCollection) { // liste d'élement
  var first = true;
  var id_select = "";
    Array.from(htmlCollection).forEach( function(htmlelement) {
      if (htmlelement.tagName == 'OPTION') {
        if (htmlelement.parentNode.parentNode.id != id_select) {
          first = true;
          id_select = htmlelement.parentNode.parentNode.id;
        }
        htmlelement.disabled = !display;
        if (display && first) {
          htmlelement.selected = true;
          first = false;
        }
        else htmlelement.selected = false;
      }
      else htmlelement.style = "display:" + (display ? "block" : "none !important");
    });
  }
  else { // unique element
    if (htmlCollection.tagName == 'OPTION') {
      htmlCollection.disabled = !display;
      htmlelement.selected = true;
    }
    else htmlCollection.style = "display:" + (display ? "block" : "none !important");
  }
}

function enable(htmlCollection) {
  modify(htmlCollection, true);
}
function disable(htmlCollection) {
  modify(htmlCollection, false);
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

function registerAllData() {

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

    if (input.tagName == "SELECT"){
      try {
        if (input.options[input.options.selectedIndex].value == 'false') {
          allVar[data][inout][id] = false;
          return;
        }
      }
      catch (e) {
        allVar[data][inout][id] = false;
        return;
      }
    }

    if (input.type == "checkbox" | input.type == "radio") allVar[data][inout][id] = input.checked;
    else {
      var val = input.value
      allVar[data][inout][id] = (val == "0") ? "0" : (val || false);
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

function toggleHead(side) {
  toShow = 'trans_coord';
  toHide = 'trans_fichier';
  border = "2px 2px 0 0";
  if (side == "right") {
    toShow = 'trans_fichier';
    toHide = 'trans_coord';
    border = "2px 0 0 2px";
  }
  document.getElementById(toShow).style.display="block";
    document.getElementById(toHide).style.display="none";
    document.getElementById('head_' + toHide).style.borderWidth="0 0 2px 0";
    document.getElementById('head_' + toShow).style.borderWidth = border;
  }

function set_tPTAp(str_t, t, str_P, P, str_T, T, str_A, A, str_p, p) {

  addToData(str_t, t);
  addToData(str_P, (P == "CH1903+" ? "CH1903plus" : P));
  if (t == 'proj') addToData(str_p, p);

  if ((t == 'proj') | (t == 'geog')) {
    addToData(str_T, T ? 'a' : 'h');
    if (T) addToData(str_A, A);
  }
}

function validAndSetData(addMap) {

  addMap = addMap || false;

  applyLoading("Chargement...");

  registerAllData();

  data = "point";

  invalide_data = "";

  allVar['data'] = '';
  allVar['head_data'] = new Array;
  allVar['head_data']['in'] = '';
  allVar['head_data']['out'] = '';

  // Pour la dénomination des variables, se référer à la doc docs/formatages_transfert_donnees.docx
  var t = allVar[data]['in']['type-coord'];
  var T = allVar[data]['in']['type-alti-altitude'];
  var p = allVar[data]['in']['projection'];
  var P = allVar[data]['in']['systeme-plani'];

  set_tPTAp('t', t, 'P', P, 'T', T, 'A', allVar[data]['in']['systeme-alti'], 'p', p)

  var unite_in = allVar[data]['in']['geog-unite'];

  if ((t == 'proj') | (t == 'geog')) {
    var T = allVar[data]['in']['type-alti-altitude'];
    if (T) {
      var H = validateCoord(allVar[data]['in']['coord-' + t + '-altitude']);
      if (H) addToData('H', H);
      else invalide_data += '   Altitude [m]  ';
    }
    else {
      var h = validateCoord(allVar[data]['in']['coord-' + t + '-hauteur']);
      if (h) addToData('h', h);
      else invalide_data += '   Hauteur [m]  ';
    }
  }

  if (data == 'point') {

    switch (t) {
      case 'cart':
        var X = validateCoord(allVar[data]['in']['coord-cart-x']);
        if (X) addToData('X', X);
        else invalide_data += '   X [m] ';

        var Y = validateCoord(allVar[data]['in']['coord-cart-y']);
        if (Y) addToData('Y', Y);
        else invalide_data += '   Y [m] ';

        var Z = validateCoord(allVar[data]['in']['coord-cart-z']);
        if (Z) addToData('Z', Z);
        else invalide_data += '   Z [m] ';

        break;

      case 'geog':
        var l = validateCoord(allVar[data]['in']['coord-geog-lng'], unite_in);
        if (l) addToData('l', l);
        else invalide_data += '   Longtiude  ';

        var f = validateCoord(allVar[data]['in']['coord-geog-lat'], unite_in);
        if (f) addToData('f', f);
        else invalide_data += '   Latitude  ';

        break;

      case 'proj':

        var E = validateCoord(allVar[data]['in']['coord-proj-est']);
        if (E) addToData('E', E);
        else invalide_data += '   E [m]  ';

        var N = validateCoord(allVar[data]['in']['coord-proj-nord']);
        if (N) addToData('N', N);
        else invalide_data += '   N [m]  ';

        var c = validateCoord(allVar[data]['in']['coord-proj-eta']);
        var x = validateCoord(allVar[data]['in']['coord-proj-xi']);

        if (c && x) addToData('c', c);
        if (c && x) addToData('x', x);

        break;
    }

    var _t = allVar[data]['out']['type-coord'],
    _P = allVar[data]['out']['systeme-plani'],
    _T = allVar[data]['out']['type-alti-altitude'],
    _A = allVar[data]['out']['systeme-alti'],
    _p = allVar[data]['out']['projection']

    set_tPTAp('_t', _t, '_P', _P, '_T', _T, '_A', _A, '_p', _p)

  }

  addToData("addMap", addMap);

  if (invalide_data == "") {
    sendDataToModel();
    return "Success";
  }
  else return invalide_data;
}

function validateCoord(coord, unite) {

  unite = unite || 'none';

  // Traitement dms
  if (coord === "0") return "0";
  if (!coord) return false;
  coord = coord.replace(',', '.').replace('/\D[^\.]/', '') // Garde uniquement nombre et points
  if (isNaN(parseFloat(coord))) {
    raiseError("Erreur 206: Le paramètre suivant n'est pas valide : ", coord);
    return false;
  }

  if (unite == 'grad') return angleToRad(coord, 400);
  else if (unite == 'deg') return angleToRad(coord, 360);
  else if (unite == 'rad') return angleToRad(coord, 2*Math.PI);
  else return coord + "";
}

function angleToRad(alpha, nb) {
  alpha = parseFloat(alpha);
  while (!(alpha >= 0 && alpha < nb)) {
    if (alpha < 0) alpha += nb;
    else if (alpha >= nb) alpha -= nb;
  }
  return alpha*2*Math.PI/nb;
}

function radToAngle(alpha, nb) {
  alpha = parseFloat(alpha);
  if (nb == "deg") nb = 360;
  else if (nb == "grad") nb = 400;
  else if (nb == "rad") nb = 2*Math.PI;
  else return alpha;
  return precisionRound(alpha*nb/(2*Math.PI), 9);
}

function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}

function raiseError(code, complement) {
  complement = complement || "";

  code = /Err[euo]+r/.test(code) ? code : 'Erreur : ' + code;
  console.log(code);
  showError(code + "<br><center>" + complement + "</center>");

  return code;
}

function getFileContent(file, addMap) {

  registerAllData();

  file = file || document.getElementById("input-file-in").files[0];
  addMap = addMap || false;

  allVar['data'] = '';
  allVar['head_data'] = new Array;
  allVar['head_data']['in'] = '';
  allVar['head_data']['out'] = '';

  var format = allVar['file']['in']['selection-formatage'];
  var format_out = allVar['file']['out']['selection-formatage'];

  var t = allVar['file']['in']['type-coord'],
  P = allVar['file']['in']['systeme-plani'];

  set_tPTAp('t', t, 'P', P, 'T', ( /H/.test(format) ? "a" : "h"), 'A', allVar['file']['in']['systeme-alti'], 'p', allVar['file']['in']['projection']);
  addToData("addMap", addMap);

  var _t = allVar['file']['out']['type-coord'],
  _P = allVar['file']['out']['systeme-plani'];
  set_tPTAp('_t', t, '_P', P, '_T', ( /H/.test(format_out) ? "a" : "h"), '_A', allVar['file']['out']['systeme-alti'], '_p', allVar['file']['out']['projection']);

  var separateur = allVar['file']['in']['separateur'];
  var start = allVar['file']['in']['ligne-start'];
  var format = allVar['file']['in']['selection-formatage'];

  if ("proj" == allVar['file']['in']['type-coord']) {
    format += allVar['file']['in']['selection-formatage-deviation'] ? allVar['file']['in']['selection-formatage-deviation'] : "";
  }


  var formdata = new FormData();

  formdata.append('file', file);
  formdata.append('separateur', separateur);
  formdata.append('start', start);
  formdata.append('format', format);

  applyLoading("Chargement du fichier...");

  return sendAjax(collectData, './php/vue/get_file_content.php', formdata, true);


}

function collectData(reponse) {

  if (isErrorType(reponse)) raiseError(reponse);
  else if (isPHPErrorType(reponse)) {

    console.log("Erreur PHP : \n" + reponse);
    raiseError('Erreur 200 : Une erreur inattendu est survenu.');
  }
  else {

    applyLoading('Traitement des données. Ceci peut prendre un moment...');

    try {
      var variables = JSON.parse(reponse);
    }
    catch (e) {
      raiseError("Erreur 207: La lecture du fichier s'est mal passé.", reponse);
      return;
    }
    console.log(variables);

    var unite_in = (allVar['file']['in']['type-coord'] == 'geog') ? allVar['file']['in']['geog-unite'] : "none";


    for (type in variables) {

      var values = variables[type];
      console.log(values);
      var temp_str = "";

      for (line in values) {

        value = values[line];

        if (type == 'n') {
          temp_str += value.replace(";", "_") + ';';
        }
        else {

          var valid_value = (type == "f" || type == "l") ? validateCoord(value, unite_in) : validateCoord(value);
          if (valid_value) temp_str += valid_value + ';';
          else return raiseError('210', 'Ligne ' + line + ", variable " + type);

        }

      }

      str = temp_str.substr(0, temp_str.length-1);

      addToData(type, str);

    }

    console.log(allVar['data']);
    sendDataToModel();
    return 'Success';
  }

}

function download(data, filename, type) {

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

async function applyLoading(message) {

  showLoader("loader");
  document.getElementById("loader-message").innerHTML = message;
  await sleep(800);
  return;
}

function sleep(ms) {
  /*
  Temporisation de la durée souhaité (ms).
  Correctif de 4ms correspondant approximativement au temps d'éxécution propre
  */

  return new Promise(resolve => setTimeout(resolve, Math.max(1, ms-4)));
}

function endLoading() {

  var loaderFiltre = document.getElementById('loader-filtre');
  loaderFiltre.style.visibility = "hidden";
  loaderFiltre.style.opacity = 0;
  loaderFiltre.style.zIndex = -10;
}

function showError(message) {

  showLoader("error");
  document.getElementById('error-message').innerHTML = message;
}

function showLoader(loadOrError) {

  document.getElementById('loader-content').style.visibility = (loadOrError == "loader") ? "visible" : "hidden";
  document.getElementById('error-content').style.visibility = (loadOrError != "loader") ? "visible" : "hidden";

  var loaderFiltre = document.getElementById('loader-filtre');
  if (loaderFiltre.style.visibility != "visible") {
    loaderFiltre.style.visibility = "visible";
    loaderFiltre.style.opacity = 1;
    loaderFiltre.style.zIndex = 1000;
  }
}

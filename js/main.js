/*

Fichier contenant les fonctions.
Les écouteurs d'évènements sont contenues dans le controleur.js

*/

function receiveDataFromModel(reponse) {
  /**
  * Est éxécuté lorsque le modèle répond à la requête AJAX de transformation des coordonnées.
  * Appelle différentes fonctions selon la nature de la réponse
  * Structure décisionnelle.
  **/

  if (isPHPErrorType(reponse)) { // Erreur PHP renvoyé par le modèle.
    console.log("Erreur sur la réponse AJAX");
    raiseError(reponse);
  }
  else if (isErrorType(reponse)) { // Erreur détecté par le modèle
    reponse ? raiseError(reponse) : endLoading();
  }
  else {
    // Instruction si rien n'est détecté
    try {
      window.coordonnees = JSON.parse(reponse);
    }
    catch (e) { // Le format renvoyé n'est pas correct. Erreur d'origine inconnue
      return raiseError(reponse);
    }

    if (allVar['type-transfo-selected'] == 'point') { // cas point
      integratePointResultInDOM();
    }
    else { // cas fichier
      // création et téléchargement du fichier si l'utilisateur souhaitait le télécharger
      if (!coordonnees['geojson_ptsess']) downloadFileFromResult();
    }

    endLoading(); // disparition de la popup de chargement

    // Si la réponse contient cette variable, c'est que l'utilisateur souhaite ajouter le(s) point(s) à la carte
    if (coordonnees['geojson_ptsess']) {

  		addPointsToMap(); // Ajoute les points à la carte
  		close_popup(); // Affichage de la carte
    }

    window.time_exec = new Date - start
    console.log("Temps d'execution : " + time_exec + "ms");
  }

}

function integratePointResultInDOM() {
  /**
  * Lorsque l'utilisateur souhaite transformer un unique point, cette fonction ajoute les coordonnées
  * dans le système de sortie demandé aux cases correspondantes.
  * Cette fonction fait l'étude de cas pour choisir où et comment les paramètres seront affichés,
  * puis appelle setCoordValue() qui ajoute de manière simple la valeur à sa case.
  **/

  // Paramètres en sortie e l'utilisateur
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
      // c permet d'aérer le code en simplifiant le "path" dans la variable coordonnees
      var c = T_out ? coordonnees[t_out][P_out]['a'][A_out] : coordonnees[t_out][P_out]['h'];
      setCoordValue(t_out, 'lng', c['lambda']['lambda0'], unite_out);
      setCoordValue(t_out, 'lat', c['phi']['phi0'], unite_out);
      T_out ? setCoordValue(t_out, 'altitude', c['H']['H0']) : setCoordValue(t_out, 'hauteur', c['h']['h0']);
      break;

    case 'proj':
      // c permet d'aérer le code en simplifiant le "path" dans la variable coordonnees
      var c = T_out ? coordonnees[t_out][P_out][p_out]['a'][A_out] : coordonnees[t_out][P_out][p_out]['h'];
      setCoordValue(t_out, 'est', c['E']['E0']);
      setCoordValue(t_out, 'nord', c['N']['N0']);
      T_out ? setCoordValue(t_out, 'altitude', c['H']['H0']) : setCoordValue(t_out, 'hauteur', c['h']['h0']);
      if (c['eta']) setCoordValue(t_out, 'eta', c['eta']['eta0'], 'rad');
      if (c['ksi']) setCoordValue(t_out, 'xi', c['ksi']['ksi0'], 'rad');

  }

}

function downloadFileFromResult() {
  /**
  * Lorsque l'utilisateur souhaite transformer un fichier, cette fonction récupère Les
  * coordonnées transformés selon les paramètres de l'utilisateur et en crée un fichier,
  * qui est ensuite téléchargé.
  **/

  // Paramètres séléctionnés par l'utilisateur
  var t_out = allVar['file']['out']['type-coord'];
  var P_out = (allVar['file']['out']['systeme-plani'] == "CH1903+") ? "CH1903plus" : allVar['file']['out']['systeme-plani'];
  var A_out = allVar['file']['out']['systeme-alti'];
  var p_out = allVar['file']['out']['projection'];
  var format_out = allVar['file']['out']['selection-formatage'];
  var format_out_dev = allVar['file']['out']['selection-formatage-deviation'];
  var T_out = /H/.test(format_out);
  var unite_out = allVar['file']['out']['geog-unite'];
  var extension = allVar['file']['out']['extension'];


  if (t_out == "proj" && allVar['file']['in']['selection-formatage-deviation'] != "false") {
    format_out += (format_out_dev == "false" ? "" : format_out_dev);
  }

  applyLoading("Ecriture du fichier...");

  // Création du fichier
  var fileContent = fillFile(t_out, P_out, T_out, A_out, p_out, format_out, unite_out);

  // Validation du nom du fichier
  var filename = allVar['file']['out']['nom-export'] + "." + extension;
  if (!/\.\w+$/.test(filename)) raiseError("Nom de fichier en sortie non valide. Vérifiez l'extension");

  applyLoading("Téléchargement en cours...");
  download(fileContent, filename); // téléchargement du fichier
}


function fillFile(_t, _P, _T, _A, _p, format, _u) {
  /**
  * Crée un string qui sera le contenu du fichier.
  * L'en-tête est d'abord créé, puis le fichier rempli des points transformés.
  **/

  // Pour l'écriture du fichier (utf-8)
  var explicite = {"cart":"Cartesiennes", "proj": "Projetees", "geog": "Geographiques", "H": "altitude", "h": "hauteur ellipsoïdale"}
  var format_to_values = {'n': 'n', 'X': 'X', 'Y': 'Y', 'Z': 'Z', 'E': 'E', 'N': 'N', 'h': 'h', 'H': 'H', 'l': 'lambda', 'f': 'phi', 'c': 'eta', 'x': 'ksi'};
  var format_to_string = {'n': 'Nom', 'X': 'X', 'Y': 'Y', 'Z': 'Z', 'E': 'Est', 'N': 'Nord', 'h': 'hauteur', 'H': 'altitude', 'l': 'lambda', 'f': 'phi', 'c': '&eta;', 'x': '&xi;'};

  // Remplissage de l'en-tête
  file_content = "";
  file_content += "#" + pad(pad("GEO FS", 46, "*", true), 99, "*") + "\r\n";
  file_content += "# Fichier realise grace au service en ligne GEOFS retrouvable a l'adresse geofs.ensg.eu . \r\n"+
                  "# Ce site a ete realise dans le cadre d'un projet en partenariat entre l'ENSG Paris et HEIG Canton de Vaud (Suisse).\r\n";
  file_content += "#\r\n# Coordonnees exprimees :\r\n#\r\n" ;
  file_content += createline(["Coordonnees :", explicite[_t]], 30, "# ") + "#\r\n";
  file_content += createline(["SYSTEME :", _P.replace("plus", "+")], 30, "# ") + "#\r\n";
  if (_t == "proj") file_content += createline(["PROJECTION :", _p], 30, "# ") + "#\r\n";
  if (_t != "cart") {
    file_content += createline(["Coordonnees en  :", explicite[(_T ? "H" : "h")]], 30, "# ") + "#\r\n";
    if (_T) file_content += createline(["SYSTEME ALTIMETRIQUE :", _A], 30, "# ") + "#\r\n";
  }
  file_content += "#" + pad("", 99, "-") + "\r\n";

  console.log(coordonnees);
  coords = coordonnees[_t][_P];

  // Description des coordonnées du fichier (formatage comme demandé par l'utilisateur)
  var headline = [];
  for (var i = 0; i < format.length; i++){
    char = format[i];
    headline.push(format_to_string[char]);
  }
  file_content += createline(headline, undefined, "# ");

  // c permet d'aérer le code en simplifiant le "path" dans la variable coordonnees
  if (_t == "cart") var c = coords;
  else if (_t == "geog") var c = _T ? coords['a'][_A] : coords['h'];
  else if (_t == "proj") var c = _T ? coords[_p]['a'][_A] : coords[_p]['h'];
  else raiseError("Erreur inconnue");

  // parcours des points
  for (var num_line = 0; num_line < coordonnees['n'].length; num_line++) {
    ligne = [];

    // Ecriture au format souhaité
    for (var i = 0; i < format.length; i++){
      char = format[i];
      val = (char == 'n') ? coordonnees['n'][num_line] : c[format_to_values[char]][format_to_values[char]+num_line]

      // Passage éventuel en radians
      ligne.push((char == 'l' || char == 'f') ? radToAngle(val, _u) : val);
    }
    file_content += createline(ligne);
  }

  return file_content;
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

function getAllElementsByClass(list_elements, globalArray) {
  /**
  Récupère tous les éléments du DOM ayant une classe au format :
  radical-[point/file]- [in/out] avec radical un élément de list_elements
  Et les stocke dans la supervariable allVar[globalArray]
  **/

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

function registerAllData() {
  /**
  * Enregistre tous les paramètres laissés à l'utilisateur.
  * Les paramètres non remplis ou non séléctionnées prennent la valeur false
  * Ces paramètres sont stockés dans la supervariable allVar.
  **/

  allVar['file'] = new Array();
  allVar['point'] = new Array();
  allVar['file']['in'] = new Array();
  allVar['point']['in'] = new Array();
  allVar['file']['out'] = new Array();
  allVar['point']['out'] = new Array();

  allHTMLElem.forEach( function (input) { // Parcours des éléments HTML des paramètres laissés à l'utilisateur

    // On récupère l'id de l'élément html et on le traite avant de le "ranger" dans allVar
    var id = input.id;
    var inout = '';

    // in ou out
    if (id.substr(-4) == "-out") {
      inout = 'out';
      id = id.slice(0, -4);
    }
    else if (id.substr(-3) == '-in') {
      inout = 'in';
      id = id.slice(0, -3);
    }
    else {console.log(input.id + " non traité");return;}

    // file ou point
    if (id.substr(-5) == "-file") {
      data = 'file';
      id = id.slice(0, -5);
    }
    else if (id.substr(-6) == '-point') {
      data = 'point';
      id = id.slice(0, -6);
    }
    else {console.log(input.id + " non traité");return;}

    // cas select : si rien n'est séléctionné, la value sera à 'false'. On traduit au booléen false.
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

    // cas checkbox et radio
    if (input.type == "checkbox" | input.type == "radio") allVar[data][inout][id] = input.checked;
    else { // sinon on récupère la value
      var val = input.value
      allVar[data][inout][id] = (val == "0") ? "0" : (val || false); // si '0' : on renvoie '0' (car peut être traité comme false sur les tests)
    }
  });

}

function toggleHead(type) {
  /**
  * Modifie l'affichage de l'en-tête, selon qu'on sélection le traitement par fichier
  * ou par points.
  **/
  toShow = (type == "point") ? "fichier" : "point";
  toHide = type;

  document.getElementById("trans_"+toShow).style.visibility = "visible";
  document.getElementById("trans_"+toShow).style.opacity = 1;
  document.getElementById('underline_'+toShow).style.width = "90%";

  document.getElementById("trans_"+toHide).style.opacity = 0;
  document.getElementById("trans_"+toHide).style.visibility = "hidden";
  document.getElementById('underline_'+toHide).style.width = "0%";
}

function set_tPTAp(str_t, t, str_P, P, str_T, T, str_A, A, str_p, p) {
  /**
  * Renseigne l'en-tête de la requête AJAX pour la transformation de coordonnées.
  * Cette fonction sert pour les paramètres en entrée et/ou pour les paramètres en sortie
  * t : type de coordonnées;
  * P : système palnimétrique
  * T : type d'altimétrie
  * A : système altimétrique
  * p : projection
  **/

  addToData(str_t, t);
  addToData(str_P, (P == "CH1903+" ? "CH1903plus" : P));
  if (t == 'proj') addToData(str_p, p);

  if ((t == 'proj') | (t == 'geog')) {
    addToData(str_T, T ? 'a' : 'h');
    if (T) addToData(str_A, A);
  }
}

function processTransformationPoint(addMap) {
  /**
  * Lance le processus de transformation d'un point.
  * Récupère les paramètres saisi par l'utilisateur, leur applique une validation
  * et les ajoute à la supervariable allVar['data'].
  * Si tout s'est bien passé, lance la requête AJAX pour la transformation.
  * Pour la dénomination des variables, se référer à la doc docs/formatages_transfert_donnees.docx
  * :return: string d'erreur si au moin un des paramètres n'est pas valide. "Success" sinon
  **/

  applyLoading("Chargement...");

  data = "point";

  invalide_data = ""; // string d'erreur

  // initialisation de la variable data
  allVar['data'] = '';
  allVar['head_data'] = new Array;
  allVar['head_data']['in'] = '';
  allVar['head_data']['out'] = '';

  // enregistrement des métadonnées en entrée et sortie
  var t = allVar[data]['in']['type-coord'],
  T = allVar[data]['in']['type-alti-altitude'],
  p = allVar[data]['in']['projection'],
  A = allVar[data]['in']['systeme-alti'],
  P = allVar[data]['in']['systeme-plani'];

  set_tPTAp('t', t, 'P', P, 'T', T, 'A', A, 'p', p);

  var _t = allVar[data]['out']['type-coord'],
  _P = allVar[data]['out']['systeme-plani'],
  _T = allVar[data]['out']['type-alti-altitude'],
  _A = allVar[data]['out']['systeme-alti'],
  _p = allVar[data]['out']['projection']

  set_tPTAp('_t', _t, '_P', _P, '_T', _T, '_A', _A, '_p', _p);

  var unite_in = allVar[data]['in']['geog-unite'];

  // Ajout de la hauteur/altitude en focntion des choix de l'utilisateur
  if ((t == 'proj') | (t == 'geog')) {
    var T = allVar[data]['in']['type-alti-altitude'];
    if (T) {
      var H = validateCoord(allVar[data]['in']['coord-' + t + '-altitude']);
      if (H) addToData('H', H); // ajout altitude
      else invalide_data += '   Altitude [m]  ';
    }
    else {
      var h = validateCoord(allVar[data]['in']['coord-' + t + '-hauteur']);
      if (h) addToData('h', h); // ajout hauteur
      else invalide_data += '   Hauteur [m]  ';
    }
  }

  // Différents cas possibles
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

      // optionnel : ajoutés que si renseignés.
      if (c && x) addToData('c', c);
      if (c && x) addToData('x', x);

      break;
  }

  addToData("addMap", addMap);

  if (invalide_data == "") { // cas succès
    sendDataToModel();
    return "Success";
  }
  else return invalide_data; // des données sont invalides
}

function processTransformationFile(file, addMap) {
  /**
  * Lance le processus de transformation d'un fichier.
  * Récupère le fichier entré par l'utilisateur, l'envoie en AJAX pour récupérer son contenu si ce contenu est valide.
  * Les paramêtres choisis par l'utilisateur sont ajoutés à la supervariable allVar['data'].
  * Pour la dénomination des variables, se référer à la doc docs/formatages_transfert_donnees.docx
  **/

  // initialisation des données à envoyer au modèle
  allVar['data'] = '';
  allVar['head_data'] = new Array;
  allVar['head_data']['in'] = '';
  allVar['head_data']['out'] = '';

  // Paramètres saisi par l'utilisateur pour la lecture du fichier
  var separateur = allVar['file']['in']['separateur'],
  start = allVar['file']['in']['ligne-start'],
  format = allVar['file']['in']['selection-formatage'],
  format_out = allVar['file']['out']['selection-formatage'],
  format_dev = allVar['file']['in']['selection-formatage-deviation'];

  // concaténation avec la déviation de la verticle si renseignée
  format = format + (format_dev ? format_dev : "");

  var t = allVar['file']['in']['type-coord'],
  P = allVar['file']['in']['systeme-plani'];

  // métadonnées en entrée enregistrés
  set_tPTAp('t', t, 'P', P, 'T', /H/.test(format), 'A', allVar['file']['in']['systeme-alti'], 'p', allVar['file']['in']['projection']);
  addToData("addMap", addMap);

  // métadonnées en sortie enregistrés
  var _t = allVar['file']['out']['type-coord'],
  _P = allVar['file']['out']['systeme-plani'];
  set_tPTAp('_t', _t, '_P', _P, '_T', /H/.test(format_out), '_A', allVar['file']['out']['systeme-alti'], '_p', allVar['file']['out']['projection']);

  // envoi du fichier en AJAX via un formulaire formdata
  var formdata = new FormData();

  formdata.append('file', file);
  formdata.append('separateur', separateur);
  formdata.append('start', start);
  formdata.append('format', format);

  applyLoading("Chargement du fichier...");

  // envoi de la requête. La fonction êxécuté en retour sera collectFileData
  return sendAjax(collectFileData, './php/vue/get_file_content.php', formdata, true);


}

function collectFileData(reponse) {
  /**
  * Exécuté une fois le traitement du fcihier terminé, cette fonction traite les données pour les envoyer
  * au modèle si tout s'est bien déroulé et si les coordonnées sont valides
  * Le contenu du fichier est contenu dans :reponse: ..
  * :return: Message d'erreur si une coordonnée est invalide, 'Success' sinon.
  **/

  if (isPHPErrorType(reponse)) { // Le fichier renvoie une erreur PHP

    console.log("Erreur PHP");
    raiseError('Erreur 200 : Une erreur inattendu est survenu.', reponse);
  }
  else if (isErrorType(reponse)) raiseError(reponse); // Une erreur a été détecté lors du parcours du fichier
  else { // Le fichier est valide

    applyLoading('Traitement des données. Ceci peut prendre un moment...');

    try {
      var variables = JSON.parse(reponse);
    }
    catch (e) { // Erreur inconnue
      return raiseError("Erreur 207: La lecture du fichier s'est mal passé.", reponse);
    }

    // Unité si coordonnées en géographique
    var unite_in = (allVar['file']['in']['type-coord'] == 'geog') ? allVar['file']['in']['geog-unite'] : "none";


    for (type in variables) { // Parcours des listes de coordonnées par type (longitude, Est, nom..)

      var values = variables[type];
      var temp_str = "";

      for (line in values) { // parcours coordonnées par coordonnées

        value = values[line];

        if (type == 'n') { // Cas nom : on renvoie le string (sans les points-virgules)
          temp_str += value.replace(";", "_") + ';';
        }
        else { // Validation de la coordonnées

          var valid_value = (type == "f" || type == "l") ? validateCoord(value, unite_in) : validateCoord(value);
          if (valid_value) temp_str += valid_value + ';';
          else return raiseError('210', 'Ligne ' + line + ", variable " + type);

        }

      }

      str = temp_str.substr(0, temp_str.length-1); // un ; en trop

      addToData(type, str); // On ajoute ce type de coordonnées à allVar['data']

    }

    sendDataToModel(); // Envoie des données au modèle
    return 'Success';
  }

}

function processTransformation(type, addMap) {
  /**
  * Fonction globale appelé lorsque l'utilisateur clique sur un des 4 outons de lancement des transformation.
  * Redirige vers la fonction concerné.
  * :addMap: true si l'utilisateur souhaite ajouter le point à la carte. false sinon
  * :type: 'point' ou 'file', selon le bouton cliqué
  **/

  window.start = new Date(); // suivi temporel

  registerAllData(); // actualisation des données renseignés par l'utilisateur

  if (type == "point") {
    var errordata = processTransformationPoint(addMap);
    if (errordata != 'Success') raiseError("Erreur 205 : Les informations suivantes sont manquantes pour la compilation : ", errordata);
  }
  if (type == "file") {
    files().forEach( function (file) { // parcours des fichiers saisis par l'utilisateur
      processTransformationFile(file, addMap);
    });
  }
}

function applyLoading(message) {
  // Change le message dans le loader. L'affiche s'il n'était pas déjà visible.

  showLoader("loader");
  document.getElementById("loader-message").innerHTML = message;
  return;
}

function endLoading() {
  /**
  * Cache le loader / gestionnaire d'erreur.
  * Force la requête AJAX à s'arrêter.
  **/

  var loaderFiltre = document.getElementById('loader-filtre');
  loaderFiltre.style.visibility = "hidden";
  loaderFiltre.style.opacity = 0;
  loaderFiltre.style.zIndex = -10;

  if (ajax.readyState != 4) {
    console.log(ajax);
    ajax.abort();
  }
}

function showLoader(loadOrError) {
  // Affiche le loader ou le message d'erreur, selon le paramètre loadOrError.

  document.getElementById('loader-content').style.visibility = (loadOrError == "loader") ? "visible" : "hidden";
  document.getElementById('error-content').style.visibility = (loadOrError != "loader") ? "visible" : "hidden";

  var loaderFiltre = document.getElementById('loader-filtre');
  if (loaderFiltre.style.visibility != "visible") { // Si le loader n'est pas déjà à l'écran, il est affiché
    loaderFiltre.style.visibility = "visible";
    loaderFiltre.style.opacity = 1;
    loaderFiltre.style.zIndex = 1000;
  }
}

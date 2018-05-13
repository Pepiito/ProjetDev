var connect = document.getElementById('connexion');
var no_connexion = document.getElementById('no-connexion');
var inscription = document.getElementById('inscription');

connect.onclick = function () { // affichage du formulaire de connexion
  showConnect();
  hideButtons();
}

inscription.onclick = function () { // affichage du formulaire d'inscription
  showConnect('inscription');
  hideButtons();
}

Array.from(document.getElementsByClassName('annuler')).forEach( function (annul) {
  annul.onclick = function () { // Pour chaque bouton annuler, on créer l'évènement correspondant
    hideConnect();
    showButtons();
  }
});


no_connexion.onclick = function () { // Crée une session temporaire
  sendAjax(checkConnexionValid, "./php/vue/sess_temp.php");
};

function showConnect(name, opacity, visibility) {
  /*
   affiche le formulaire de connexion (ou d'inscription si précisé dans name)
   */
  name = name || 'connexion';
  opacity = (opacity === undefined ? 1 : opacity);
  visibility = visibility || 'visible';

  var form = document.getElementById('form_'+name);
  form.style.opacity = opacity;
  form.style.visibility = visibility;
}

function hideConnect() {
  showConnect('connexion', 0, 'hidden');
  showConnect('inscription', 0, 'hidden');
}

function showButtons(opacity, visibility) {
  opacity = (opacity === undefined ? 1 : opacity);
  visibility = visibility ||'visible';

  Array.from(document.getElementsByClassName('select-connection')).forEach( function (select) {
    select.style.opacity = opacity;
    select.style.visibility = visibility;
  });
}

function hideButtons() {
  showButtons(0, 'hidden');
}

document.getElementById('identification').addEventListener('click', (event) => { // Envoie les informations relatives à la connexion
  data = "pseudo=" + encodeURIComponent(document.getElementById('pseudo-connexion').value);
  data += "&password=" + encodeURIComponent(document.getElementById('password-connexion').value);
  sendAjax(checkConnexionValid, "./php/vue/sess_controle.php", data);
}, false);

document.getElementById('sinscrire').addEventListener('click', (event) => { // Envoie les informations relatives à l'inscription
  data = "pseudo=" + encodeURIComponent(document.getElementById('pseudo-inscription').value);
  data += "&password=" + encodeURIComponent(document.getElementById('password-inscription').value);
  data += "&pass2=" + encodeURIComponent(document.getElementById('pass2-inscription').value);

  sendAjax(checkConnexionValid, "./php/vue/sess_create.php", data);
}, false);

function checkConnexionValid(reponse) {
  /*
  Fonction éxécuté en retour de la fonction AJAX pour la connexion.
  Affiche une erreur dans le DOM si la réponse est différente de "success".
  */

  if (isPHPErrorType(reponse)) {
    //raiseError('Erreur 200 : Une erreur inattendu est survenu.', reponse);
    goToMainPage();
  }
  else if (reponse != "Success") {
    var isConnexion = (/inscription/.test(reponse) ? false : true); // L'erreur vient de la connexion ou de l'inscription
    var isPseudo = (/pseudo/.test(reponse) ? true : false); // L'erreur est sur le mot de passe ou le pseud

    var error_string = "";

    // Création du message d'erreur en fonction de la configuration de l'erreur
    if (isPseudo && isConnexion) error_string += "Ce pseudo n'existe pas";
    else if (!isPseudo && isConnexion) error_string += "Le mot de passe est incorrect";
    else if (isPseudo && !isConnexion) error_string += "Ce pseudo existe déjà";
    else if (!isPseudo && !isConnexion) error_string += "Les mots de passe ne correspondent pas";
    showErrorConnexion((isConnexion ? "connexion" : "inscription"), error_string);
  }
  else { // si tout s'est bien passé on quitte la page de garde.
    goToMainPage();
  }
}

function showErrorConnexion(type, error, display) {
  /*
  affiche le message d'erreur dans le formulaire correspondant
  */
  display = display || "flex";
  document.getElementById('error-'+type).innerHTML = error;
  document.getElementById('error-'+type).style.display = display;
}

function hideErrorConnexion() { // Cache le mesage d'erreur
  showErrorConnexion("inscription", "", "none");
  showErrorConnexion("connexion", "", "none");
}

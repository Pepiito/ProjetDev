<?php if (!isset($_SESSION)) session_start(); ?>
<?php include('./php/vue/head.php'); ?>
<body id=page_accueil>
  <div id=head-content>
    <div id=headline>
      <img src=./images/geofs.jpg alt=GeoFS>
      <div>GEO FS</div>
    </div>
    <div id=description>
      Logiciel franco-suisse de transformations g&eacute;od&eacute;siques de coordonn&eacute;es en ligne
    </div>
  </div>
  <div id=connexions>
    <div id=form_connexion class=form>
      <div id=error-connexion class=error-form></div>
      <div class=form-labels>
          <label for=pseudo>Pseudo : </label>
          <label for=password>Mot de passe : </label>
      </div>
      <div class=form-inputs>
        <input type=text name=pseudo id=pseudo-connexion class=pseudo />
        <input type=password name=password id=password-connexion class=password />
      </div>
      <div class=form-buttons>
        <button type=button name=button class=form-button id=identification>Se connecter</button>
        <button type=button name=button class=annuler form-button>Annuler</button>
      </div>
    </div>
    <div id=form_inscription class=form>
      <div id=error-inscription class=error-form>Le mot de passe est incorrect</div>
      <div class=form-labels>
          <label for=pseudo>Pseudo : </label>
          <label for=password>Mot de passe : </label>
          <label for=pass2>Confirmez le mot de passe : </label>
      </div>
      <div class=form-inputs>
        <input type=text name=pseudo id=pseudo-inscription class=pseudo />
          <input type=password name=password id=password-inscription class=password />
          <input type=password name=pass2 id=pass2-inscription class=password />
      </div>
      <div class=form-buttons>
        <button type=button name=button class=form-button id=sinscrire>Cr&eacute;er</button>
        <button type=button name=button class=annuler form-button>Annuler</button>
      </div>
    </div>
    <div class=select-connection>
      <button type=button name=button id=connexion>S'identifier</button>
      <span>Se connecter à un compte existant</span>
    </div>
    <div class=select-connection>
      <button type=button name=button id=no-connexion>Continuer sans connexion</button>
      <span>Vos points ne seront pas sauvegard&eacute;s</span>
    </div>
    <div class=select-connection>
      <button type=button name=button id=inscription>S'inscrire</button>
      <span>Cr&eacute;er un nouveau compte</span>
    </div>
  </div>
  <div id=fonctionnalites>
    <div id=transformer-vos-points class=fonctionnalite>
      <div class=head-fonctionnalite>
        Changez de système de coordonn&eacute;es point par point ou par fichier
      </div>
      <div class=img-fonctionnalite></div>
    </div>
    <div id=visualiser-vos-points class=fonctionnalite>
      <div class=head-fonctionnalite>
        Visualisez imm&eacute;diatement vos points sur la carte dans le système de votre choix
      </div>
      <div class=img-fonctionnalite></div>
    </div>
    <div id=retrouver-vos-points class=fonctionnalite>
      <div class=head-fonctionnalite>
        Retrouvez vos points plus tard en vous connectant à votre compte
      </div>
      <div class=img-fonctionnalite></div>
    </div>
  </div>
  <div id=footer-content>
    <img src=./images/ensg-heig.png alt="ENSG - HEIG">
    <span>
      Ce site a &eacute;t&eacute; r&eacute;alis&eacute; par des &eacute;tudiants lors d'un projet en partenariat entre l'ENSG G&eacute;omatique (Paris) et l'HEIG - Canton de Vaud (Suisse).
      Son utilisation est libre de droit.
    </span>
  </div>
  <script type=text/javascript src=./js/accueil.js></script>
</body>

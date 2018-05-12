<div id=page_accueil>
  <div id=head-content>
    <div id=headline>
      <img src=./images/geofs.jpg alt=GeoFS>
      <div>GEO FS</div>
    </div>
    <div id=description>
      Logiciel franco-suisse de transformations géodésiques de coordonnées en ligne
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
        <button type=button name=button class=form-button id=sinscrire>Créer</button>
        <button type=button name=button class=annuler form-button>Annuler</button>
      </div>
    </div>
    <div class=select-connection>
      <button type=button name=button id=connexion>S'identifier</button>
      <span>Se connecter à un compte existant</span>
    </div>
    <div class=select-connection>
      <button type=button name=button id=no-connexion>Continuer sans connexion</button>
      <span>Vos points ne seront pas sauvegardés</span>
    </div>
    <div class=select-connection>
      <button type=button name=button id=inscription>S'inscrire</button>
      <span>Créer un nouveau compte</span>
    </div>
  </div>
  <div id=fonctionnalites>
    <div id=transformer-vos-points class=fonctionnalite>
      <div class=head-fonctionnalite>
        Changez de système de coordonnées point par point ou par fichier
      </div>
      <div class=img-fonctionnalite></div>
    </div>
    <div id=visualiser-vos-points class=fonctionnalite>
      <div class=head-fonctionnalite>
        Visualisez immédiatement vos points sur la carte dans le système de votre choix
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
    <img src=./images/ensg-heig.png alt=ENSG - HEIG>
    <span>
      Ce site à été réalisé par des étudiants lors d'un projet en partenariat entre l'ENSG Géomatique (Paris) et l'HEIG - Canton de Vaud (Suisse).
      Son utilisation est libre de droit.
    </span>
  </div>
</div>

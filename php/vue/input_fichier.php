<form>
  <fieldset id=fichier-de-depart>
    <legend>Fichier de départ</legend>
    <div id='container-input-file' style="display:flex;flex-direction:row;">
      <input id="inputfile" type="file"/>
      <label for="inputfile"><button type="button">Parcourir</button></label>
      <label for="inputfile"><button type="button">Déposer ici</button></label>
    </div>
    <div style=display:flex;flex-direction:row;align-items:center;>
      <label for="separateur-file-in">Séparateur: </label>
      <input type="text" id=separateur-file-in class=input-transfo-fichier value=";" maxlength="2">
      <div style="margin-left:30px;display:flex;flex-direction:row;">
        <select id="selection-formatage-in" class=input-transfo-fichier>
          <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
          <?php echo AfficheFileConfigs('in'); ?>
        </select>
        <select id="selection-formatage-deviation-in" class="proj-file-in input-transfo-fichier">
          <!-- c: eta; x: xi -->
          <option selected disabled>Déviation de la verticale</option>
          <option value="cx">&eta;  &xi;</option>
          <option value="xc">&eta;  &xi;</option>
          <option value="">Aucun</option>
        </select>
        <label for="ligne-start" style="position:relative;top:3px;">Début à la ligne: </label>
        <input type="number" id="ligne-start" value="0" maxlength="3" class="input-transfo-fichier">
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Système de départ</legend>
    <div style="display:flex;">
      <div>
        <label for="type-coord-file-in">Type de coordonnées</label>
        <select id="type-coord-file-in" style="margin-right:20px;">
          <option value="proj">Projetées</option>
          <option value="geog" selected>Géographiques</option>
          <option value="cart">Cartésiennes</option>
        </select>
      </div>
      <div>
        <label for="systeme-plani-file-in">Systèmes</label>
        <select id="systeme-plani-file-in" style="width:150px";>
          <optgroup label="Europe">
            <option value="ETRS89">ETRS89/CHTRF95</option>
          </optgroup>
          <optgroup label="Suisse">
            <option value="CH1903+">CH1903+</option>
            <option value="CH1903">CH1903</option>
          </optgroup>
          <optgroup label="France">
            <option value="RGF93">RGF93</option>
            <option value="NTF">NTF</option>
          </optgroup>
        </select>
      </div>
      <div class="proj-file-in">
        <label for="projecion-file-in">projection choisie</label>
        <select id=projection-file-in>
          <?php echo Afficheprojections(); ?>
        </select>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Système d'arrivée</legend>
    <div style="display:flex;">
      <div>
        <label for="type-coord-file-out">Type de coordonnées</label>
        <select id="type-coord-file-out" style="margin-right:20px;">
          <option value="proj">Projetées</option>
          <option value="geog" selected>Géographiques</option>
          <option value="cart">Cartésiennes</option>
        </select>
      </div>
      <div>
        <label for="systeme-plani-file-out">Système</label>
        <select id="systeme-plani-file-out" style="width:150px";>
          <optgroup label="Europe">
            <option value="ETRS89">ETRS89/CHTRF95</option>
          </optgroup>
          <optgroup label="Suisse">
            <option value="CH1903+">CH1903+</option>
            <option value="CH1903">CH1903</option>
          </optgroup>
          <optgroup label="France">
            <option value="RGF93">RGF93</option>
            <option value="NTF">NTF</option>
          </optgroup>
        </select>
      </div>
      <div class="proj-file-out">
        <label for="projecion-file-out">projection choisie</label>
        <select id=projection-file-out>
          <?php echo Afficheprojections(); ?>
        </select>
      </div>
      <div class="proj-file-out">
          <div style="margin-left: 60px;">
            <label for="systeme-alti-file-out">Système altimétrique (si altitude)</label>
            <select id="systeme-alti-file-out" style="width:150px";>
              <option value="false">--</option>
              <?php echo AfficheSystemesAlti(); ?>
            </select>
        </div>
      </div>
    </div>
    <div class="geog-file-out"></div>
    <div class="cart-file-out"></div>
  </fieldset>
  <fieldset id=fichier-d-arrivee>
    <legend>Fichier en sortie</legend>
    <div id=options-out-file style="display:flex;margin:5px 0">
      <label for="nom-export-file-out" style="margin: 5px 40px;">Nom du fichier en sortie</label>
      <input type="text" id=nom-export-file-out value="export_file.txt" maxlength="40" />
    </div>
    <div style=display:flex;flex-direction:row;align-items:center;>
      <label for="separateur-file-out">Séparateur: </label>
      <input type="text" id=separateur-file-out class=input-transfo-fichier value=";" maxlength="2">
      <div style="margin-left:30px;display:flex;flex-direction:row;">
        <select id="selection-formatage-out" class=input-transfo-fichier>
          <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
          <?php echo AfficheFileConfigs('out'); ?>
        </select>
        <select id="selection-formatage-deviation-out" class="proj-file-out input-transfo-fichier">
          <!-- c: eta; x: xi -->
          <option selected disabled>Déviation de la verticale</option>
          <option value="cx">&eta;  &xi;</option>
          <option value="xc">&eta;  &xi;</option>
          <option value="">Aucun</option>
        </select>
      </div>
    </div>
  </fieldset>
  <div style="display: flex;justify-content:space-around;">
    <button class="button_popup_calc" id="calcul-file">CALCULER</button>
    <button class="button_popup_calc" id="dl-file">TELECHARGER</button>
    <button class="button_popup_calc" id="ajout-carte-file">AJOUTER À LA CARTE</button>
  </div>
</form>

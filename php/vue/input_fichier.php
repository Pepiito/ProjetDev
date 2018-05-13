<fieldset id=fichier-de-depart>
  <legend>Fichier de départ</legend>
  <div class=put-inline style=justify-content:space-around>
    <div id='container-input-file' class=put-inline style=margin-right:20px;>
      <input id="input-file-in" name=input-file-in type="file" multiple/>
      <label for="input-file-in">Parcourir</label>
      <input type=text id=name-import-file-in disabled value="Déposer un fichier ici...">
      <input type=file id=dropped-files style=display:none;  multiple/><!-- Contient les fichiers droppés -->
      <span id=reset-input-file-in>&times;</span>
    </div>
    <div class=put-inline>
      <label for="separateur-file-in">Séparateur: </label>
      <input type="text" id=separateur-file-in class=input-transfo-fichier value=";" maxlength="2">
    </div>
    <span class=infobulle-content><span class=infobulle-base>?<span class=infobulle-pop>Séparateur entre coordonnées. Si multiples espaces ou tab, laissez vide</span></span></span>
    <div class=put-inline>
      <label for="ligne-start-file-in" style="position:relative;margin-left:20px;">Début à la ligne: </label>
      <input type="number" id="ligne-start-file-in" value="0" maxlength="3" min=0 class="input-transfo-fichier">
    </div>
  </div>
  <div class=put-inline>
    <label for=selection-formatage-file-out style="margin-left:20px;">Formatage du fichier :</label>
      <select id="selection-formatage-file-in" class=input-transfo-fichier>
        <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
        <?php echo AfficheFileConfigs('in'); ?>
      </select>
      <select id="selection-formatage-deviation-file-in" class="proj-file-in input-transfo-fichier" style="margin-left: 0px;">
        <!-- c: eta; x: xi -->
        <option value=false selected disabled>Déviation de la verticale</option>
        <option value="cx">&eta;  &xi;</option>
        <option value="xc">&xi;  &eta;</option>
        <option value=false>Aucun</option>
      </select>
  </div>
</fieldset>
<fieldset>
  <legend>Système de départ</legend>
  <div style="display:flex;">
    <div>
      <label for="systeme-plani-file-in">Systèmes</label>
      <select id="systeme-plani-file-in" style="width:150px";>
        <?php echo AfficheSystemesPlani(); ?>
      </select>
    </div>
    <div>
      <label for="type-coord-file-in">Type de coordonnées</label>
      <select id="type-coord-file-in" style="margin-right:20px;">
        <option value="proj">Projetées</option>
        <option value="geog" selected>Géographiques</option>
        <option value="cart">Cartésiennes</option>
      </select>
    </div>
    <div class="proj-file-in">
      <label for="projecion-file-in">Projection</label>
      <select id=projection-file-in>
        <?php echo Afficheprojections('file', 'in'); ?>
      </select>
    </div>
    <div class="proj-file-in geog-file-in">
        <div>
          <label for="systeme-alti-file-in">Système altimétrique (si altitude)</label>
          <select id="systeme-alti-file-in" style="width:150px";>
            <option value="false">--</option>
            <?php echo AfficheSystemesAlti('file', 'in'); ?>
          </select>
      </div>
    </div>
    <div class=geog-file-in>
      <label for=geog-unite-file-in>Unité</label>
      <select id=geog-unite-file-in style=width:150px;>
        <option value=grad>Grades</option>
        <option value=deg selected>Degrés décimaux</option>
        <option value=rad>Radians</option>
      </select>
    </div>
  </div>
</fieldset>
<fieldset>
  <legend>Système d'arrivée</legend>
  <div style="display:flex;">
    <div>
      <label for="systeme-plani-file-out">Système</label>
      <select id="systeme-plani-file-out" style="width:150px";>
          <?php echo AfficheSystemesPlani(); ?>
      </select>
    </div>
    <div>
      <label for="type-coord-file-out">Type de coordonnées</label>
      <select id="type-coord-file-out" style="margin-right:20px;">
        <option value="proj">Projetées</option>
        <option value="geog" selected>Géographiques</option>
        <option value="cart">Cartésiennes</option>
      </select>
    </div>
    <div class="proj-file-out">
      <label for="projecion-file-out">Projection</label>
      <select id=projection-file-out>
        <?php echo Afficheprojections('file', 'out'); ?>
      </select>
    </div>
    <div class="proj-file-out geog-file-out">
        <div>
          <label for="systeme-alti-file-out">Système altimétrique (si altitude)</label>
          <select id="systeme-alti-file-out" style="width:150px";>
            <option value="false">--</option>
            <?php echo AfficheSystemesAlti('file', 'out'); ?>
          </select>
      </div>
    </div>
    <div class=geog-file-out>
      <label for=geog-unite-file-out>Unité</label>
      <select id=geog-unite-file-out style=width:150px;>
        <option value=grad>Grades</option>
        <option value=deg selected>Degrés décimaux</option>
        <option value=rad>Radians</option>
      </select>
    </div>
    <div class="cart-file-out"></div>
  </div>
</fieldset>
<fieldset id=fichier-d-arrivee>
  <legend>Fichier en sortie</legend>
  <div style=display:flex;align-items:center;justify-content:space-around;>
    <div id=options-out-file class=put-inline>
      <div style=display:flex;flex-direction:column>
        <label for="nom-export-file-out">Nom du fichier en sortie</label>
        <input type="text" id=nom-export-file-out value="geofs" maxlength="60" placeholder="Nom du fichier" />
      </div>
      <div style=display:flex;flex-direction:column>
        <label for="extension-file-out">Extension</label>
        <select id=extension-file-out>
          <option value="txt">.txt</option>
          <option value="csv">.csv</option>
        </select>
      </div>
    </div>
    <div class=put-inline>
      <label for=selection-formatage-file-out>Formatage du fichier :</label>
      <select id="selection-formatage-file-out" class=input-transfo-fichier>
        <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
        <?php echo AfficheFileConfigs('out'); ?>
      </select>
      <select id="selection-formatage-deviation-file-out" class="proj-file-out input-transfo-fichier">
        <!-- c: eta; x: xi -->
        <option value=false selected disabled>Déviation de la verticale</option>
        <option value="cx">&eta;  &xi;</option>
        <option value="xc">&eta;  &xi;</option>
        <option value=false>Aucun</option>
      </select>
    </div>
  </div>
</fieldset>
<div style="display: flex;justify-content:space-around;">
  <button class="button_popup_calc" id="dl-file">TELECHARGER</button>
  <button class="button_popup_calc" id="ajout-carte-file">AJOUTER À LA CARTE</button>
</div>

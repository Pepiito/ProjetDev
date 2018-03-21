<form>
  <fieldset id=fichier-de-depart>
    <legend>Fichier de départ</legend>
    <div id='container-input-file' style="display:flex;flex-direction:row;">
      <input id="inputfile" type="file"/>
      <label for="inputfile"><button type="button">Parcourir</button></label>
      <label for="inputfile"><button type="button">Déposer ici</button></label>
    </div>
    <div style=display:flex;flex-direction:row;align-items:center;>
      <label for="separateur">Séparateur: </label>
      <input type="text" id=separateur class=input-transfo-fichier value=";" maxlength="2">
      <div style="margin-left:30px;display:flex;flex-direction:row;">
        <select id="selection-formatage-in" class=input-transfo-fichier>
          <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
          <option value="false" disabled selected>Format du fichier</option>
          <option value="ENH" class="Projetées-file-in">Est  Nord Altitude</option>
          <option value="ENh" class="Projetées-file-in">Est  Nord Hauteur</option>
          <option value="NEH" class="Projetées-file-in">Nord Est  Altitude</option>
          <option value="NEh" class="Projetées-file-in">Nord Est  Hauteur</option>
          <option value="nENH" class="Projetées-file-in">Nom Est  Nord Altitude</option>
          <option value="nENh" class="Projetées-file-in">Nom Est  Nord Hauteur</option>
          <option value="nNEH" class="Projetées-file-in">Nom Nord Est  Altitude</option>
          <option value="nNEh" class="Projetées-file-in">Nom Nord Est  Hauteur</option>
          <option value="lph" class="Geog-file-in">Longitude Latitude  Hauteur</option>
          <option value="lpH" class="Geog-file-in">Longitude Latitude  Altitude</option>
          <option value="plh" class="Geog-file-in">Latitude  Longitude Hauteur</option>
          <option value="plH" class="Geog-file-in">Latitude  Longitude Altitude</option>
          <option value="nlph" class="Geog-file-in">Nom Longitude Latitude  Hauteur</option>
          <option value="nlpH" class="Geog-file-in">Nom Longitude Latitude  Altitude</option>
          <option value="nplh" class="Geog-file-in">Nom Latitude  Longitude Hauteur</option>
          <option value="nplH" class="Geog-file-in">Nom Latitude  Longitude Altitude</option>
          <option value="XYZ" class="Cart-file-in">X Y Z</option>
          <option value="nXYZ" class="Cart-file-in">Nom X Y Z</option>
        </select>
        <select id="selection-formatage-deviation-in" class="Projetées-file-in input-transfo-fichier">
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
        <select name="type-coord" id="type-coord-file-in" style="margin-right:20px;">
          <option value="Projetées" selected>Projetées</option>
          <option value="Geog">Géographiques</option>
          <option value="Cart">Cartésiennes</option>
        </select>
      </div>
      <div>
        <label for="systeme">Systèmes</label>
        <select name="systeme" id="systeme-in" style="width:150px";>
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
        <div class="Projetées-file-in">
          <label for="projecion-file-in">Projection choisie</label>
          <select id=projection-file-in>
            <?php echo getProjections(); ?>
          </select>
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Système d'arrivée</legend>
    <div style="display:flex;">
      <div>
        <label for="type_coord-file-out">Type de coordonnées</label>
        <select name="type_coord-file-out" id="type-coord-file-out" style="margin-right:20px;">
          <option value="Projetées" selected>Projetées</option>
          <option value="Geog">Géographiques</option>
          <option value="Cart">Cartésiennes</option>
        </select>
      </div>
      <div>
        <label for="systeme-file-out">Système</label>
        <select name="systeme-file-out" id="systeme_plani_coord-file-out" style="width:150px";>
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
        <div class="Projetées-file-out">
          <label for="projecion-file-out">Projection choisie</label>
          <select id=projection-file-out>
            <?php echo getProjections(); ?>
          </select>
        </div>
      </div>
      <div class="Projetées-file-out">
          <div id="sys_alti_arrivee_coord" style="margin-left: 60px;">
            <label for="sys_alt-file-out">Système altimétrique (si altitude)</label>
            <select name="sys_alt-file-out" id="sys_alt" style="width:150px";>
              <option value="false">--</option>
              <optgroup label="Suisse" class="Alt_suisse-file-out">
                <option value="RAN95" id="altimetrie_RAN95_coord-file-out">RAN95</option>
                <option value="NF02" id="altimetrie_NF02_coord2">NF02</option>
              </optgroup>
              <optgroup label="France" class="Alt_francais-file-out">
                <option value="IGN69" id="altimetrie_IGN69_coord-file-out">IGN69</option>
              </optgroup>
            </select>
        </div>
      </div>
    </div>
    <div class="geog-file-out"></div>
    <div class="Cart-file-out"></div>
  </fieldset>
  <fieldset id=fichier-d-arrivee>
    <legend>Fichier en sortie</legend>
    <div id=options-out-file>
      <label for="nom-export">Nom du fichier en sortie</label>
      <input type="text" name="" value="export_file.txt" maxlength="30"/>
    </div>
    <div style=display:flex;flex-direction:row;align-items:center;>
      <label for="separateur">Séparateur: </label>
      <input type="text" id=separateur class=input-transfo-fichier value=";" maxlength="2">
      <div style="margin-left:30px;display:flex;flex-direction:row;">
        <select id="selection-formatage-out" class=input-transfo-fichier>
          <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
          <option value="false" disabled selected>Format du fichier</option>
          <option value="ENH" class="Projetées-file-out">Est  Nord Altitude</option>
          <option value="ENh" class="Projetées-file-out">Est  Nord Hauteur</option>
          <option value="NEH" class="Projetées-file-out">Nord Est  Altitude</option>
          <option value="NEh" class="Projetées-file-out">Nord Est  Hauteur</option>
          <option value="nENH" class="Projetées-file-out">Nom Est  Nord Altitude</option>
          <option value="nENh" class="Projetées-file-out">Nom Est  Nord Hauteur</option>
          <option value="nNEH" class="Projetées-file-out">Nom Nord Est  Altitude</option>
          <option value="nNEh" class="Projetées-file-out">Nom Nord Est  Hauteur</option>
          <option value="lph" class="Geog-file-out">Longitude Latitude  Hauteur</option>
          <option value="lpH" class="Geog-file-out">Longitude Latitude  Altitude</option>
          <option value="plh" class="Geog-file-out">Latitude  Longitude Hauteur</option>
          <option value="plH" class="Geog-file-out">Latitude  Longitude Altitude</option>
          <option value="nlph" class="Geog-file-out">Nom Longitude Latitude  Hauteur</option>
          <option value="nlpH" class="Geog-file-out">Nom Longitude Latitude  Altitude</option>
          <option value="nplh" class="Geog-file-out">Nom Latitude  Longitude Hauteur</option>
          <option value="nplH" class="Geog-file-out">Nom Latitude  Longitude Altitude</option>
          <option value="XYZ" class="Cart-file-out">X Y Z</option>
          <option value="nXYZ" class="Cart-file-out">Nom X Y Z</option>
        </select>
        <select id="selection-formatage-deviation-out" class="Projetées-file-out input-transfo-fichier">
          <!-- c: eta; x: xi -->
          <option selected disabled>Déviation de la verticale</option>
          <option value="cx">&eta;  &xi;</option>
          <option value="xc">&eta;  &xi;</option>
          <option value="">Aucun</option>
        </select>

      </div>
    </div>
  </fieldset>
</form>

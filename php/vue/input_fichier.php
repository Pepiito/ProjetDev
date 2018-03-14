<form>
  <fieldset id=fichier_de_depart>
    <legend>Fichier de départ</legend>
    <div style="display:flex;flex-direction:row;">
      <input id="inputfile" type="file"/>
      <label for="inputfile" style="margin:10px 30px"><button type="button">Parcourir</button></label>
      <label for="inputfile"><button type="button">Déposer ici</button></label>
    </div>
    <div style=display:flex;flex-direction:row;>
      <label for="separateur">Séparateur: </label>
      <input type="text" id=separateur class=input_transfo_fichier value=";" maxlength="2">
      <div style="margin_left:50px;display:flex;flex-direction:row;">
        <select id="selection_formatage" class=input_transfo_fichier>
          <!-- n : nom; E: est, N: nord; H: Altitude; h: hauteur; l: longitude; p: latitude; X; Y; Z-->
          <option value="False" disabled selected>Format du fichier</option>
          <option value="ENH" class="projection">Est  Nord Altitude</option>
          <option value="NEH" class="projection">Nord Est  Altitude</option>
          <option value="nENH" class="projection">Nom Est  Nord Altitude</option>
          <option value="nNEH" class="projection">Nom Nord Est  Altitude</option>
          <option value="lph" class="geographique">Longitude Latitude  Hauteur</option>
          <option value="lpH" class="geographique">Longitude Latitude  Altitude</option>
          <option value="plh" class="geographique">Latitude  Longitude Hauteur</option>
          <option value="plH" class="geographique">Latitude  Longitude Altitude</option>
          <option value="nlph" class="geographique">Nom Longitude Latitude  Hauteur</option>
          <option value="nlpH" class="geographique">Nom Longitude Latitude  Altitude</option>
          <option value="nplh" class="geographique">Nom Latitude  Longitude Hauteur</option>
          <option value="nplH" class="geographique">Nom Latitude  Longitude Altitude</option>
          <option value="XYZ" class="cartesien">X Y Z</option>
          <option value="nXYZ" class="cartesien">Nom X Y Z</option>
        </select>
        <select id="selection_formatage_deviation" class="projection input_transfo_fichier">
          <!-- c: eta; x: xi -->
          <option selected disabled>Déviation de la verticale</option>
          <option value="cx">&eta; - &xi;</option>
          <option value="">Aucun</option>
        </select>
        <label for="ligne_start">Début à la ligne: </label>
        <input type="number" id="ligne_start" value="0" maxlength="3" class="input_transfo_fichier">
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend>Système de départ:</legend>
    <div style="display:flex;">
      <div>
        <label for="type_coord_transfo_fichier">Type de coordonnées</label>
        <select name="type_coord" id="type_coord_transfo_fichier" style="margin-right:20px;">
          <option value="Projetées" selected>Projetées</option>
          <option value="Geog">Géographiques</option>
          <option value="Cart">Cartésiennes</option>
        </select>
      </div>
      <div>
        <label for="systeme">Systèmes</label>
        <select name="systeme" id="systeme" style="width:150px";>
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
    </div>
  </fieldset>
</form>

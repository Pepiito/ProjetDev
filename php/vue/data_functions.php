<?php
function Afficheprojections() {
  return "<optgroup label=France>
    <option value=Lambert1>Lambert 1</option>
    <option value=Lambert2>Lambert 2</option>
    <option value=Lambert3>Lambert 3</option>
    <option value=Lambert4>Lambert 4</option>
    <option value=Lambert2etendu>Lambert 2 étendu</option>
    <option value=Lambert93>Lambert 93</option>
    <option value=CC42>CC42</option>
    <option value=CC43>CC43</option>
    <option value=CC44>CC44</option>
    <option value=CC45>CC45</option>
    <option value=CC46>CC46</option>
    <option value=CC47>CC47</option>
    <option value=CC48>CC48</option>
    <option value=CC49>CC49</option>
    <option value=CC50>CC50</option>
    <option value=CC51>CC51</option>
  </optgroup>
  <optgroup label=Suisse>
    <option value=CH1905>CH1905</option>
      <option value=??>??</option>
  </optgroup>";
}

function AfficheSystemesPlani() {
  return "
  <optgroup label=Europe>
    <option value=ETRS89>ETRS89/CHTRF95</option>
  </optgroup>
  <optgroup label=Suisse>
    <option value=CH1903+>CH1903+</option>
    <option value=CH1903>CH1903</option>
  </optgroup>
  <optgroup label=France>
    <option value=RGF93>RGF93</option>
    <option value=NTF>NTF</option>
  </optgroup>";
}

function AfficheSystemesAlti() {
  return "
  <optgroup label=Suisse>
    <option value=RAN95>RAN95</option>
    <option value=NF02>NF02</option>
  </optgroup>
  <optgroup label=France>
    <option value=IGN69>IGN69</option>
  </optgroup>";
}

function AfficheCoord($inout) {
  $disable = "";
  $hide = "";
  if ($inout == "out") {
    $hide = "style=display:none;";
    $disable = "disabled";
  }

  return "
  <div id=proj-point-$inout class='inputs-coord'>"
    .
    addInput('proj', 'est', 'Est', $inout)
    .
    addInput('proj', 'nord', 'Nord', $inout)
    .
    addInput('proj', 'hauteur', 'Hauteur [m]', $inout)
    .
    addInput('proj', 'altitude', 'Alitude [m]', $inout)
    . "
    <div class=proj-point-$inout>
      <label>Déviation de la véritcale &eta; - &xi;</label>
      <input type=text id=coord-proj-eta-point-$inout $disable>
      <input type=text id=coord-proj-xi-point-$inout $disable>
    </div>"
    .
    addInput('proj', 'cote', 'Cote du géoïde', $inout)
    . "
  </div>
  <div id=geog-point-$inout class='inputs-coord'>"
    .
    addInput('geog', 'lng', 'Longitude', $inout)
    .
    addInput('geog', 'lat', 'Latitude', $inout)
    .
    addInput('geog', 'hauteur', 'Hauteur [m]', $inout)
    .
    addInput('geog', 'altitude', 'Alitude [m]', $inout)
    . "
    <div $hide class=geog-point-$inout>
      <label for=geog-unite-$inout>Unité</label>
      <select id=geog-unite-$inout style=width:150px;>
        <option value=grad>Grades</option>
        <option value=deg>Degrés centésimaux - décimaux</option>
        <option value=rad>Radians</option>
      </select>
    </div>
  </div>
  <div id=cart-point-$inout class='inputs-coord'>"
    .
    addInput('cart', 'x', 'X [m]', $inout)
    .
    addInput('cart', 'y', 'Y [m]', $inout)
    .
    addInput('cart', 'z', 'Z [m]', $inout)
    . "
  </div>
  ";
}

function addInput($typecoord, $value, $label, $inout) {
  $disable = "";
  if ($inout == "out") $disable = "disabled";
  return "
  <div class=$typecoord-point-$inout>
    <label for=coord-$typecoord-$value-point-$inout>$label</label>
    <input type=text id=coord-$typecoord-$value-point-$inout $disable>
  </div>
  ";
}

function AfficheFileConfigs($inout) {
  return "
  <option value=false disabled selected>Format du fichier</option>
  <option value=ENH class=proj-file-$inout>Est  Nord Altitude</option>
  <option value=ENh class=proj-file-$inout>Est  Nord Hauteur</option>
  <option value=NEH class=proj-file-$inout>Nord Est  Altitude</option>
  <option value=NEh class=proj-file-$inout>Nord Est  Hauteur</option>
  <option value=nENH class=proj-file-$inout>Nom Est  Nord Altitude</option>
  <option value=nENh class=proj-file-$inout>Nom Est  Nord Hauteur</option>
  <option value=nNEH class=proj-file-$inout>Nom Nord Est  Altitude</option>
  <option value=nNEh class=proj-file-$inout>Nom Nord Est  Hauteur</option>
  <option value=lph class=geog-file-$inout>Longitude Latitude  Hauteur</option>
  <option value=lpH class=geog-file-$inout>Longitude Latitude  Altitude</option>
  <option value=plh class=geog-file-$inout>Latitude  Longitude Hauteur</option>
  <option value=plH class=geog-file-$inout>Latitude  Longitude Altitude</option>
  <option value=nlph class=geog-file-$inout>Nom Longitude Latitude  Hauteur</option>
  <option value=nlpH class=geog-file-$inout>Nom Longitude Latitude  Altitude</option>
  <option value=nplh class=geog-file-$inout>Nom Latitude  Longitude Hauteur</option>
  <option value=nplH class=geog-file-$inout>Nom Latitude  Longitude Altitude</option>
  <option value=XYZ class=cart-file-$inout>X Y Z</option>
  <option value=nXYZ class=cart-file-$inout>Nom X Y Z</option>
  ";
}
?>

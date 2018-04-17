<?php
function Afficheprojections($type, $inout) {
  return "<optgroup label=France class='NTF-$type-$inout RGF93-$type-$inout'>
    <option value=Lambert1 class='NTF-$type-$inout'>Lambert 1</option>
    <option value=Lambert2 class='NTF-$type-$inout'>Lambert 2</option>
    <option value=Lambert3 class='NTF-$type-$inout'>Lambert 3</option>
    <option value=Lambert4 class='NTF-$type-$inout'>Lambert 4</option>
    <option value=Lambert2etendu class='NTF-$type-$inout'>Lambert 2 étendu</option>
    <option value=Lambert93 class='RGF93-$type-$inout' selected>Lambert 93</option>
    <option value=CC42 class='RGF93-$type-$inout'>CC42</option>
    <option value=CC43 class='RGF93-$type-$inout'>CC43</option>
    <option value=CC44 class='RGF93-$type-$inout'>CC44</option>
    <option value=CC45 class='RGF93-$type-$inout'>CC45</option>
    <option value=CC46 class='RGF93-$type-$inout'>CC46</option>
    <option value=CC47 class='RGF93-$type-$inout'>CC47</option>
    <option value=CC48 class='RGF93-$type-$inout'>CC48</option>
    <option value=CC49 class='RGF93-$type-$inout'>CC49</option>
    <option value=CC50 class='RGF93-$type-$inout'>CC50</option>
    <option value=CC51 class='RGF93-$type-$inout'>CC51</option>
  </optgroup>
  <optgroup label=Suisse class='CH1903+-$type-$inout ETRS89-$type-$inout'>
    <option value=MN95 class='CH1903+-$type-$inout'>MN95</option>
    <option value=MN03 class='CH1903-$type-$inout'>MN03</option>
    <option value=false class='ETRS89-$type-$inout' disabled>Pas de projection</option>
  </optgroup>";
}

function AfficheSystemesPlani() {
  return "
  <optgroup label=Europe>
    <option value=ETRS89>ETRS89/CHTRS95</option>
  </optgroup>
  <optgroup label=Suisse>
    <option value=CH1903+>CH1903+</option>
    <option value=CH1903>CH1903</option>
  </optgroup>
  <optgroup label=France>
    <option value=RGF93 selected>RGF93</option>
    <option value=NTF>NTF</option>
  </optgroup>";
}

function AfficheSystemesAlti($type, $inout) {
  return "
  <optgroup label=Suisse class='CH1903-$type-$inout CH1903+-$type-$inout'>
    <option value=RAN95 class='CH1903-$type-$inout CH1903+-$type-$inout'>RAN95</option>
    <option value=NF02 class='CH1903-$type-$inout CH1903+-$type-$inout'>NF02</option>
  </optgroup>
  <optgroup label=France class='RGF93-$type-$inout NTF-$type-$inout'>
    <option value=IGN69 class='RGF93-$type-$inout NTF-$type-$inout'>IGN69</option>
  </optgroup>
  <option value=false class='ETRS89-$type-$inout' disabled>Pas de système altimétrique</option>
  ";
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
    addInput('proj', 'hauteur', 'Hauteur [m]', $inout, "proj-hauteur-point-".$inout)
    .
    addInput('proj', 'altitude', 'Alitude [m]', $inout, "proj-alti-point-".$inout)
    . "
    <div class=proj-point-$inout>
      <label>Déviation de la véritcale &eta; - &xi;</label>
      <input type=text id=coord-eta-point-$inout $disable>
      <input type=text id=coord-xi-point-$inout $disable>
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
    addInput('geog', 'hauteur', 'Hauteur [m]', $inout, "geog-hauteur-point-".$inout)
    .
    addInput('geog', 'altitude', 'Alitude [m]', $inout, "geog-alti-point-".$inout)
    . "
    <div $hide class=geog-point-$inout>
      <label for=geog-unite-point-$inout>Unité</label>
      <select id=geog-unite-point-$inout style=width:150px;>
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

function addInput($typecoord, $value, $label, $inout, $addedclasses="") {
  $disable = "";
  $classes = "class='$typecoord-point-$inout " . $addedclasses . "'";
  if ($inout == "out") $disable = "disabled";
  return "
  <div $classes>
    <label for=coord-$value-point-$inout>$label</label>
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

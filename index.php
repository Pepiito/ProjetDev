<?php if (!isset($_SESSION)) session_start(); ?>
<?php include("./php/vue/head.php"); ?>

<?php
function getProjections() {
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
} ?>


  <body>
    <div class="corps">
	<div class="legende">
	<?php include("./php/vue/legende.php"); ?>
	</div>
	<div style="width:100%;position:relative;">
	<div id="map">
	</div>
	<div class="button_transfo"><input class="button_tran" type="submit" onclick="Open_transfo()" value="Changement de coordonnées"/></div>
	<div class="school"><img src="images/ensg-heig.png" width="350" height="81"/></div>
	</div>
	</div>
	<?php include("./php/vue/change_coord.php"); ?>
	<script src="lib/proj4.js"></script>
    <script src="lib/proj4-epsg21781.js"></script>
    <script src="lib/proj4-epsg2056.js"></script>
	<script src="lib/proj4-epsg2154.js"></script>
	<script src="lib/proj4-epsg4275.js"></script>
    <script src="js/map.js"></script>
    <script type="text/javascript" src="./js/controleur.js"></script>
  </body>
</html>

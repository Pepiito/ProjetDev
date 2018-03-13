<div id="popup" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<div id="pop_body">
		<div id="pop_header">
			<p id="head1" onclick="show_trans_coord()">Transformation de coordonnées</p>
			<p id="head2" onclick="show_trans_fichier()">Transformation via fichier</p>
		</div>
		<div id="trans_coord">
			<form>
				<fieldset>
				<legend>Système de départ:</legend>
				<div style="display:flex;">
				<div>
				<label for="type_coord">Type de coordonnées</label>
				<select name="type_coord" onchange="change_typecoord(this.value)" id="type_coord" style="margin-right:20px;">
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
				<div id="Projetées">
				<div style="display:flex;">
				<div>
				<label for="sys_alt">Système altimétrique</label>
				<select name="sys_alt" id="sys_alt" style="width:150px";>
					<optgroup label="Suisse">
						<option value="RAN95">RAN95</option>
						<option value="NF02">NF02</option>
					</optgroup>
					<optgroup label="France">
						<option value="">IGN69</option>
					</optgroup>
				</select>
				</div>
				</div>
				<div style="display:flex;">
				<div>
				<label for="Est">Est</label>
				<input type="n" name="Est">
				</div>
				<div>
				<label for="Nord">Nord</label>
				<input type="text" name="Est">
				</div>
				<div>
				<label for="Altitude">Altitude</label>
				<input type="text" name="Est">
				</div>
				<div>
				<label for="verticale" style="display:flex">Déviation de la véritcale<p style="font-family: 'Greek', Sherif;margin:0;">c-x</p></label>
				<input type="text" name="Vert1" id="vert">
				<input type="text" name="Vert2" id="vert">
				</div>
				<div>
				<label for="Cote">Cote du Géoid</label>
				<input type="text" name="Cote">
				</div>
				</div>
				</div>
				<div id="Geog">
				<div style="display:flex;">
				<div>
				<label for="Long">Long</label>
				<input type="text" name="Long">
				</div>
				<div>
				<label for="Lat">Lat</label>
				<input type="text" name="Lat">
				</div>
				<div>
				<label for="hauteur">hauteur</label>
				<input type="text" name="hauteur">
				</div>
				</div>
				</div>
				<div id="Cart">
				<div style="display:flex;">
				<div>
				<label for="X">X</label>
				<input type="text" name="X">
				</div>
				<div>
				<label for="Y">Y</label>
				<input type="text" name="Y">
				</div>
				<div>
				<label for="Z">Z</label>
				<input type="text" name="Z">
				</div>
				</div>
				</div>
				</fieldset>
				<fieldset>
				<legend>Système d'arrivée:</legend>
				<div style="display:flex;">
				<div>
				<label for="type_coord2">Type de coordonnées</label>
				<select name="type_coord2" onchange="change_typecoord2(this.value)" id="type_coord2" style="margin-right:20px;">
					<option value="Projetées" selected>Projetées</option>
					<option value="Geog">Géographiques</option>
					<option value="Cart">Cartésiennes</option>
				</select>
				</div>
				<div>
				<label for="systeme2">Système</label>
				<select name="systeme2" id="systeme2" style="width:150px";>
						<option value="ETRS89">ETRS89</option>
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
				<div>
				</div>
				</fieldset>
			</form>
		</div>
		<div id="trans_fichier">
			<p>essai_fichier</p>
		</div>
		
		
		</div>
		
		
	</div>
</div>
<script type="text/javascript">
var body_trans1 = document.getElementById('trans_coord');
var body_trans2 = document.getElementById('trans_fichier');
var head_trans1 = document.getElementById('head1');
var head_trans2 = document.getElementById('head2');
var type_coord = document.getElementById('type_coord');

function show_trans_coord() {
	body_trans1.style.display="block";
	body_trans2.style.display="none";
	head_trans1.style.borderWidth="0px 1px 0px 0px";
	head_trans2.style.borderWidth="0px 0px 2px 1px";
}
function show_trans_fichier() {
	body_trans2.style.display="block";
	body_trans1.style.display="none";
	head_trans1.style.borderWidth="0px 1px 2px 0px";
	head_trans2.style.borderWidth="0px 0px 0px 1px";
}

function change_typecoord(type){
	console.log(type)
	switch(type){
		case "Projetées":
			Projetées.style.display="block";
			Geog.style.display="none";
			Cart.style.display="none";
			break;
		case "Geog":
			Projetées.style.display="none";
			Geog.style.display="block";
			Cart.style.display="none";
			break;
		case "Cart":
			Projetées.style.display="none";
			Geog.style.display="none";
			Cart.style.display="block";
			break;
	}	
}
</script>
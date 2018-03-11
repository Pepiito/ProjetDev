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
				<label for="type_coord">Type de coordonnées</label>
				<select name="type_coord" id="type_coord">
					<option value="Projetées">Projetées</option>
					<option value="Géographiques">Géographiques</option>
					<option value="Cartésiennes">Cartésiennes</option>
				</select>
				<div id="Projetées">
				<div>
				<label for="Est">Est</label>
				<input type="text" name="Est">
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
				</fieldset>
				<fieldset>
				<legend>Système d'arrivée:</legend>
				
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
</script>
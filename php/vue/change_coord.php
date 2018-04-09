<div id="popup" class="modal">
	<center class="modal-content">
		<span class="close">&times;</span>
		<div id="pop_body">
			<div id="pop_header">
				<p id="head_trans_coord">Transformation de coordonnées</p>
				<p id="head_trans_fichier">Transformation via fichier</p>
			</div>
			<div id="trans_coord" class="form_transfo">
				<fieldset>
					<legend>Système de départ:</legend>
					<div style="display:flex;">
						<div>
							<label for="type-coord-point-in">Type de coordonnées</label>
							<select name="type-coord-point-in" id="type-coord-point-in" style="margin-right:20px;">
							<option value="proj">Projetées</option>
								<option value="geog" selected>Géographiques</option>
								<option value="cart">Cartésiennes</option>
							</select>
						</div>
						<div>
							<label for="systeme-plani-point-in">Systèmes</label>
							<select name="systeme-plani-point-in" id="systeme-plani-point-in" style="width:150px";>
								<?php echo AfficheSystemesPlani(); ?>
							</select>
						</div>
					</div>
					<div style="display:flex;align-items:center;margin:15px 0;">

						<div id="type-alti-point-in" class='type-alti proj-point-in geog-point-in'>
							<input type="radio" id="type-alti-altitude-point-in" name="altimetrie_depart" checked>
							<label for="type-alti-altitude-point-in" style="margin:auto 10px auto 0;">Altitude</label>
							<input type="radio" id="type-alti-hauteur-point-in" name="altimetrie_depart">
							<label for="type-alti-hauteur-point-in" style="margin:auto 10px auto 0;" >Hauteur ellipsoïdale</label>
						</div>

						<div class="proj-point-in geog-point-in">
							<label for="systeme-alti-point-in" class="systeme-alti-point-in proj-alti-point-in geog-alti-point-in">Système altimétrique</label>
							<select id="systeme-alti-point-in" class="systeme-alti-point-in proj-alti-point-in geog-alti-point-in">
								<?php echo AfficheSystemesAlti(); ?>
							</select>
						</div>

						<div class="proj-point-in">
							<label for=projection-point-in>Projection choisie</label>
							<select id=projection-point-in>
								<?php echo Afficheprojections('point', 'in'); ?>
							</select>
						</div>
					</div>
					<?php echo AfficheCoord("in"); ?>
				</fieldset>
				<fieldset>
					<legend>Système d'arrivée:</legend>
					<div style="display:flex;">
						<div>
							<label for="type-coord-point-out">Type de coordonnées</label>
							<select name="type-coord-point-out" id="type-coord-point-out" style="margin-right:20px;">
							<option value="proj">Projetées</option>
								<option value="geog" selected>Géographiques</option>
								<option value="cart">Cartésiennes</option>
							</select>
						</div>
						<div>
							<label for="systeme-plani-point-out">Système</label>
							<select id="systeme-plani-point-out" style="width:150px";>
								<?php echo AfficheSystemesPlani(); ?>
							</select>
						</div>
					</div>
					<div style="display:flex;align-items:center;margin:15px 0;">
						<div id="type-alti-point-out" class='type-alti proj-point-out geog-point-out'>
							<input type="radio" id="type-alti-altitude-point-out" name="altimetrie_arrivee" checked>
							<label for="type-alti-altitude-point-out">Altitude</label>
							<input type="radio" id="type-alti-hauteur-point-out" name="altimetrie_arrivee">
							<label for="type-alti-hauteur-point-out-">Hauteur ellipsoïdale</label>
						</div>
						<div class='proj-point-out geog-point-out'>
							<label for="systeme-alti-point-out" class="systeme-alti-point-out proj-alti-point-out geog-alti-point-out">Système altimétrique</label>
							<select id="systeme-alti-point-out" class="systeme-alti-point-out proj-alti-point-out geog-alti-point-out">
								<?php echo AfficheSystemesAlti(); ?>
							</select>
						</div>

						<div class="proj-point-out">
							<label for="projecion-point-out">Projection choisie</label>
							<select id=projection-point-out >
								<?php echo Afficheprojections('point', 'out'); ?>
							</select>
						</div>
					</div>
					<?php echo AfficheCoord("out"); ?>
				</fieldset>
				<div style="display: flex;justify-content:space-around;">
					<button class="button_popup_calc" id="calcul-point">CALCULER</button>
					<button class="button_popup_calc" id="ajout-carte-point">AJOUTER À LA CARTE</button>
				</div>
			</div>
			<div id="trans_fichier" class="form_transfo">
				<?php include("./php/vue/input_fichier.php"); ?>
			</div>
		</div>
	</center>
</div>

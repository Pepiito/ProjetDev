<div id="popup" class="modal">
	<div id=loader-filtre>
		<div id=loader-content class=loading>
			<div id=loader-message>
				Message généré JS
			</div>
			<div id="loader-glissement">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
	     </div>
			 <div class=loader-close>
 				<span class="close">&times;</span>
 			</div>
		</div>
		<div id=error-content class=loading>
			<div id=error-message>
				Message généré JS
			</div>
			<div id=error-ok>
				OK
			</div>
			<div class=loader-close>
			 <span class="close">&times;</span>
		 </div>
		</div>
	</div>
	<div class="modal-content">
		<span class="close" id=close-popup>&times;</span>
		<div id="pop_body">
			<div id="pop_header">
				<div id="head_point" class=head>
					<p>Transformation de coordonnées</p>
					<div id=underline_point class=underline></div>
				</div>
				<div id="head_fichier" class=head>
					<p>Transformation par fichier</p>
					<div id=underline_fichier class=underline></div>
				</div>
			</div>
			<div id="trans_point" class="form_transfo">
				<fieldset>
					<legend>Système de départ:</legend>
					<div style="display:flex;">
						<div>
							<label for="systeme-plani-point-in">Système</label>
							<select name="systeme-plani-point-in" id="systeme-plani-point-in" style="width:150px";>
								<?php echo AfficheSystemesPlani(); ?>
							</select>
						</div>
						<div>
							<label for="type-coord-point-in">Type de coordonnées</label>
							<select name="type-coord-point-in" id="type-coord-point-in" style="margin-right:20px;">
							<option value="proj">Projetées</option>
								<option value="geog" selected>Géographiques</option>
								<option value="cart">Cartésiennes</option>
							</select>
						</div>
						<div class="proj-point-in">
							<label for=projection-point-in>Projection choisie</label>
							<select id=projection-point-in>
								<?php echo Afficheprojections('point', 'in'); ?>
							</select>
						</div>
						<p class='infobulle-content proj-point-in'><p class='infobulle-base proj-point-in'>?<span class=infobulle-pop>Choisissez la projection que vous souhaitez. Ne sont disponibles que celles existantes dans le sysème de référence que vous avez sélectionné</span></p></p>
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
								<?php echo AfficheSystemesAlti('point', 'in'); ?>
							</select>
						</div>
					</div>
					<?php echo AfficheCoord("in"); ?>
				</fieldset>
				<fieldset>
					<legend>Système d'arrivée:</legend>
					<div style="display:flex;">
						<div>
							<label for="systeme-plani-point-out">Système</label>
							<select id="systeme-plani-point-out" style="width:150px";>
								<?php echo AfficheSystemesPlani(); ?>
							</select>
						</div>
						<div>
							<label for="type-coord-point-out">Type de coordonnées</label>
							<select name="type-coord-point-out" id="type-coord-point-out" style="margin-right:20px;">
							<option value="proj">Projetées</option>
								<option value="geog" selected>Géographiques</option>
								<option value="cart">Cartésiennes</option>
							</select>
						</div>
						<div class="proj-point-out">
							<label for="projecion-point-out">Projection choisie</label>
							<select id=projection-point-out >
								<?php echo Afficheprojections('point', 'out'); ?>
							</select>
						</div>
						<p class='infobulle-content proj-point-out'><p class='infobulle-base proj-point-out'>?<span class=infobulle-pop>Choisissez la projection que vous souhaitez. Ne sont disponibles que celles existantes dans le sysème de référence que vous avez sélectionné</span></p></p>
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
								<?php echo AfficheSystemesAlti('point', 'out'); ?>
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
	</div>
</div>

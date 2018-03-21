type-coord-point-out<div id="popup" class="modal">
	<center class="modal-content">
		<span class="close">&times;</span>
		<div id="pop_body">
			<div id="pop_header">
				<p id="head1">Transformation de coordonnées</p>
				<p id="head2">Transformation via fichier</p>
			</div>
			<div id="trans_coord" class="form_transfo">
				<form>
					<fieldset>
						<legend>Système de départ:</legend>
						<div style="display:flex;">
							<div>
								<label for="type-coord-point-in">Type de coordonnées</label>
								<select name="type-coord-point-in" id="type-coord-point-in" style="margin-right:20px;">
									<option value="Projetées" selected>Projetées</option>
									<option value="Geog">Géographiques</option>
									<option value="Cart">Cartésiennes</option>
								</select>
							</div>
							<div>
								<label for="systeme">Systèmes</label>
								<select name="systeme" id="systeme_plani_coord" style="width:150px";>
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
						<div style="display:flex;margin:15px 0 15px 0;height:40px;">
							<div id="type_altimetre_projetee_coord">
							<input type="radio" id="altimetrieChoice_alti"
							name="altimetrie_depart" value="altitude">
							<label for="altimetrieChoice_alti" style="margin:auto 10px auto 0;">Altitude</label>
							<input type="radio" id="altimetrieChoice_hauteur"
							name="altimetrie_depart" value="hauteur" checked>
							<label for="altimetrieChoice_hauteur" style="margin:auto 10px auto 0;" >Hauteur ellipsoïdale</label>
							</div>
							<div id="sys_alti_depart_coord">
								<label for="sys_alt">Système altimétrique</label>
								<select name="sys_alt" id="sys_alt" style="width:150px";>
									<optgroup label="Suisse" class="Alt_suisse">
										<option value="RAN95" id="altimetrie_RAN95_coord">RAN95</option>
										<option value="NF02" id="altimetrie_NF02_coord">NF02</option>
									</optgroup>
									<optgroup label="France" class="Alt_francais">
										<option value="IGN69" id="altimetrie_IGN69_coord">IGN69</option>
									</optgroup>
								</select>
							</div>
							<div class="Projetées-point-in">
								<label for="projecion-point-in">Projection choisie</label>
								<select id=projection-point-in >
									<?php echo getProjections(); ?>
								</select>
							</div>
						</div>
						<div class="Projetées-point-in">
							<div style="display:flex;">
								<div>
									<label for="Est">Est [m]</label>
									<input type="text" name="Est">
								</div>
								<div>
									<label for="Nord">Nord [m]</label>
									<input type="text" name="Nord">
								</div>
								<div>
									<label for="Altitude" id="label_input_alti_projetee_coord">Hauteur [m]</label>
									<input type="text" name="H">
								</div>
								<div>
									<label for="verticale" style="display:flex">Déviation de la véritcale &eta; - &xi;</label>
									<input type="text" name="Vert1" id="vert">
									<input type="text" name="Vert2" id="vert">
								</div>
								<div>
									<label for="Cote">Cote du Géoid</label>
									<input type="text" name="Cote">
								</div>
							</div>
						</div>
						<div class="geog-point-in">
							<div style="display:flex; margin:15px 0 0 0;">
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
								<div>
									<label for="geog_unite_depart">Unités</label>
									<select name="geog_unite_depart" id="geog_unite_coord" style="width:150px";>
										<option value="grade" id="geog_unite_coord_grade">Grades</option>
										<option value="degre" id="geog_unite_coord_degre">Degrés centésimaux</option>
										<option value="rad" id="geog_unite_coord_rad">Radians</option>
									</select>
								</div>
							</div>
						</div>
						<div class="Cart-point-in">
							<div style="display:flex;margin:15px 0 0 0;">
								<div>
									<label for="X">X [m]</label>
									<input type="text" name="X">
								</div>
								<div>
									<label for="Y">Y [m]</label>
									<input type="text" name="Y">
								</div>
								<div>
									<label for="Z">Z [m]</label>
									<input type="text" name="Z">
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Système d'arrivée:</legend>
						<div style="display:flex;">
							<div>
								<label for="type-coord-point-out">Type de coordonnées</label>
								<select name="type-coord-point-out" id="type-coord-point-out" style="margin-right:20px;">
									<option value="Projetées" selected>Projetées</option>
									<option value="Geog">Géographiques</option>
									<option value="Cart">Cartésiennes</option>
								</select>
							</div>
							<div>
								<label for="systeme2">Système</label>
								<select name="systeme2" id="systeme_plani_coord2" style="width:150px";>
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
						<div class="Projetées-point-out">
							<label for="projecion-point-out">Projection choisie</label>
							<select id=projection-point-out >
								<?php echo getProjections(); ?>
							</select>
						</div>
						<div style="display:flex;margin:15px 0 15px 0;height:40px;">
							<div id="type_altimetre_projetee_coord2">
							<input type="radio" id="altimetrieChoice_alti2"
							name="altimetrie_depart2" value="altitude">
							<label for="altimetrieChoice_alti2" style="margin:auto 10px auto 0;">Altitude</label>
							<input type="radio" id="altimetrieChoice_hauteur2"
							name="altimetrie_depart2" value="hauteur" checked>
							<label for="altimetrieChoice_hauteur2" style="margin:auto 10px auto 0;" >Hauteur ellipsoïdale</label>
							</div>
							<div id="sys_alti_arrivee_coord">
								<label for="sys_alt2">Système altimétrique</label>
								<select name="sys_alt2" id="sys_alt" style="width:150px";>
									<optgroup label="Suisse" class="Alt_suisse2">
										<option value="RAN95" id="altimetrie_RAN95_coord2">RAN95</option>
										<option value="NF02" id="altimetrie_NF02_coord2">NF02</option>
									</optgroup>
									<optgroup label="France" class="Alt_francais2">
										<option value="IGN69" id="altimetrie_IGN69_coord2">IGN69</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="Projetées-point-out">
							<div style="display:flex;">
								<div>
									<label for="Est2">Est [m]</label>
									<input type="text" name="Est2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="Nord2">Nord [m]</label>
									<input type="text" name="Nord2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="H2" id="label_input_alti_projetee_coord2">Hauteur [m]</label>
									<input type="text" name="H2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="verticale" style="display:flex">Déviation de la véritcale &eta; - &xi;</label>
									<input type="text" name="Vert1_2" id="vert" class="input_calc_coord" disabled>
									<input type="text" name="Vert2_2" id="vert" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="Cote2">Cote du Géoid</label>
									<input type="text" name="Cote2" class="input_calc_coord" disabled>
								</div>
							</div>
						</div>
						<div class="geog-point-out">
							<div style="display:flex; margin:15px 0 0 0;">
								<div>
									<label for="Long2">Long</label>
									<input type="text" name="Long2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="Lat2">Lat</label>
									<input type="text" name="Lat2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="hauteur2">hauteur</label>
									<input type="text" name="hauteur2" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="geog_unite_arrivee">Unités</label>
									<select name="geog_unite_arrivee" id="geog_unite_coord" style="width:150px";>
										<option value="grade" id="geog_unite_coord_grade">Grades</option>
										<option value="degre" id="geog_unite_coord_degre">Degrés centésimaux</option>
										<option value="rad" id="geog_unite_coord_rad">Radians</option>
									</select>
								</div>
							</div>
						</div>
						<div class="Cart-point-out">
							<div style="display:flex;margin:15px 0 0 0;">
								<div>
									<label for="X">X [m]</label>
									<input type="text" name="X" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="Y">Y [m]</label>
									<input type="text" name="Y" class="input_calc_coord" disabled>
								</div>
								<div>
									<label for="Z">Z [m]</label>
									<input type="text" name="Z" class="input_calc_coord" disabled>
								</div>
							</div>
						</div>
					</fieldset>
					<div style="display: flex;justify-content:space-between;">
					<!--<input type="submit" class="button_popup_calc" value="CALCULER LES COORDONNÉES"/>
					<input type="submit" class="button_popup_calc" value="AJOUTER À LA CARTE"/>-->
					<div><p class="button_popup_calc">CALCULER LES COORDONNÉES<p/></div>
					<div><p class="button_popup_calc">AJOUTER À LA CARTE<p/></div>
					</div>

				</form>
			</div>
			<div id="trans_fichier" class="form_transfo">
				<?php include("./php/vue/input_fichier.php"); ?>
			</div>
		</div>
	</center>
</div>

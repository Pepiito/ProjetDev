<!-- HEIG-vd -->
<input class="recherche" type="search" placeholder="Recherche par points"/>
	<form>
	<div id="title_theme">
	<div id="points_fixes_title" style="display:flex;">
	<i id="symbol_list_pts"></i>
	<span id="span_pts"><p>Liste des points pouvant être affichés sur la carte</p></span>
	<h3>
       Liste des points:</h3>
	   </div>
	</div>
	
   <div id="point_fixe_map">
   <div class="border_legende">
	   <p><u>Français</u></p>
       <p>Ne sont pas encore importés</p>
	   <p><u>Suisse</u></p>
       <div style="height:25px;"><input type="checkbox" name="PFP1" id="PFP1_leg"/> <label for="PFP1">PFP1</label><img src="icon_map/PFP1.svg">  </div>
	   <div style="height:25px;"><input type="checkbox" name="PFP2" id="PFA1_leg"/> <label for="PFA1">PFA1</label><img src="icon_map/PFA1.svg"></div>
	   <?php
	   if(isset($_SESSION['pseudo'])){
			echo '<p><u>Points de '.$_SESSION['pseudo'].'</u></p>';
		    echo '<select id="liste_chantier">';
		   
			$result = pg_query($conn, 'SELECT date_chantier FROM "Points_session" WHERE id_sess='.$_SESSION['id'].';');
			if (!$result) {
				echo "Une erreur s'est produite.\n";
				exit;
			}
			
			while ($row = pg_fetch_row($result)) {
				echo '<option value='.$row[0].'>'.$row[0].'</option>';
			}
			echo '</select>';
	   }

		
		?>
	</div>
	</div>
	<div id="title_theme">
	<div id="sys_plani_title"  style="display:flex;">
	<i id="symbol_list_plani"></i>
	<h3>Systèmes de coordonnées</h3>
	<span id="span_plani"><p>Les systèmes des coordonnées pouvant être affichés dans la popup en cliquant sur un point</p></span>
   </div>
   </div>
       
	   <div id="sys_plani_leg">
	   <div class="border_legende">
	   <p><u>Coordonnées projetées</u></p>
	   <div><input type="checkbox" name="ch1903_proj_map" id="ch1903_proj_map"/> <label for="ch1903_proj_map">CH1903</label></div>
	   <div><input type="checkbox" name="ch1903plus_proj_map" id="ch1903plus_proj_map" /> <label for="ch1903plus_proj_map">CH1903+</label></div>
	   <div><input type="checkbox" name="rgf_proj_map" id="rgf_proj_map" /> <label for="rgf_proj_map">RGF Lambert-93</label></div>
	   <div><input type="checkbox" name="rgf_proj_C46_map" id="rgf_proj_C46_map" /> <label for="rgf_proj_C46_map">RGF C46</label></div>
	   <div><input type="checkbox" name="rgf_proj_C47_map" id="rgf_proj_C47_map" /> <label for="rgf_proj_C47_map">RGF C47</label></div>
	   <div><input type="checkbox" name="rgf_proj_C48_map" id="rgf_proj_C48_map" /> <label for="rgf_proj_C48_map">RGF C48</label></div>
	   <div><input type="checkbox" name="ntf_proj_Etendu_map" id="ntf_proj_Etendu_map" /> <label for="ntf_proj_Etendu_map">NTF II Etendu</label></div>
	   <div><input type="checkbox" name="ntf_proj_2_map" id="ntf_proj_2_map" /> <label for="ntf_proj_2_map">NTF II</label></div>
	   </div>
	   
	   <div class="border_legende">
	   <p><u>Coordonnées géographiques</u></p>
       <div><input type="checkbox" name="etrs89_geog_map" id="etrs89_geog_map"/> <label for="etrs89_geog_map">ETRS89/RGF/CHTRS95</label></div>
	   <div><input type="checkbox" name="ch1903_geog_map" id="ch1903_geog_map" /> <label for="ch1903_geog_map">CH1903+</label></div>
	   <!--<div><input type="checkbox" name="rgf_geog_map" id="rgf_geog_map" /> <label for="rgf_geog_map">RGF</label></div>-->
	   <div><input type="checkbox" name="ntf_geog_map" id="ntf_geog_map" /> <label for="ntf_geog_map">NTF</label></div>
	   </div>
	   
	   <div class="border_legende">
	   <p><u>Coordonnées cartésiennes</u></p>
       <div><input type="checkbox" name="etrs89_cart_map" id="etrs89_cart_map"/> <label for="etrs89_cart_map">ETRS89/RGF/CHTRS95</label></div>
	   <div><input type="checkbox" name="ch1903_cart_map" id="ch1903_cart_map" /> <label for="ch1903_cart_map">CH1903+</label></div>
	   <!--<div><input type="checkbox" name="rgf_cart_map" id="rgf_cart_map" /> <label for="rgf_cart_map">RGF</label></div>-->
	   <div><input type="checkbox" name="ntf_cart_map" id="ntf_cart_map" /> <label for="ntf_cart_map">NTF</label></div>
	   </div>
	   </div>
	   
		<div id="title_theme">
		<div id="sys_alti_title"  style="display:flex;">
		<i id="symbol_list_alti"></i>
		<h3>Systèmes altimétriques</h3>
		<span id="span_alti"><p>Les systèmes altimétriques pouvant être affichés dans la popup en cliquant sur un point</p></span>
		</div>
		</div>
	   <div id="sys_alti_leg">
	   <div class="border_legende">
	   <p><u>Altitudes</u></p>
       <div><input type="checkbox" name="ign69_map" id="ign69_map"/> <label for="ign69_map">IGN69</label></div>
       <div><input type="checkbox" name="ran95_map" id="ran95_map"/> <label for="ran95_map">RAN95</label></div>
       <div><input type="checkbox" name="nf02_map" id="nf02_map"/> <label for="nf02_map">NF02</label></div>
	   </div>
	   <div class="border_legende">
	   <p><u>Hauteur</u></p>
	   <div><input type="checkbox" name="hbessel_map" id="hbessel_map"/> <label for="hbessel_map">Ellisoïde: Bessel</label></div>
	   <div><input type="checkbox" name="hgrs80_map" id="hgrs80_map"/> <label for="hgrs80_map">Ellisoïde: IAG GRS 1980</label></div>
		</div>
		</div>	
	   
	   
       
   
	
	</form>
	
            <p>projection :
                <select id="maListe" onchange="changeProjection(this.value)">
                    <option value="EPSG:4326">WGS84</option>
					<optgroup label="Coordonnées Françaises projetées">
					<option value="EPSG:2154">RGF93</option>
					<option value="EPSG:27572">NTF</option>
					</optgroup>
					<optgroup label="Coordonnées Suisse projetées">
                    <option value="EPSG:21781">CH1903</option>
                    <option value="EPSG:2056">CH1903+</option>
					</optgroup>
                </select>
            </p>
            <p id="coordonnees"></p>
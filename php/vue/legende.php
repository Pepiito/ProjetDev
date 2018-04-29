<!-- HEIG-vd -->
<input class="recherche" type="search" placeholder="Recherche par points"/>
	<form>
	<div id="point_fixe_map">
	<h3>
       Liste des points fixes:</h3>
	   <p><u>Français</u></p>
       
	   <p><u>Suisse</u></p>
       <div style="height:25px;"><input type="checkbox" name="PFP1" id="PFP1_leg"/> <label for="PFP1">PFP1</label><img src="icon_map/PFP1.svg">  </div>
	   <div style="height:25px;"><input type="checkbox" name="PFP2" id="PFA1_leg"/> <label for="PFA1">PFA1</label><img src="icon_map/PFA1.svg"></div>
	   
	</div> 
	<h3>
       Systèmes de coordonnées</h3>
	   <p><u>Coordonnées projetées</u></p>
	   <div><input type="checkbox" name="ch1903_proj_map" id="ch1903_proj_map"/> <label for="ch1903_proj_map">CH1903</label></div>
	   <div><input type="checkbox" name="ch1903plus_proj_map" id="ch1903plus_proj_map" /> <label for="ch1903plus_proj_map">CH1903+</label></div>
	   <div><input type="checkbox" name="rgf_proj_map" id="rgf_proj_map" /> <label for="rgf_proj_map">RGF Lambert-93</label></div>
	   <div><input type="checkbox" name="rgf_proj_C46_map" id="rgf_proj_C46_map" /> <label for="rgf_proj_C46_map">RGF C46</label></div>
	   <div><input type="checkbox" name="rgf_proj_C47_map" id="rgf_proj_C47_map" /> <label for="rgf_proj_C47_map">RGF C47</label></div>
	   <div><input type="checkbox" name="rgf_proj_C48_map" id="rgf_proj_C48_map" /> <label for="rgf_proj_C48_map">RGF C48</label></div>
	   <div><input type="checkbox" name="ntf_proj_Etendu_map" id="ntf_proj_Etendu_map" /> <label for="ntf_proj_Etendu_map">NTF II Etendu</label></div>
	   <div><input type="checkbox" name="ntf_proj_2_map" id="ntf_proj_2_map" /> <label for="ntf_proj_2_map">NTF II</label></div>
	   
	   
	   <p><u>Coordonnées géographiques</u></p>
       <div><input type="checkbox" name="etrs89_geog_map" id="etrs89_geog_map"/> <label for="etrs89_geog_map">ETRS89/RGF/CHTRS95/~WGS84</label></div>
	   <div><input type="checkbox" name="ch1903_geog_map" id="ch1903_geog_map" /> <label for="ch1903_geog_map">CH1903+</label></div>
	   <!--<div><input type="checkbox" name="rgf_geog_map" id="rgf_geog_map" /> <label for="rgf_geog_map">RGF</label></div>-->
	   <div><input type="checkbox" name="ntf_geog_map" id="ntf_geog_map" /> <label for="ntf_geog_map">NTF</label></div>
	   <p><u>Coordonnées cartésiennes</u></p>
       <div><input type="checkbox" name="etrs89_cart_map" id="etrs89_cart_map"/> <label for="etrs89_cart_map">ETRS89/RGF/CHTRS95</label></div>
	   <div><input type="checkbox" name="ch1903_cart_map" id="ch1903_cart_map" /> <label for="ch1903_cart_map">CH1903+</label></div>
	   <!--<div><input type="checkbox" name="rgf_cart_map" id="rgf_cart_map" /> <label for="rgf_cart_map">RGF</label></div>-->
	   <div><input type="checkbox" name="ntf_cart_map" id="ntf_cart_map" /> <label for="ntf_cart_map">NTF</label></div>
	   
	   
	   
	   <h3>Systèmes altimétriques</h3>
	   <p><u>Altitudes</u></p>
       <div><input type="checkbox" name="ign69_map" id="ign69_map"/> <label for="ign69_map">IGN69</label></div>
       <div><input type="checkbox" name="ran95_map" id="ran95_map"/> <label for="ran95_map">RAN95</label></div>
       <div><input type="checkbox" name="nf02_map" id="nf02_map"/> <label for="nf02_map">NF02</label></div>
	   <p><u>Hauteur</u></p>
	   <div><input type="checkbox" name="hbessel_map" id="hbessel_map"/> <label for="hbessel_map">Ellisoïde: Bessel</label></div>
	   <div><input type="checkbox" name="hgrs80_map" id="hgrs80_map"/> <label for="hgrs80_map">Ellisoïde: IAG GRS 1980</label></div>

	   
	   
       
   
	
	</form>
	
            <p>projection :
                <select id="maListe" onchange="changeprojection(this.value)">
                    <option value="EPSG:4326">WGS84</option>
					<optgroup label="Français">
					<option value="EPSG:2154">RGF93</option>
					<option value="EPSG:4275">NTF</option>
					</optgroup>
					<optgroup label="Suisse">
                    <option value="EPSG:21781">CH1903</option>
                    <option value="EPSG:2056">CH1903+</option>
					</optgroup>
                </select>
            </p>
            <p id="coordonnees"></p>
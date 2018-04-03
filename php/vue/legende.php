<input class="recherche" type="search" placeholder="Recherche par points"/>
	<form>
	<div id="point_fixe_map">
	<h3>
       Liste des points fixes:</h3>
	   <p><u>Français</u></p>
       
	   <p><u>Suisse</u></p>
       <div><input type="checkbox" name="PFP1" id="PFP1_leg"/> <label for="PFP1">PFP1</label></div>
	   <div><input type="checkbox" name="PFP2" id="PFA1_leg"/> <label for="PFA1">PFA1</label></div>
	   
	</div> 
	<h3>
       Liste des systèmes de coordonnées</h3>
	   <p><u>Coordonnées géographiques</u></p>
       <div><input type="checkbox" name="etrs89_geog_map" id="etrs89_geog_map"/> <label for="etrs89_geog_map">ETRS89 EPSG:4258</label></div>
	   <div><input type="checkbox" name="ch1903_geog_map" id="ch1903_geog_map" /> <label for="ch1903_geog_map">CH1903+</label></div>
	   <div><input type="checkbox" name="rgf_geog_map" id="rgf_geog_map" /> <label for="rgf_geog_map">RGF</label></div>
	   <div><input type="checkbox" name="ntf_geog_map" id="ntf_geog_map" /> <label for="ntf_geog_map">NTF</label></div>
	   <p><u>Coordonnées projetées</u></p>
	   <div><input type="checkbox" name="ch1903_proj_map" id="ch1903_proj_map"/> <label for="ch1903_proj_map">CH1903 </label></div>
	   <div><input type="checkbox" name="ch1903plus_proj_map" id="ch1903plus_proj_map" /> <label for="ch1903plus_proj_map">CH1903+</label></div>
	   <div><input type="checkbox" name="rgf_proj_map" id="rgf_proj_map" /> <label for="rgf_proj_map">RGF</label></div>
	   <div><input type="checkbox" name="ntf_proj_map" id="ntf_proj_map" /> <label for="ntf_proj_map">NTF</label></div>
	   
	   
	   <h3>Liste des systèmes altimétriques</h3>
	   <p><u>Altitudes</u></p>
       <div><input type="checkbox" name="" id="" checked/> <label for="">IGN69</label></div>
       <div><input type="checkbox" name="" id="" checked/> <label for="">RAN95</label></div>
       <div><input type="checkbox" name="" id="" checked/> <label for="">NF02</label></div>
	   <p><u>Hauteur</u></p>
	   <div><input type="checkbox" name="Bessel" id="Bessel" checked/> <label for="Bessel">Ellisoïde: Bessel</label></div>
	   <div><input type="checkbox" name="" id="" checked/> <label for="">Ellisoïde: IAG GRS 1980</label></div>

	   
	   
       
   
	
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
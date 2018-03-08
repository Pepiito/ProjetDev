<input class="recherche" type="text" placeholder="Recherche par points"/>
	<form>
	<h3>
       Liste des points fixes:</h3>
	   <p><u>Français</u></p>
       <input type="checkbox" name="pointfixe1" id="pointfixe1" checked/> <label for="pointfixe1">pointfixe1</label>
	   <p><u>Suisse</u></p>
       <div><input type="checkbox" name="PFP1" id="PFP1" checked/> <label for="PFP1">PFP1</label></div>
	   <div><input type="checkbox" name="PFP2" id="PFP2" checked/> <label for="PFP2">PFP1</label></div>
	   <div><input type="checkbox" name="PFP3" id="PFP3" checked/> <label for="PFP3">PFP1</label></div>
	   
	<h3>
       Liste des systèmes de coordonnées</h3>
	   <p><u>Coordonnées géographiques</u></p>
       <div><input type="checkbox" name="EPSG:4326" id="EPSG:4326" checked/> <label for="EPSG:4326">WGS 84 EPSG:4326</label></div>
       <div><input type="checkbox" name="EPSG:4258" id="EPSG:4258" checked/> <label for="EPSG:4258">ETRS89 EPSG:4258</label></div>
	   <p><u>Coordonnées projetées</u></p>
	   <p><u>Français</u></p>
       <div><input type="checkbox" name="EPSG:2154" id="EPSG:2154" checked/> <label for="EPSG:2154">RGF93 EPSG:2154</label></div>
       <div><input type="checkbox" name="EPSG:4275" id="EPSG:4275" checked/> <label for="EPSG:4275">NTF EPSG:4275</label></div>
	   <p><u>Suisse</u></p>
       <div><input type="checkbox" name="EPSG:2056" id="EPSG:2056" checked/> <label for="EPSG:2056">CH1903+ EPSG:2056</label></div>
       <div><input type="checkbox" name="EPSG:21781" id="EPSG:21781" checked/> <label for="EPSG:21781">CH1903 EPSG:21781</label></div>
	   
	   
       
   
	
	</form>
	
            <p>Projection :
                <select id="maListe" onchange="changeProjection(this.value)">
                    <option value="EPSG:4326">WGS84</option>
					<option value="EPSG:2154">RGF93</option>
                    <option value="EPSG:21781">CH1903</option>
                    <option value="EPSG:2056">CH1903+</option>
                </select>
            </p>
            <p id="coordonnees"></p>
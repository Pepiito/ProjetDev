<?php
//HEIG-VD
if (!isset($_SESSION)) session_start();
	
	//connection to postgis
	include("connexion_postgis.php");
	
		$res = pg_query($conn, "SELECT id_sess FROM session WHERE pseudo='".$_SESSION['pseudo']."';");
		$id_sess = pg_fetch_result($res,0,0);
		$pt_session = pg_query($conn, 
		"SELECT jsonb_build_object(
			'type',       'Feature',
			'id',         id_ptsess,
			'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
			'properties', to_jsonb(row) - 'geom'
		) FROM (SELECT * FROM \"Points_session\" WHERE id_sess=".$id_sess." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\" WHERE id_sess=".$id_sess." AND date_chantier=".$valuedelalistedanslegendephpligne32.")) row;");
	function postgis_to_geojson($select_postgis){
		$geojson='';
		$nb=0;
		while ($row = pg_fetch_row($select_postgis)){
			$geojson.=$row[0].',';
			$nb+=1;
	}
		$geojson = '{"type":"FeatureCollection","totalFeatures":'.$nb.',"features":['.substr($geojson, 0, -1).']}';
		return $geojson;
	}
	$geojson_ptsess = postgis_to_geojson($pt_session);
?>
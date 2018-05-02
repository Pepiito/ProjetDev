<?php
	//HEIG-VD
	//connection to postgis
	include("connexion_postgis.php");
	
	$id_utilisateur=1;
	$pt_session = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM \"Points_session\" WHERE id_ptsess=".$id_utilisateur." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\")) row;");
	
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
	
	

	
	

	$pfa1 = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_pf,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM \"Points_fixes\" WHERE type='PFA1') row;");
	$geojson_pfa1 = postgis_to_geojson($pfa1);
	
	$pfp1 = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_pf,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM \"Points_fixes\" WHERE type='PFP1') row;");
	$geojson_pfp1 = postgis_to_geojson($pfp1);
	

?>
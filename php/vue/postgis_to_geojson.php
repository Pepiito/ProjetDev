<?php
	$conn = pg_connect("host=localhost port=5432 dbname=ProjetDEV user=postgres password=postgres");
	$pt_session = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM \"Points_session\") row;");
	$geojson_ptsess = pg_fetch_array($pt_session);
	echo $geojson_ptsess[1];
	$nb_ptsess=count($geojson_ptsess);
	echo $nb_ptsess;
	$n=0;
	for($i = 0; $i < count($geojson_ptsess); ++$i){
		echo $geojson_ptsess[$i];
	}
		

	echo $geojson_conc;
	

	$pfa1 = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_pf,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM \"Points_fixes\" WHERE type='PFA1') row;");
	$geojson_pfa1 = pg_fetch_array($pt_session);

?>
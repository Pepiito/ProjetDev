<?php
include("connexion_postgis.php");
$res = pg_query($conn, 'SELECT max(id_ptsess) FROM "Points_session";');
$id_ptsess = pg_fetch_result($res,0,0)+1;

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
	
	
$nb_pt = count($echo['n']); //Variable des numéros des points --> contrôler si variable juste

for ($i = 0; $i<$nb_pt;$i++){
	$num=$echo['n']['n'.$i];
	$ETRS89_X = $echo['cart']['ETRS89']['X']['X'.$i];
	$ETRS89_Y = $echo['cart']['ETRS89']['Y']['Y'.$i];
	$ETRS89_Z = $echo['cart']['ETRS89']['Z']['Z'.$i];
	$ETRS89_l = $echo['geog']['ETRS89']['h']['lambda']['lambda'.$i]*180/pi();
	$ETRS89_p = $echo['geog']['ETRS89']['h']['phi']['phi'.$i]*180/pi();
	$ETRS89_h = $echo['geog']['ETRS89']['h']['h']['h'.$i];
	$CH1903plus_X = $echo['cart']['CH1903plus']['X']['X'.$i];
	$CH1903plus_Y = $echo['cart']['CH1903plus']['Y']['Y'.$i];
	$CH1903plus_Z = $echo['cart']['CH1903plus']['Z']['Z'.$i];
	$CH1903plus_l = $echo['geog']['CH1903plus']['h']['lambda']['lambda'.$i]*180/pi();
	$CH1903plus_p = $echo['geog']['CH1903plus']['h']['phi']['phi'.$i]*180/pi();
	$CH1903plus_h = $echo['geog']['CH1903plus']['h']['h']['h'.$i];
	$CH1903plus_E = $echo['proj']['CH1903plus']['MN95']['a']['RAN95']['E']['E'.$i];
	$CH1903plus_N = $echo['proj']['CH1903plus']['MN95']['a']['RAN95']['N']['N'.$i];
	$CH1903_E= $echo['proj']['CH1903']['MN03']['a']['NF02']['E']['E'.$i];
	$CH1903_N =$echo['proj']['CH1903']['MN03']['a']['NF02']['N']['N'.$i];
	$RAN95 = $echo['proj']['CH1903plus']['MN95']['a']['RAN95']['H']['H'.$i];
	$NF02 = $echo['proj']['CH1903plus']['MN95']['a']['NF02']['H']['H'.$i];
	$RGF93_E = $echo['proj']['RGF93']['Lambert93']['a']['IGN69']['E']['E'.$i];
	$RGF93_N = $echo['proj']['RGF93']['Lambert93']['a']['IGN69']['N']['N'.$i];
	$NTF_X = $echo['cart']['NTF']['X']['X'.$i];
	$NTF_Y = $echo['cart']['NTF']['Y']['Y'.$i];
	$NTF_Z = $echo['cart']['NTF']['Z']['Z'.$i];
	$NTF_l = $echo['geog']['NTF']['h']['lambda']['lambda'.$i]*180/pi();
	$NTF_p = $echo['geog']['NTF']['h']['phi']['phi'.$i]*180/pi();
	$NTF_h = $echo['geog']['NTF']['h']['h']['h'.$i];
	$NTF_Lambert2etendu_E = $echo['proj']['NTF']['Lambert2etendu']['a']['IGN69']['E']['E'.$i];
	$NTF_Lambert2etendu_N = $echo['proj']['NTF']['Lambert2etendu']['a']['IGN69']['N']['N'.$i];
	$IGN69 = $echo['proj']['RGF93']['Lambert93']['a']['IGN69']['H']['H'.$i];
	$NTF_Lambert2_E = $echo['proj']['NTF']['Lambert2']['a']['IGN69']['E']['E'.$i];
	$NTF_Lambert2_N = $echo['proj']['NTF']['Lambert2']['a']['IGN69']['N']['N'.$i];
	$RGF_CC46_E = $echo['proj']['RGF93']['CC46']['a']['IGN69']['E']['E'.$i];
	$RGF_CC46_N = $echo['proj']['RGF93']['CC46']['a']['IGN69']['N']['N'.$i];
	$RGF_CC47_E = $echo['proj']['RGF93']['CC47']['a']['IGN69']['E']['E'.$i];
	$RGF_CC47_N = $echo['proj']['RGF93']['CC47']['a']['IGN69']['N']['N'.$i];
	$RGF_CC48_E = $echo['proj']['RGF93']['CC48']['a']['IGN69']['E']['E'.$i];
	$RGF_CC48_N = $echo['proj']['RGF93']['CC48']['a']['IGN69']['N']['N'.$i];
	
	
	if (isset ($_SESSION['id_sess'])){
		$id_sess = $_SESSION['id_sess'];
		pg_query($conn, "INSERT INTO \"Points_session\"(
		id_sess, num_pt, \"X_ETRS89\", \"Y_ETRS89\", \"Z_ETRS89\", \"long_ETRS89\", \"lat_ETRS89\", \"h_ETRS89\", \"X_CH1903plus\", \"Y_CH1903plus\", \"Z_CH1903plus\", \"long_CH1903plus\", \"lat_CH1903plus\", \"h_CH1903plus\", \"E_CH1903plus\", \"N_CH1903plus\", \"E_CH1903\", \"N_CH1903\", \"alt_RAN95\", \"alt_NF02\", \"E_RGF\", \"N_RGF\", \"X_NTF\", \"Y_NTF\", \"Z_NTF\", \"long_NTF\", \"lat_NTF\", \"h_NTF\", \"E_NTF\", \"N_NTF\", \"alt_IGN69\", geom, \"E_NTF_2\", \"N_NTF_2\", \"E_RGF_C46\", \"N_RGF_C46\", \"E_RGF_C47\", \"N_RGF_C47\", \"E_RGF_C48\", \"N_RGF_C48\", date_chantier)
		VALUES (
		'$id_sess',
		'$num',
		'$ETRS89_X',
		'$ETRS89_Y',
		'$ETRS89_Z',
		'$ETRS89_l',
		'$ETRS89_p',
		'$ETRS89_h',
		'$CH1903plus_X',
		'$CH1903plus_Y',
		'$CH1903plus_Z',
		'$CH1903plus_l',
		'$CH1903plus_p',
		'$CH1903plus_h',
		'$CH1903plus_E',
		'$CH1903plus_N',
		'$CH1903_E',
		'$CH1903_N',
		'$RAN95',
		'$NF02',
		'$RGF93_E',
		'$RGF93_N',
		'$NTF_X',
		'$NTF_Y',
		'$NTF_Z',
		'$NTF_l',
		'$NTF_p',
		'$NTF_h',
		'$NTF_Lambert2etendu_E',
		'$NTF_Lambert2etendu_N',
		'$IGN69',
		ST_SetSRID(ST_Point($ETRS89_l,$ETRS89_p),4258),
		'$NTF_Lambert2_E',
		'$NTF_Lambert2_N',
		'$RGF_CC46_E',
		'$RGF_CC46_N',
		'$RGF_CC47_E',
		'$RGF_CC47_N',
		'$RGF_CC48_E',
		'$RGF_CC48_N',
		CURRENT_DATE
		);");
		$pt_session = pg_query($conn, 
		"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
		) FROM (SELECT * FROM \"Points_session\" WHERE id_sess=".$_SESSION['id_sess']." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\" WHERE id_sess=".$_SESSION['id_sess']." )) row;");
		$geojson_ptsess = postgis_to_geojson($pt_session);
	}else{
		$id_aleat = $_SESSION['id_aleat'];
		pg_query($conn, "INSERT INTO \"Points_session\"(
		id_aleat, aleatoire, num_pt, \"X_ETRS89\", \"Y_ETRS89\", \"Z_ETRS89\", \"long_ETRS89\", \"lat_ETRS89\", \"h_ETRS89\", \"X_CH1903plus\", \"Y_CH1903plus\", \"Z_CH1903plus\", \"long_CH1903plus\", \"lat_CH1903plus\", \"h_CH1903plus\", \"E_CH1903plus\", \"N_CH1903plus\", \"E_CH1903\", \"N_CH1903\", \"alt_RAN95\", \"alt_NF02\", \"E_RGF\", \"N_RGF\", \"X_NTF\", \"Y_NTF\", \"Z_NTF\", \"long_NTF\", \"lat_NTF\", \"h_NTF\", \"E_NTF\", \"N_NTF\", \"alt_IGN69\", geom, \"E_NTF_2\", \"N_NTF_2\", \"E_RGF_C46\", \"N_RGF_C46\", \"E_RGF_C47\", \"N_RGF_C47\", \"E_RGF_C48\", \"N_RGF_C48\", date_chantier)
		VALUES (
		'$id_aleat',
		'YES',
		'$num',
		'$ETRS89_X',
		'$ETRS89_Y',
		'$ETRS89_Z',
		'$ETRS89_l',
		'$ETRS89_p',
		'$ETRS89_h',
		'$CH1903plus_X',
		'$CH1903plus_Y',
		'$CH1903plus_Z',
		'$CH1903plus_l',
		'$CH1903plus_p',
		'$CH1903plus_h',
		'$CH1903plus_E',
		'$CH1903plus_N',
		'$CH1903_E',
		'$CH1903_N',
		'$RAN95',
		'$NF02',
		'$RGF93_E',
		'$RGF93_N',
		'$NTF_X',
		'$NTF_Y',
		'$NTF_Z',
		'$NTF_l',
		'$NTF_p',
		'$NTF_h',
		'$NTF_Lambert2etendu_E',
		'$NTF_Lambert2etendu_N',
		'$IGN69',
		ST_SetSRID(ST_Point($ETRS89_l,$ETRS89_p),4258),
		'$NTF_Lambert2_E',
		'$NTF_Lambert2_N',
		'$RGF_CC46_E',
		'$RGF_CC46_N',
		'$RGF_CC47_E',
		'$RGF_CC47_N',
		'$RGF_CC48_E',
		'$RGF_CC48_N',
		CURRENT_DATE
		);");
		$pt_session = pg_query($conn, 
		"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
		) FROM (SELECT * FROM \"Points_session\" WHERE id_aleat=".$_SESSION['id_aleat']." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\" WHERE id_aleat=".$_SESSION['id_aleat']." )) row;");
		$geojson_ptsess = postgis_to_geojson($pt_session);
		
	}
}
$echo['geojson_ptsess'] = $geojson_ptsess;



?>
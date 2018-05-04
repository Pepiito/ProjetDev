<?php
include("connexion_postgis.php");

$nb_pt = count($num_pt]); //Variable des numéros des points --> contrôler si variable juste

for ($i = 0; $i<$nb_pt;$i++){
	if (isset ($_SESSION['id_sess'])){
		pg_query($conn, 'INSERT INTO "Points_session"(
		id_sess, num_pt, "X_ETRS89", "Y_ETRS89", "Z_ETRS89", "long_ETRS89", "lat_ETRS89", "h_ETRS89", "X_CH1903plus", "Y_CH1903plus", "Z_CH1903plus", "long_CH1903plus", "lat_CH1903plus", "h_CH1903plus", "E_CH1903plus", "N_CH1903plus", "E_CH1903", "N_CH1903", "alt_RAN95", "alt_NF02", "E_RGF", "N_RGF", "X_NTF", "Y_NTF", "Z_NTF", "long_NTF", "lat_NTF", "h_NTF", "E_NTF", "N_NTF", "alt_IGN69", geom, "E_NTF_2", "N_NTF_2", "E_RGF_C46", "N_RGF_C46", "E_RGF_C47", "N_RGF_C47", "E_RGF_C48", "N_RGF_C48", date_chantier)
		VALUES ( '.$_SESSION['id_sess'].',
		'.$coordonnees['cart']['ETRS89']['X'.$i].',
		'.$coordonnees['cart']['ETRS89']['Y'.$i].',
		'.$coordonnees['cart']['ETRS89']['Z'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['l'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['p'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['h'.$i].',
		'.$coordonnees['cart']['CH1903+']['X'.$i].',
		'.$coordonnees['cart']['CH1903+']['Y'.$i].',
		'.$coordonnees['cart']['CH1903+']['Z'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['l'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['p'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['h'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['E'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['N'.$i].',
		'.$coordonnees['proj']['CH1903']['MN03']['a']['NF02']['E'.$i].',
		'.$coordonnees['proj']['CH1903']['MN03']['a']['NF02']['N'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['H'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['NF02']['H'.$i].',
		'.$coordonnees['proj']['RGF93']['Lambert93']['a']['IGN69']['E'.$i].',
		'.$coordonnees['proj']['RGF93']['Lambert93']['a']['IGN69']['N'.$i].',
		'.$coordonnees['cart']['NTF']['X'.$i].',
		'.$coordonnees['cart']['NTF']['Y'.$i].',
		'.$coordonnees['cart']['NTF']['Z'.$i].',
		'.$coordonnees['geog']['NTF']['h']['l'.$i].',
		'.$coordonnees['geog']['NTF']['h']['p'.$i].',
		'.$coordonnees['geog']['NTF']['h']['h'.$i].',
		'.$coordonnees['proj']['NTF']['Lambert2etendu']['a']['IGN69']['E'.$i].',
		'.$coordonnees['proj']['NTF']['Lambert2etendu']['a']['IGN69']['N'.$i].',
		'.$coordonnees['proj']['RGF93']['Lambert93']['a']['IGN69']['H'.$i].',
		'ST_SetSRID(ST_Point(.$coordonnees['geog']['ETRS89']['h']['l'.$i].','.$coordonnees['geog']['ETRS89']['h']['p'.$i]. '),4258),
		'.$coordonnees['proj']['NTF']['Lambert2']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['NTF']['Lambert2']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['CC46']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['CC46']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['CC47']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['CC47']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['CC48']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['CC48']['a']['IGN69']['N'$i].',
		CURRENT_DATE
		);');
		
		$pt_session = pg_query($conn, 
		"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
		) FROM (SELECT * FROM \"Points_session\" WHERE id_ptsess=".$_SESSION['id_sess']." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\")) row;");
		$geojson_ptsess = postgis_to_geojson($pt_session);
	}else{
		pg_query($conn, 'INSERT INTO "Points_session"(
		id_aleat, aleatoire, num_pt, "X_ETRS89", "Y_ETRS89", "Z_ETRS89", "long_ETRS89", "lat_ETRS89", "h_ETRS89", "X_CH1903plus", "Y_CH1903plus", "Z_CH1903plus", "long_CH1903plus", "lat_CH1903plus", "h_CH1903plus", "E_CH1903plus", "N_CH1903plus", "E_CH1903", "N_CH1903", "alt_RAN95", "alt_NF02", "E_RGF", "N_RGF", "X_NTF", "Y_NTF", "Z_NTF", "long_NTF", "lat_NTF", "h_NTF", "E_NTF", "N_NTF", "alt_IGN69", geom, "E_NTF_2", "N_NTF_2", "E_RGF_C46", "N_RGF_C46", "E_RGF_C47", "N_RGF_C47", "E_RGF_C48", "N_RGF_C48", date_chantier)
		VALUES ( '.$_SESSION['id_aleat'].',
		"YES",
		'.$coordonnees['cart']['ETRS89']['X'.$i].',
		'.$coordonnees['cart']['ETRS89']['Y'.$i].',
		'.$coordonnees['cart']['ETRS89']['Z'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['l'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['p'.$i].',
		'.$coordonnees['geog']['ETRS89']['h']['h'.$i].',
		'.$coordonnees['cart']['CH1903+']['X'.$i].',
		'.$coordonnees['cart']['CH1903+']['Y'.$i].',
		'.$coordonnees['cart']['CH1903+']['Z'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['l'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['p'.$i].',
		'.$coordonnees['geog']['CH1903+']['h']['h'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['E'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['N'.$i].',
		'.$coordonnees['proj']['CH1903']['MN03']['a']['NF02']['E'.$i].',
		'.$coordonnees['proj']['CH1903']['MN03']['a']['NF02']['N'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['RAN95']['H'.$i].',
		'.$coordonnees['proj']['CH1903+']['MN95']['a']['NF02']['H'.$i].',
		'.$coordonnees['proj']['RGF93']['Lambert93']['a']['IGN69']['E'.$i].',
		'.$coordonnees['proj']['RGF93']['Lambert93']['a']['IGN69']['N'.$i].',
		'.$coordonnees['cart']['NTF']['X'.$i].',
		'.$coordonnees['cart']['NTF']['Y'.$i].',
		'.$coordonnees['cart']['NTF']['Z'.$i].',
		'.$coordonnees['geog']['NTF']['h']['l'.$i].',
		'.$coordonnees['geog']['NTF']['h']['p'.$i].',
		'.$coordonnees['geog']['NTF']['h']['h'.$i].',
		'.$coordonnees['proj']['NTF']['Lambert2etendu']['a']['IGN69']['E'.$i].',
		'.$coordonnees['proj']['NTF']['Lambert2etendu']['a']['IGN69']['N'.$i].',
		'.$coordonnees['proj']['RGF93']['lambert93']['a']['IGN69']['H'.$i].',
		'ST_SetSRID(ST_Point(.$coordonnees['geog']['ETRS89']['h']['l'.$i].','.$coordonnees['geog']['ETRS89']['h']['p'.$i]. '),4258),
		'.$coordonnees['proj']['NTF']['Lambert2']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['NTF']['Lambert2']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['C46']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['C46']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['C47']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['C47']['a']['IGN69']['N'$i].',
		'.$coordonnees['proj']['RGF']['C48']['a']['IGN69']['E'$i].',
		'.$coordonnees['proj']['RGF']['C48']['a']['IGN69']['N'$i].',
		CURRENT_DATE
		);');
		$pt_session = pg_query($conn, 
		"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(ST_Transform(geom,3857))::jsonb,
		'properties', to_jsonb(row) - 'geom'
		) FROM (SELECT * FROM \"Points_session\" WHERE id_aleat=".$_SESSION['id_aleat']." AND date_chantier=(SELECT max(date_chantier) FROM \"Points_session\")) row;");
		$geojson_ptsess = postgis_to_geojson($pt_session);

		
	}
}
$echo['geojson_ptsess'] = $geojson_ptsess



?>
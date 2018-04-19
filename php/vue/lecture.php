<?php
	
	$pt_session = pg_query($conn, 
	"SELECT jsonb_build_object(
		'type',       'Feature',
		'id',         id_ptsess,
		'geometry',   ST_AsGeoJSON(geom)::jsonb,
		'properties', to_jsonb(row) - 'geom'
	) FROM (SELECT * FROM 'Points_session' WHERE id_ptsess=1) row;)")
	echo "<script>console.log(".  $pt_session . ");</script>";
?>
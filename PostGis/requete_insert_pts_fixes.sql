INSERT INTO "Points_fixes"(
	num_pt, "type", "X_ETRS89", "Y_ETRS89", "Z_ETRS89", "long_ETRS89", "lat_ETRS89", "h_ETRS89", "X_CH1903plus", "Y_CH1903plus", "Z_CH1903plus", "long_CH1903plus", "lat_CH1903plus", "h_CH1903plus", "E_CH1903plus", "N_CH1903plus", "E_CH1903", "N_CH1903", "alt_RAN95", "alt_NF02", "E_RGF", "N_RGF", "X_NTF", "Y_NTF", "Z_NTF", "long_NTF", "lat_NTF", "h_NTF", "E_NTF", "N_NTF", "alt_IGN69", geom, "E_NTF_2", "N_NTF_2", "E_RGF_C46", "N_RGF_C46", "E_RGF_C47", "N_RGF_C47", "E_RGF_C48", "N_RGF_C48")
	SELECT num_pt, 'type-de-point', "X_ETRS89", "Y_ETRS89", "Z_ETRS89", "long_ETRS89", "lat_ETRS89", "h_ETRS89", "X_CH1903plus", "Y_CH1903plus", "Z_CH1903plus", "long_CH1903plus", "lat_CH1903plus", "h_CH1903plus", "E_CH1903plus", "N_CH1903plus", "E_CH1903", "N_CH1903", "alt_RAN95", "alt_NF02", "E_RGF", "N_RGF", "X_NTF", "Y_NTF", "Z_NTF", "long_NTF", "lat_NTF", "h_NTF", "E_NTF", "N_NTF", "alt_IGN69", geom, "E_NTF_2", "N_NTF_2", "E_RGF_C46", "N_RGF_C46", "E_RGF_C47", "N_RGF_C47", "E_RGF_C48", "N_RGF_C48" FROM "Points_session" WHERE id_sess=num_sess;
	
DELETE FROM public."Points_session"
	WHERE id_sess=num_sess;
	
	
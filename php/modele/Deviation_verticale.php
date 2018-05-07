<?php //HEIG
# Définition des calculs

# Déviation de la verticale

function deviation_verticale($mode, $Bessel_lambda, $Bessel_phi, $GRS80_lambda, $GRS80_phi, $eta, $ksi){
    $vecteur_suisse_zeta = 1/sqrt(1+tan($eta)**2+tan($ksi)**2);
    $vecteur_france_zeta = 1/sqrt(1+tan($eta)**2+tan($ksi)**2);

    # Matrice Bessel de rotation repère terrestre géodésique --> repère local géodésique

    $matrice_Bessel_tg_to_lg=array($Bessel_lambda, $Bessel_phi);
    $matrice_Bessel_tg_to_lg[0]=array(-sin($Bessel_lambda),cos($Bessel_lambda),0);
    $matrice_Bessel_tg_to_lg[1]=array((-sin($Bessel_phi))*cos($Bessel_lambda),(-sin($Bessel_phi))*sin($Bessel_lambda),cos($Bessel_phi));
    $matrice_Bessel_tg_to_lg[2]=array(cos($Bessel_phi)*cos($Bessel_lambda),cos($Bessel_phi)*sin($Bessel_lambda),sin($Bessel_phi));

    #echo $matrice_Bessel_tg_to_lg[0][1];

    # Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique

    $matrice_Bessel_tg_to_lg_transpose=array($matrice_Bessel_tg_to_lg);
    $matrice_Bessel_tg_to_lg_transpose[0]=array($matrice_Bessel_tg_to_lg[0][0],$matrice_Bessel_tg_to_lg[1][0],$matrice_Bessel_tg_to_lg[2][0]);
    $matrice_Bessel_tg_to_lg_transpose[1]=array($matrice_Bessel_tg_to_lg[0][1],$matrice_Bessel_tg_to_lg[1][1],$matrice_Bessel_tg_to_lg[2][1]);
    $matrice_Bessel_tg_to_lg_transpose[2]=array($matrice_Bessel_tg_to_lg[0][2],$matrice_Bessel_tg_to_lg[1][2],$matrice_Bessel_tg_to_lg[2][2]);

    #echo $matrice_Bessel_tg_to_lg_transpose[0][0];

    # Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique

    $matrice_GRS80_lg_to_cg=array($GRS80_lambda, $GRS80_phi);
    $matrice_GRS80_lg_to_cg[0]=array(-sin($GRS80_lambda),cos($GRS80_lambda),0);
    $matrice_GRS80_lg_to_cg[1]=array((-sin($GRS80_phi))*cos($GRS80_lambda),(-sin($GRS80_phi))*sin($GRS80_lambda),cos($GRS80_phi));
    $matrice_GRS80_lg_to_cg[2]=array(cos($GRS80_phi)*cos($GRS80_lambda),cos($GRS80_phi)*sin($GRS80_lambda),sin($GRS80_phi));

    #echo $matrice_GRS80_lg_to_cg[0][1];

    # Matrice du résultat du produit matriciel
    #	--> Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique
    #	--> Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique

    $matrice_produit_matriciel=array($matrice_GRS80_lg_to_cg, $matrice_Bessel_tg_to_lg_transpose);
    $matrice_produit_matriciel[0]=array($matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
                                        $matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][1],
                                        $matrice_GRS80_lg_to_cg[0][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[0][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[0][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);
    $matrice_produit_matriciel[1]=array($matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
                                        $matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][1],
                                        $matrice_GRS80_lg_to_cg[1][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[1][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[1][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);
    $matrice_produit_matriciel[2]=array($matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][0]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][0]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][0],
                                        $matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][1]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][1]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][1],
                                        $matrice_GRS80_lg_to_cg[2][0]*$matrice_Bessel_tg_to_lg_transpose[0][2]+$matrice_GRS80_lg_to_cg[2][1]*$matrice_Bessel_tg_to_lg_transpose[1][2]+$matrice_GRS80_lg_to_cg[2][2]*$matrice_Bessel_tg_to_lg_transpose[2][2]);

    #echo $matrice_produit_matriciel[0][0];

    if($mode=='fr'){
        $vecteur_suisse_eta = $eta;
        $vecteur_suisse_ksi = $ksi;
        # Matrice du résultat du produit matriciel qui donne le vecteur eta,ksi et zéta de la france en radians
        #	--> Résultat du produit matriciel des Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique et Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique
        #	--> Vecteur eta, ksi et zéta suisse

        $matrice_vecteur_france=array($matrice_produit_matriciel, $vecteur_suisse_eta, $vecteur_suisse_ksi, $vecteur_suisse_zeta);
        $eta=array($matrice_produit_matriciel[0][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[0][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[0][2]*$vecteur_suisse_zeta);
        $ksi=array($matrice_produit_matriciel[1][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[1][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[1][2]*$vecteur_suisse_zeta);
        $zeta=array($matrice_produit_matriciel[2][0]*$vecteur_suisse_eta+$matrice_produit_matriciel[2][1]*$vecteur_suisse_ksi+$matrice_produit_matriciel[2][2]*$vecteur_suisse_zeta);
        #echo $matrice_vecteur_france[0][0];
    }elseif ($mode=='ch'){
        $vecteur_france_eta = $eta;
        $vecteur_france_ksi = $ksi;
        # Matrice transpose du résultat du produit matriciel
        #	--> Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique
        #	--> Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique

        $matrice_produit_matriciel_transpose=array($matrice_produit_matriciel);
        $matrice_produit_matriciel_transpose[0]=array($matrice_produit_matriciel[0][0],$matrice_produit_matriciel[1][0],$matrice_produit_matriciel[2][0]);
        $matrice_produit_matriciel_transpose[1]=array($matrice_produit_matriciel[0][1],$matrice_produit_matriciel[1][1],$matrice_produit_matriciel[2][1]);
        $matrice_produit_matriciel_transpose[2]=array($matrice_produit_matriciel[0][2],$matrice_produit_matriciel[1][2],$matrice_produit_matriciel[2][2]);

        #echo $matrice_produit_matriciel_transpose[0][0];

        # Matrice du résultat du produit matriciel qui donne le vecteur eta,ksi et zéta de la suisse en radians
        #	--> Résultat du produit matriciel des Matrice GRS80 de rotation repère local géodésique --> repère carthésien géodésique et Matrice Bessel de rotation transposé repère terrestre géodésique --> repère local géodésique
        #	--> Vecteur eta, ksi et zéta francais

        $matrice_vecteur_suisse=array($matrice_produit_matriciel_transpose, $vecteur_france_eta, $vecteur_france_ksi, $vecteur_france_zeta);
        $eta=array($matrice_produit_matriciel_transpose[0][0]*$vecteur_france_eta+$matrice_produit_matriciel_transpose[0][1]*$vecteur_france_ksi+$matrice_produit_matriciel_transpose[0][2]*$vecteur_france_zeta);
        $ksi=array($matrice_produit_matriciel_transpose[1][0]*$vecteur_france_eta+$matrice_produit_matriciel_transpose[1][1]*$vecteur_france_ksi+$matrice_produit_matriciel_transpose[1][2]*$vecteur_france_zeta);
        $zeta=array($matrice_produit_matriciel_transpose[2][0]*$vecteur_france_eta+$matrice_produit_matriciel_transpose[2][1]*$vecteur_france_ksi+$matrice_produit_matriciel_transpose[2][2]*$vecteur_france_zeta);
        #echo $matrice_vecteur_suisse[0][0];
    }
    return array($eta[0], $ksi[0], $zeta[0]);
}
?>

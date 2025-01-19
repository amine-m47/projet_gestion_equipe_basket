<?php
function getEquipeStats($pdo) {
    // Requête SQL pour obtenir les statistiques globales de l'équipe
    $stmt = $pdo->query('
        SELECT 
    COUNT(DISTINCT `match`.id_match) AS total,  -- Comptabilise tous les matchs uniques
    CAST(SUM(CASE WHEN `match`.resultat = "2" THEN 1 ELSE 0 END) AS UNSIGNED) AS gagnes,  -- Nombre de matchs gagnés
    CAST(SUM(CASE WHEN `match`.resultat = "1" THEN 1 ELSE 0 END) AS UNSIGNED) AS nuls,    -- Nombre de matchs nuls
    CAST(SUM(CASE WHEN `match`.resultat = "0" THEN 1 ELSE 0 END) AS UNSIGNED) AS perdus   -- Nombre de matchs perdus
FROM `match`
WHERE `match`.resultat IS NOT NULL;  -- Filtrer les matchs où un résultat est présent

    ');

    // Vérification si des résultats ont été obtenus
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$stats) {
        // Si aucun résultat n'est trouvé, renvoyer un tableau vide
        $stats = [
            'total' => 0,
            'gagnes' => 0,
            'nuls' => 0,
            'perdus' => 0
        ];
    }

    // S'assurer que les valeurs ne sont pas nulles
    $stats['total'] = $stats['total'] ?? 0;
    $stats['gagnes'] = $stats['gagnes'] ?? 0;
    $stats['nuls'] = $stats['nuls'] ?? 0;
    $stats['perdus'] = $stats['perdus'] ?? 0;

    // Retourner les statistiques de l'équipe
    return $stats;
}



function getJoueurStats($pdo) {
    $stmt = $pdo->query("
        SELECT 
            CONCAT(joueur.nom, ' ', joueur.prenom) AS nom_complet,
            joueur.statut,
            CASE
                WHEN MAX(participer.poste) = 1 THEN 'Meneur'
                WHEN MAX(participer.poste) = 2 THEN 'Arrière'
                WHEN MAX(participer.poste) = 3 THEN 'Ailier'
                WHEN MAX(participer.poste) = 4 THEN 'Ailier fort'
                WHEN MAX(participer.poste) = 5 THEN 'Pivot'
                ELSE 'Inconnu'
            END AS poste_prefere,                                                                                            -- Poste préféré unique
            COUNT(DISTINCT CASE WHEN participer.titulaire = 1 THEN participer.id_match ELSE NULL END) AS titularisations,    -- Nombre de titularisations distinctes
            COUNT(DISTINCT CASE WHEN participer.titulaire = 0 THEN participer.id_match ELSE NULL END) AS remplacements,      -- Nombre de remplacements distincts
            COUNT(DISTINCT note_personnelle.id_note) AS evaluations_count,                                                   -- Évaluations distinctes
            ROUND(
                (SUM(DISTINCT CASE WHEN `match`.resultat = 2 THEN 1 ELSE 0 END) * 100) /
                NULLIF(COUNT(DISTINCT `match`.id_match), 0), 2
            ) AS pourcentage_victoires,                                                                                      -- % de victoires calculé sur des matchs distincts
            MAX(participer.titulaire) AS selections_consecutives                                                             -- Nombre maximum de titularisations consécutives
        FROM joueur
        INNER JOIN participer ON joueur.id_joueur = participer.id_joueur
        INNER JOIN `match` ON participer.id_match = `match`.id_match
        LEFT JOIN note_personnelle ON joueur.id_joueur = note_personnelle.id_joueur
        GROUP BY joueur.id_joueur, joueur.nom, joueur.statut                                                                 -- Grouper par joueur
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

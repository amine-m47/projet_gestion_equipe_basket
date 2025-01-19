<?php
function getEquipeStats($pdo) {
    // Requête SQL pour obtenir les statistiques globales de l'équipe
    $stmt = $pdo->query('
        SELECT 
            COUNT(DISTINCT `match`.id_match) AS total,
            CAST(SUM(CASE WHEN `match`.resultat = "2" THEN 1 ELSE 0 END) AS UNSIGNED) AS gagnes,
            CAST(SUM(CASE WHEN `match`.resultat = "1" THEN 1 ELSE 0 END) AS UNSIGNED) AS nuls,
            CAST(SUM(CASE WHEN `match`.resultat = "0" THEN 1 ELSE 0 END) AS UNSIGNED) AS perdus
        FROM `match`
        INNER JOIN participer ON `match`.id_match = participer.id_match
        WHERE `match`.resultat IS NOT NULL
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
    $stmt = $pdo->query('
        SELECT 
            joueur.nom,
            joueur.statut,
            MAX(participer.poste) AS poste_prefere, -- Poste préféré unique
            COUNT(DISTINCT CASE WHEN participer.titulaire = 1 THEN participer.id_match ELSE NULL END) AS titularisations, -- Nombre de titularisations distinctes
            COUNT(DISTINCT CASE WHEN participer.titulaire = 0 THEN participer.id_match ELSE NULL END) AS remplacements, -- Nombre de remplacements distincts
            COUNT(DISTINCT note_personnelle.id_note) AS evaluations_count, -- Évaluations distinctes
            ROUND(
                (SUM(DISTINCT CASE WHEN `match`.resultat = "2" THEN 1 ELSE 0 END) * 100) /
                NULLIF(COUNT(DISTINCT `match`.id_match), 0), 2
            ) AS pourcentage_victoires, -- % de victoires calculé sur des matchs distincts
            MAX(participer.titulaire) AS selections_consecutives -- Nombre maximum de titularisations consécutives
        FROM joueur
        INNER JOIN participer ON joueur.id_joueur = participer.id_joueur
        INNER JOIN `match` ON participer.id_match = `match`.id_match
        LEFT JOIN note_personnelle ON joueur.id_joueur = note_personnelle.id_joueur
        GROUP BY joueur.id_joueur, joueur.nom, joueur.statut -- Grouper par joueur
    ');

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

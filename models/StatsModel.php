<?php

class StatsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getStatsEquipe() {
        $query = "
            SELECT 
                COUNT(*) AS total_matchs,
                SUM(resultat = 1) AS matchs_gagnes,
                SUM(resultat = 0) AS matchs_nuls,
                SUM(resultat = 2) AS matchs_perdus,
                ROUND((SUM(resultat = 1) / COUNT(*)) * 100, 2) AS pourcentage_gagnes,
                ROUND((SUM(resultat = 0) / COUNT(*)) * 100, 2) AS pourcentage_nuls,
                ROUND((SUM(resultat = 2) / COUNT(*)) * 100, 2) AS pourcentage_perdus
            FROM `match`
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatsJoueurs() {
        $query = "
            SELECT 
                j.id_joueur,
                j.nom AS joueur_nom,
                IF(MAX(m.date) >= NOW() - INTERVAL 1 MONTH, 'Actif', 'Inactif') AS statut_actuel,
                (SELECT role_joueur 
                 FROM `participer` 
                 WHERE id_joueur = j.id_joueur 
                 GROUP BY role_joueur 
                 ORDER BY AVG(note_joueur) DESC 
                 LIMIT 1) AS poste_prefere,
                SUM(p.titulaire = 1) AS titularisations,
                SUM(p.titulaire = 0) AS remplacements,
                AVG(p.note_joueur) AS moyenne_notes,
                ROUND((SUM(m.resultat = 1 AND p.note_joueur IS NOT NULL) / COUNT(*)) * 100, 2) AS pourcentage_gagnes,
                (SELECT MAX(cons) FROM (
                    SELECT id_joueur, 
                           id_match, 
                           (id_match - ROW_NUMBER() OVER (PARTITION BY id_joueur ORDER BY id_match)) AS cons
                    FROM participer WHERE note_joueur IS NOT NULL
                ) t WHERE t.id_joueur = j.id_joueur GROUP BY t.cons) AS selections_consecutives
            FROM `joueur` j
            LEFT JOIN `participer` p ON j.id_joueur = p.id_joueur
            LEFT JOIN `match` m ON p.id_match = m.id_match
            GROUP BY j.id_joueur;
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
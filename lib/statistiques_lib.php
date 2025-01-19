<?php

class StatistiqueLib
{
    private static function getDbConnection()
    {
        $dsn = 'mysql:host=localhost;dbname=gestion_equipe_basket';
        $username = 'root';
        $password = '';
        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public static function getStatsEquipe()
    {
        $pdo = self::getDbConnection();
        $query = '
            SELECT 
                COUNT(*) AS total, 
                SUM(CASE WHEN resultat = "gagne" THEN 1 ELSE 0 END) AS gagnes,
                SUM(CASE WHEN resultat = "nul" THEN 1 ELSE 0 END) AS nuls,
                SUM(CASE WHEN resultat = "perdu" THEN 1 ELSE 0 END) AS perdus
            FROM matchs';
        $stmt = $pdo->query($query);
        return $stmt->fetch();
    }

    public static function getStatsJoueurs()
    {
        $pdo = self::getDbConnection();
        $query = '
            SELECT 
                joueurs.nom,
                joueurs.statut,
                joueurs.poste,
                COUNT(CASE WHEN participations.titularisation = 1 THEN 1 ELSE NULL END) AS titularisations,
                COUNT(CASE WHEN participations.remplacement = 1 THEN 1 ELSE NULL END) AS remplacements,
                AVG(evaluations.note) AS moyenne_evaluations,
                ROUND(AVG(CASE WHEN matchs.resultat = "gagne" THEN 100 ELSE 0 END), 2) AS pourcentage_victoires,
                MAX(participations.selections_consecutives) AS selections_consecutives
            FROM joueurs
            LEFT JOIN participations ON joueurs.id = participations.joueur_id
            LEFT JOIN matchs ON participations.match_id = matchs.id
            LEFT JOIN evaluations ON joueurs.id = evaluations.joueur_id
            GROUP BY joueurs.id';
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    }
}

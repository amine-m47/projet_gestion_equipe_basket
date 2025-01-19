<?php

class StatsController
{
    public function index()
    {
        // Inclure la bibliothèque
        require_once __DIR__ . '/../lib/statistique_lib.php';

        // Récupérer les statistiques
        $statsEquipe = StatistiqueLib::getStatsEquipe();
        $statsJoueurs = StatistiqueLib::getStatsJoueurs();

        // Passer les données à la vue
        require_once __DIR__ . '/../views/stats/index.php';
    }
}

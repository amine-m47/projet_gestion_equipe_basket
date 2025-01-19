<?php

require_once 'models/StatsModel.php';

class StatsController {
    private $statsModel;

    public function __construct($db) {
        $this->statsModel = new StatsModel($db);
    }

    public function showStatsPage() {
        // Récupérer les données depuis le modèle
        $statsEquipe = $this->statsModel->getStatsEquipe();
        $statsJoueurs = $this->statsModel->getStatsJoueurs();

        // Charger la vue avec les données
        require 'views/stats.php';
    }
}

?>
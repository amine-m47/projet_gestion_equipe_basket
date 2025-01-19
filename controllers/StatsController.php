<?php
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données
require_once __DIR__ . '/../lib/statistiques_lib.php'; // Fonctions pour les stats

class StatsController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Dans la méthode index du contrôleur
    public function index() {
        // Récupérer les stats depuis la bibliothèque
        $statsEquipe = getEquipeStats($this->pdo);
        $statsJoueurs = getJoueurStats($this->pdo);
    
        // Debug : Vérifiez les données récupérées
        //var_dump($statsEquipe);
        //var_dump($statsJoueurs);  

        // Charger la vue avec les données
        require_once __DIR__ . '/../views/stats/index.php';
    }
    

}
?>

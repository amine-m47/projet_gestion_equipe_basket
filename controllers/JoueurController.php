<?php
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données
require_once __DIR__ . '/../lib/joueur_lib.php'; // Fonction pour ajouter un joueur

class JoueurController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour afficher la liste des joueurs
    public function index() {
        $joueurs = getAllJoueurs($this->pdo);
        require __DIR__ . '/../views/joueurs/index.php';
    }

    // Méthode pour ajouter un joueur
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'numero' => $_POST['numero_licence_joueur'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'date_naissance' => $_POST['date_naissance'],
                'taille' => $_POST['taille'],
                'poids' => $_POST['poids'],
                'statut' => $_POST['statut']
            ];

            // Ajouter le joueur dans la base de données
            createJoueur($this->pdo, $data);

            // Rediriger vers la liste des joueurs après ajout
            header('Location: index.php');
            exit;
        }
    }}
?>

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

    // Méthode pour afficher le formulaire d'ajout de joueur
    public function create() {
        require __DIR__ . '/../views/joueurs/create.php';
    }

    // Méthode pour traiter l'ajout de joueur (via le formulaire)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $data = [
                'numero' => $_POST['numero_licence_joueur'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'date_naissance' => $_POST['date_naissance'],
                'taille' => $_POST['taille'],
                'poids' => $_POST['poids'],
                'statut' => $_POST['statut']
            ];

            // Appeler la fonction pour ajouter un joueur
            createJoueur($this->pdo, $data);

            // Redirection après ajout
            header('Location: /../views/joueurs/index.php');  // Redirige vers la liste des joueurs
            exit;
        }
    }
}
?>

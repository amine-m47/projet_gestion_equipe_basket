<?php
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données
require_once __DIR__ . '/../lib/joueur_lib.php'; // Fonction pour ajouter un joueur

class JoueurController {
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Afficher la liste des joueurs
    public function index() {
        // Récupère les joueurs depuis la base de données
        $joueurs = getAllJoueurs($this->pdo);

        // Inclut la vue index.php et passe les données
        require __DIR__ . '/../views/joueurs/index.php';
    }

    // Afficher le formulaire pour ajouter un joueur
    public function create() {
        // Affiche le formulaire pour ajouter un joueur
        require __DIR__ . '/../views/joueurs/create.php';
    }

    // Ajouter un joueur
    public function store() {
        // Vérifie si le formulaire a été soumis avec toutes les données nécessaires
        if (isset($_POST['numero_licence_joueur'], $_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['taille'], $_POST['poids'], $_POST['statut'])) {

            // Récupère les données du formulaire
            $data = [
                'numero' => $_POST['numero_licence_joueur'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'date_naissance' => $_POST['date_naissance'],
                'taille' => $_POST['taille'],
                'poids' => $_POST['poids'],
                'statut' => $_POST['statut']
            ];

            // Appelle la fonction pour ajouter un joueur dans la base de données
            createJoueur($this->pdo, $data);

            // Redirige vers la liste des joueurs après l'ajout
            header("Location: /public/joueurs/index.php");
            exit();
        } else {
            // Si une donnée est manquante, afficher un message d'erreur
            echo "Tous les champs sont obligatoires.";
        }
    }
}

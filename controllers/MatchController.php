<?php
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données
require_once __DIR__ . '/../lib/match_lib.php'; // Fonctions pour gérer les matchs

class MatchController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $this->delete(); // Appeler la méthode delete
            } elseif (isset($_POST['action']) && $_POST['action'] === 'store') {
                $this->store(); // Ajouter un match
            } elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
                $this->update(); // Mettre à jour un match
            }
        } else {
            // Par défaut, afficher la liste des matchs
            $this->index();
        }
    }

    // Méthode pour afficher la liste des matchs
    public function index() {
        return getAllMatchs($this->pdo); // Récupérer tous les matchs
    }

    // Méthode pour afficher un match spécifique
    public function show($id_match) {
        return getMatchById($this->pdo, $id_match); // Récupérer un match par son ID
    }

    // Ajouter un nouveau match
    public function store() {
        $data = [
            'date_heure' => $_POST['date_heure'],
            'lieu_rencontre' => $_POST['lieu_rencontre'],
            'domicile' => isset($_POST['domicile']) ? 1 : 0, // Si la case domicile est cochée, domicile = 1, sinon 0
            'resultat' => $_POST['resultat'], // Résultat du match : victoire (1) ou défaite (0)
            'equipe_adverse' => $_POST['equipe_adverse'],
        ];

        createMatch($this->pdo, $data); // Appeler la fonction pour insérer le match
        header("Location: index.php"); // Rediriger après l'ajout
        exit();
    }

    // Mettre à jour un match existant
    public function update() {
        if (isset($_POST['id_match'])) {
            $data = [
                'id_match' => $_POST['id_match'],
                'date_heure' => $_POST['date_heure'],
                'lieu_rencontre' => $_POST['lieu_rencontre'],
                'domicile' => isset($_POST['domicile']) ? 1 : 0, // Si la case domicile est cochée, domicile = 1, sinon 0
                'resultat' => $_POST['resultat'], // Résultat du match : victoire (1) ou défaite (0)
                'equipe_adverse' => $_POST['equipe_adverse'],
            ];
            echo 'ok';
            updateMatch($this->pdo, $data); // Appeler la fonction pour mettre à jour le match
            header("Location: index.php"); // Rediriger après la mise à jour
            exit();
        }
        echo 'nn';
    }

    // Supprimer un match
    public function delete() {
        if (isset($_POST['id_match'])) {
            $id_match = $_POST['id_match'];
            deleteMatch($this->pdo, $id_match); // Appeler la fonction de suppression
            header("Location: index.php"); // Rediriger vers la page de la liste des matchs
            exit(); // Toujours appeler exit() après une redirection pour s'assurer que le script s'arrête
        }
    }
}
?>

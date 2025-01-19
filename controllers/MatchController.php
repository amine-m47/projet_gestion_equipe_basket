<?php
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données
require_once __DIR__ . '/../lib/match_lib.php'; // Fonctions pour gérer les matchs

class MatchController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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
            'equipe_adverse' => $_POST['equipe_adverse'],
        ];

        createMatchNull($this->pdo, $data); // Appeler la fonction pour insérer le match
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
                'equipe_adverse' => $_POST['equipe_adverse'],
            ];
            updateMatch($this->pdo, $data); // Appeler la fonction pour mettre à jour le match
            header("Location: index.php"); // Rediriger après la mise à jour
            exit();
        }
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

    public function updateResult($id_match, $resultat) {
        return updateMatchResult($this->pdo, $id_match, $resultat);
    }

    public function getMatchsAVenir() {
        return getMatchsAVenir($this->pdo);
    }


    public function getJoueursActifs() {
        return $this->matchModel->getJoueursActifs();
    }

    public function getMatchDetails($matchId) {
        return $this->matchModel->getMatchDetails($matchId);
    }

    public function ajouterParticipation($joueurId, $matchId, $poste, $titulaire) {
        return $this->matchModel->ajouterParticipation($joueurId, $matchId, $poste, $titulaire);
    }
}
?>

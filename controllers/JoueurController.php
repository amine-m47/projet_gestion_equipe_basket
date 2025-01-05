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
        return getAllJoueurs($this->pdo);
    }

    // Afficher les détails d'un joueur ainsi que ses notes
    public function showCommentaires($id_joueur) {
        // Récupérer les informations du joueur et ses notes
        $joueur = getJoueurById($this->pdo, $id_joueur); // Vous devrez définir cette fonction pour récupérer les infos du joueur
        $notes = getNotesJoueur($this->pdo, $id_joueur); // Récupérer les notes du joueur
        return ['joueur' => $joueur, 'notes' => $notes];
    }

    public function show($id_joueur) {
        return getJoueurById($this->pdo, $id_joueur);
    }

    // Ajouter un nouveau joueur
    public function store() {
        $data = [
            'numero_licence_joueur' => $_POST['numero_licence_joueur'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'date_naissance' => $_POST['date_naissance'],
            'taille' => $_POST['taille'],
            'poids' => $_POST['poids'],
            'statut' => $_POST['statut'],
        ];

        createJoueur($this->pdo, $data); // Appeler la fonction pour insérer le joueur
        header("Location: index.php"); // Rediriger après l'ajout
        exit();
    }

    // Mettre à jour un joueur
    public function update() {
        if (isset($_POST['id_joueur'])) {
            $data = [
                'id_joueur' => $_POST['id_joueur'],
                'numero_licence_joueur' => $_POST['numero_licence_joueur'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'date_naissance' => $_POST['date_naissance'],
                'taille' => $_POST['taille'],
                'poids' => $_POST['poids'],
                'statut' => $_POST['statut'],
            ];

            updateJoueur($this->pdo, $data); // Appeler la fonction pour mettre à jour le joueur
            header("Location: index.php"); // Rediriger après la mise à jour
            exit();
    }}

    // Supprimer un joueur
    public function delete() {
        if (isset($_POST['id_joueur'])) {
            $id_joueur = $_POST['id_joueur'];
            deleteJoueur($this->pdo, $id_joueur); // Appeler la fonction de suppression
            header("Location: index.php"); // Rediriger vers la page de la liste des joueurs
            exit(); // Toujours appeler exit() après une redirection pour s'assurer que le script s'arrête
        }
    }

}
?>

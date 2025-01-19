<?php
require_once __DIR__ . '/../lib/Participer_lib.php';
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données


class ParticiperController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Afficher la page de la feuille de match
    public function show($id_match) {
        $joueursDisponibles = getJoueursDisponibles($this->pdo, $id_match);
        // Récupérer la feuille de match pour ce match
        $feuilleMatch = getParticipation($this->pdo, $id_match);

        return ['joueursDisponibles' => $joueursDisponibles, 'feuilleMatch' => $feuilleMatch];
    }

    // Ajouter un joueur à la feuille de match
    public function addJoueur($id_match, $id_joueur, $poste, $titulaire) {
        // Ajouter le joueur à la base de données
        ajouterJoueur($this->pdo, $id_match, $id_joueur, $poste, $titulaire);
    }


    // Modifier la participation d'un joueur
    public function modifyJoueur($id_match, $id_joueur, $titulaire, $poste) {
        modifierParticipation($this->pdo, $id_match, $id_joueur, $titulaire, $poste);
    }

    // Retirer un joueur d'une feuille de match
    public function removeJoueur($id_match, $id_joueur) {
        retirerJoueur($this->pdo, $id_match, $id_joueur);
    }

    // Vérifier le nombre de joueurs sélectionnés
    public function checkJoueurs($id_match, $nombre_requis) {
        return verifierNombreJoueurs($this->pdo, $id_match, $nombre_requis);
    }

    // Évaluer un joueur
    public function evaluerJoueur($id_match, $id_joueur, $note) {
        evaluerJoueur($this->pdo, $id_match, $id_joueur, $note);
    }

    public function removeAllJoueurs($id_match) {
        retirerAllJoueurs($this->pdo, $id_match);
    }

}
?>

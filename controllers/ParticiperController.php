<?php
require_once __DIR__ . '/../lib/participer_lib.php';

class ParticiperController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un joueur à la feuille de match
    public function ajouterParticipation($id_joueur, $id_match, $role_joueur, $titulaire) {
        if (!isJoueurActif($this->pdo, $id_joueur)) {
            throw new Exception("Le joueur n'est pas actif.");
        }
        ajouterParticipation($this->pdo, $id_joueur, $id_match, $role_joueur, $titulaire);
    }

    // Modifier la participation d'un joueur
    public function modifierParticipation($id_joueur, $id_match, $role_joueur, $titulaire) {
        if (!isJoueurActif($this->pdo, $id_joueur)) {
            throw new Exception("Le joueur n'est pas actif.");
        }
        modifierParticipation($this->pdo, $id_joueur, $id_match, $role_joueur, $titulaire);
    }

    // Retirer un joueur de la feuille de match
    public function retirerJoueur($id_joueur, $id_match) {
        retirerJoueur($this->pdo, $id_joueur, $id_match);
    }

    // Récupérer les joueurs d'un match spécifique
    public function getJoueursDeMatch($id_match) {
        return getJoueursDeMatch($this->pdo, $id_match);
    }

    // Vérifier que le nombre de joueurs correspond à celui du sport choisi
    public function verifierNombreJoueurs($joueursSelectionnes, $nombreJoueursRequis) {
        return verifierNombreJoueursValide($joueursSelectionnes, $nombreJoueursRequis);
    }
}
?>

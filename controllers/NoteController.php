<?php
require_once __DIR__ . '/../lib/note_personnelle_lib.php'; // Inclure le fichier avec les fonctions
require_once __DIR__ . '/../config/database.php'; // Connexion à la base de données

class NoteController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une note
    public function ajouterNote($id_joueur, $texte) {
        ajouterNote($this->pdo, $id_joueur, $texte); // Utiliser la fonction dans note_personnelle_lib.php
    }

    // Modifier une note
    public function modifierNote($id_note, $texte) {
        modifierNote($this->pdo, $id_note, $texte); // Utiliser la fonction dans note_personnelle_lib.php
    }

    // Supprimer une note
    public function supprimerNote($id_note) {
        supprimerNote($this->pdo, $id_note); // Utiliser la fonction dans note_personnelle_lib.php
    }

    // Récupérer toutes les notes d'un joueur
    public function getNotes($id_joueur) {
        return getNotes($this->pdo, $id_joueur); // Utiliser la fonction dans note_personnelle_lib.php
    }
}
?>

<?php
// note_personnelle_lib.php

// Ajouter une note
function ajouterNote($pdo, $id_joueur, $texte) {
    $stmt = $pdo->prepare("INSERT INTO Note_personnelle (texte, date_heure, id_joueur) VALUES (?, NOW(), ?)");
    $stmt->execute([$texte, $id_joueur]);
}

// Modifier une note
function modifierNote($pdo, $id_note, $texte) {
    $stmt = $pdo->prepare("UPDATE Note_personnelle SET texte = ? WHERE id_note = ?");
    $stmt->execute([$texte, $id_note]);
}

// Supprimer une note
function supprimerNote($pdo, $id_note) {
    $stmt = $pdo->prepare("DELETE FROM Note_personnelle WHERE id_note = ?");
    $stmt->execute([$id_note]);
}

// Récupérer toutes les notes d'un joueur
function getNotes($pdo, $id_joueur) {
    $stmt = $pdo->prepare("SELECT * FROM Note_personnelle WHERE id_joueur = ? ORDER BY date_heure DESC");
    $stmt->execute([$id_joueur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

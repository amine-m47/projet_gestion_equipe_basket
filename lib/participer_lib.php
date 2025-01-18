<?php

// Ajouter un joueur à la feuille de match
function ajouterParticipation($pdo, $id_joueur, $id_match, $role_joueur, $titulaire) {
    $stmt = $pdo->prepare('INSERT INTO Participer (id_joueur, id_match, role_joueur, titulaire) VALUES (?, ?, ?, ?)');
    $stmt->execute([$id_joueur, $id_match, $role_joueur, $titulaire]);
}

// Modifier la participation du joueur
function modifierParticipation($pdo, $id_joueur, $id_match, $role_joueur, $titulaire) {
    $stmt = $pdo->prepare('UPDATE Participer SET role_joueur = ?, titulaire = ? WHERE id_joueur = ? AND id_match = ?');
    $stmt->execute([$role_joueur, $titulaire, $id_joueur, $id_match]);
}

// Retirer un joueur de la feuille de match
function retirerJoueur($pdo, $id_joueur, $id_match) {
    $stmt = $pdo->prepare('DELETE FROM Participer WHERE id_joueur = ? AND id_match = ?');
    $stmt->execute([$id_joueur, $id_match]);
}

// Récupérer tous les joueurs d'un match
function getJoueursDeMatch($pdo, $id_match) {
    $stmt = $pdo->prepare('SELECT * FROM Participer WHERE id_match = ?');
    $stmt->execute([$id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérifier si un joueur est actif
function isJoueurActif($pdo, $id_joueur) {
    $stmt = $pdo->prepare('SELECT statut FROM Joueur WHERE id_joueur = ?');
    $stmt->execute([$id_joueur]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['statut'] === 'actif';
}

// Vérifier le nombre de joueurs sélectionnés
function verifierNombreJoueursValide($joueursSelectionnes, $nombreJoueursRequis) {
    return count($joueursSelectionnes) === $nombreJoueursRequis;
}
?>

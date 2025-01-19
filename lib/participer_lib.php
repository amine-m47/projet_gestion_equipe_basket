<?php

// Récupérer les joueurs disponibles pour un match (actifs et non encore sélectionnés)
function getJoueursDisponibles($pdo, $id_match) {
    $stmt = $pdo->prepare(
        "SELECT j.id_joueur, j.prenom, j.nom, j.numero_licence_joueur
        FROM Joueur j
        WHERE lower(j.statut) = 'actif'
        AND j.id_joueur NOT IN (SELECT id_joueur FROM Participer WHERE id_match = :id_match)"
    );
    $stmt->execute([':id_match' => $id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter un joueur à la feuille de match
function ajouterJoueurMatch($pdo, $id_match, $id_joueur, $poste, $titulaire) {
    $stmt = $pdo->prepare("INSERT INTO Participer (id_match, id_joueur, poste, titulaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_match, $id_joueur, $poste, $titulaire]);
}

// Modifier la participation d'un joueur (titulaire, poste)
function modifierParticipation($pdo, $id_match, $id_joueur, $poste, $titulaire) {
    $stmt = $pdo->prepare("UPDATE Participer SET poste = ?, titulaire = ? WHERE id_match = ? AND id_joueur = ?");
    $stmt->execute([$poste, $titulaire, $id_match, $id_joueur]);
}

// Retirer un joueur de la feuille de match
function retirerJoueur($pdo, $id_match, $id_joueur) {
    $stmt = $pdo->prepare("DELETE FROM Participer WHERE id_match = :id_match AND id_joueur = :id_joueur");
    $stmt->execute([':id_match' => $id_match, ':id_joueur' => $id_joueur]);
}

// Vérifier si le nombre de joueurs sélectionnés est correct (par rapport au sport)
function verifierNombreJoueurs($pdo, $id_match, $nombre_requis) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Participer WHERE id_match = :id_match");
    $stmt->execute([':id_match' => $id_match]);
    $nombre_joueurs = $stmt->fetchColumn();
    return $nombre_joueurs == $nombre_requis;
}

// Évaluer un joueur dans un match joué
function evaluerJoueur($pdo, $id_match, $id_joueur, $note) {
    $stmt = $pdo->prepare("UPDATE Participer SET note_joueur = :note WHERE id_match = :id_match AND id_joueur = :id_joueur");
    $stmt->execute([
        ':id_match' => $id_match,
        ':id_joueur' => $id_joueur,
        ':note' => $note
    ]);
}
// Récupérer la participation d'un joueur pour un match donné (avec les informations du joueur)
// Récupérer la participation de tous les joueurs pour un match donné (avec les informations du joueur)
function getParticipation($pdo, $id_match) {
    // Requête SQL pour récupérer la participation et les informations du joueur
    $stmt = $pdo->prepare(
        "SELECT p.id_joueur, p.id_match, p.poste, p.titulaire, p.note_joueur, j.prenom, j.nom
        FROM Participer p
        JOIN Joueur j ON p.id_joueur = j.id_joueur
        WHERE p.id_match = :id_match"
    );
    $stmt->execute([':id_match' => $id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retourne toutes les participations sous forme de tableau associatif
}

?>

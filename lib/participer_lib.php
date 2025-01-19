<?php

// Récupérer les joueurs disponibles pour un match (actifs et non encore sélectionnés)
function getJoueursDisponibles($pdo, $id_match) {
    $stmt = $pdo->prepare(
        "SELECT j.id_joueur, j.nom, j.prenom
        FROM Joueur j
        WHERE lower(j.statut) = 'actif'
        AND j.id_joueur NOT IN (
            SELECT p.id_joueur
            FROM Participer p
            WHERE p.id_match = :id_match
        )"
    );
    $stmt->execute([':id_match' => $id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Ajouter un joueur à la feuille de match
function ajouterJoueur($pdo,$id_match, $id_joueur, $poste, $titulaire) {
    // Vérifier si ce joueur est déjà assigné à ce poste dans ce match
    $query = "SELECT * FROM Participer WHERE id_match = :id_match AND id_joueur = :id_joueur";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id_match' => $id_match,
        ':id_joueur' => $id_joueur,
    ]);
    if ($stmt->rowCount() > 0) {
         // Si on ne remplace pas, on renvoie une erreur ou on fait rien
                $_SESSION['error'] = "Le joueur est déjà assigné à ce poste.";
                return;
            }
    // Ajouter le joueur à la feuille de match
    $query = "INSERT INTO Participer (id_match, id_joueur, poste, titulaire) VALUES (:id_match, :id_joueur, :poste, :titulaire)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id_match' => $id_match,
        ':id_joueur' => $id_joueur,
        ':poste' => $poste,
        ':titulaire' => $titulaire,
    ]);
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
function ajouterNote($pdo, $id_joueur,$id_match,  $note) {
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

// ParticiperController.php
function retirerAllJoueurs($pdo, $id_match) {
    // Supprimer tous les joueurs pour ce match
    $query = $pdo->prepare("DELETE FROM participer WHERE id_match = :id_match");
    $query->execute(['id_match' => $id_match]);
}

?>

<?php

// Récupérer tous les joueurs
function getAllJoueurs($pdo) {
    $stmt = $pdo->query('SELECT * FROM Joueur');
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
}

// Récupérer un joueur par son ID
function getJoueurById($pdo, $id_joueur) {
    $stmt = $pdo->prepare('SELECT * FROM Joueur WHERE id_joueur = :id_joueur');
    $stmt->bindParam(':id_joueur', $id_joueur, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ajouter un nouveau joueur
function createJoueur($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO Joueur (numero_licence_joueur, nom, prenom, date_naissance, taille, poids, statut)
        VALUES (:numero_licence_joueur, :nom, :prenom, :date_naissance, :taille, :poids, :statut)
    ');
    $stmt->execute($data);
}

// Mettre à jour un joueur existant
function updateJoueur($pdo, $data) {
    $stmt = $pdo->prepare('
        UPDATE Joueur
        SET numero_licence_joueur = :numero_licence_joueur,
            nom = :nom,
            prenom = :prenom,
            date_naissance = :date_naissance,
            taille = :taille,
            poids = :poids,
            statut = :statut
        WHERE id_joueur = :id_joueur
    ');
    $stmt->execute($data);
}

// Supprimer un joueur
function deleteJoueur($pdo, $id_joueur) {
    $stmt = $pdo->prepare('DELETE FROM Joueur WHERE id_joueur = :id_joueur');
    $stmt->bindParam(':id_joueur', $id_joueur, PDO::PARAM_INT);
    $stmt->execute();
}


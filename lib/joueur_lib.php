<?php

function getAllJoueurs($pdo) {
    $stmt = $pdo->query('SELECT * FROM Joueur');
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
}

function createJoueur($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO Joueur (numero_licence_joueur, nom, prenom, date_naissance, taille, poids, statut)
        VALUES (:numero, :nom, :prenom, :date_naissance, :taille, :poids, :statut)
    ');
    $stmt->execute($data);  // Exécuter la requête avec les données du formulaire
}

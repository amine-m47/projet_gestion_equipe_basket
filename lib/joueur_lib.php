<?php
function getAllJoueurs($pdo) {
    $stmt = $pdo->query('SELECT * FROM Joueurs');
    return $stmt->fetchAll();
}

function createJoueur($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO Joueurs (numero_licence_joueur, nom, prenom, date_naissance, taille, poids, statut)
        VALUES (:numero, :nom, :prenom, :date_naissance, :taille, :poids, :statut)
    ');
    $stmt->execute($data);
}
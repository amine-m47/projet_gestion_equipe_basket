<?php
function getAllMatchs($pdo) {
    $stmt = $pdo->query('SELECT * FROM Matchs');
    return $stmt->fetchAll();
}

function createMatch($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO Matchs (date_heure, lieu_rencontre, domicile_ou_exterieur, resultat, equipe_adverse)
        VALUES (:date_heure, :lieu, :domicile, :resultat, :equipe)
    ');
    $stmt->execute($data);
}

function updateMatch($pdo, $data) {
    $stmt = $pdo->prepare('
        UPDATE Matchs
        SET date_heure = :date_heure, lieu_rencontre = :lieu, domicile_ou_exterieur = :domicile,
            resultat = :resultat, equipe_adverse = :equipe
        WHERE id_match = :id
    ');
    $stmt->execute($data);
}

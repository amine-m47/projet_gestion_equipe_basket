<?php
function getAllMatchs($pdo) {
    // Trier par date_heure décroissante pour avoir les matchs les plus récents en premier
    $stmt = $pdo->query('SELECT * FROM `Match` ORDER BY date_heure DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif des matchs triés
}


function createMatch($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO `Match` (date_heure, lieu_rencontre, domicile, resultat, equipe_adverse)
        VALUES (:date_heure, :lieu_rencontre, :domicile, :resultat, :equipe_adverse)
    ');
    $stmt->execute($data);
}

function createMatchNull($pdo, $data) {
    $stmt = $pdo->prepare('
        INSERT INTO `Match` (date_heure, lieu_rencontre, domicile, resultat, equipe_adverse)
        VALUES (:date_heure, :lieu_rencontre, :domicile, null, :equipe_adverse)
    ');
    $stmt->execute($data);
}

function updateMatch($pdo, $data) {
    $stmt = $pdo->prepare('
        UPDATE `Match`
        SET date_heure = :date_heure, lieu_rencontre = :lieu_rencontre, domicile = :domicile, resultat = null, equipe_adverse = :equipe_adverse
        WHERE id_match = :id_match
    ');
    $stmt->execute($data);
}

function getMatchById($pdo, $id_match) {
    $stmt = $pdo->prepare('SELECT * FROM `Match` WHERE id_match = :id_match');
    $stmt->execute(['id_match' => $id_match]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un tableau associatif du match
}

// Supprimer un match
function deleteMatch($pdo, $id_match) {
    $stmt = $pdo->prepare('DELETE FROM `Match` WHERE id_match = :id_match');
    $stmt->bindParam(':id_match', $id_match, PDO::PARAM_INT);
    $stmt->execute();
}

// match_lib.php

function updateMatchResult($pdo, $id_match, $resultat) {
    $stmt = $pdo->prepare("UPDATE `Match` SET resultat = :resultat WHERE id_match = :id_match");
    return $stmt->execute([
        ':resultat' => $resultat,
        ':id_match' => $id_match,
    ]);
}

// Récupérer tous les matchs à venir (non joués)
function getMatchsAVenir($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM Matchs WHERE resultat IS NULL ORDER BY date_heure ASC');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>

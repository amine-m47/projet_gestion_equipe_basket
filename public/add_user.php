<?php
require_once __DIR__ . '/../config/database.php';

$hashedPassword = password_hash('coach', PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO Utilisateur (id, mail, nom, prenom, password) VALUES (:id, :mail, :nom, :prenom, :password)');
$stmt->execute([
    'id' => uniqid(),
    'mail' => 'coach@coach.fr',
    'nom' => 'coach',
    'prenom' => 'coach',
    'password' => $hashedPassword,
]);

echo "Utilisateur créé avec succès !";
?>

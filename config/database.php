<?php
// config/database.php
//$host = 'sql103.infinityfree.com';
//$port='3306';
//$dbname = 'if0_37814117_projet_gestion_equipe_basket';
//$username = 'if0_37814117';
//$password = 'wByPsY0qcrqmVv';

$host = 'localhost';
$dbname = 'basket';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

<?php
// Inclure la configuration de la base de données
require_once __DIR__ . '/../config/database.php';
// Inclure le contrôleur
require_once __DIR__ . '/JoueurController.php';

// Vérifier si la méthode est définie dans l'URL (ex: ?method=store)
$method = $_GET['method'] ?? 'index';  // Par défaut, appeler la méthode 'index'

// Initialiser le contrôleur avec la connexion PDO
$controller = new JoueurController($pdo);

// Vérifier si la méthode existe dans le contrôleur
if (method_exists($controller, $method)) {
    $controller->$method(); // Appeler la méthode du contrôleur
} else {
    // Si la méthode n'existe pas, afficher une erreur
    echo "Méthode introuvable.";
}

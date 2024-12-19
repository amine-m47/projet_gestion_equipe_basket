<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->login($_POST['email'], $_POST['password']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<h1>Connexion</h1>
<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>
<form method="POST">
    <label>Email :</label>
    <input type="email" name="email" required>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
</form>
</body>
</html>

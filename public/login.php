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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Team Basket</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="login-container">

    <h1>Connexion à votre compte</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div class="input-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required placeholder="Votre email">
        </div>

        <div class="input-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required placeholder="Votre mot de passe">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Se connecter</button>
        </div>


    </form>
</div>
</body>
</html>

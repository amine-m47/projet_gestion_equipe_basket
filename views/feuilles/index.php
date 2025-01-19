<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ParticiperController.php';

// Initialisation du contrôleur
$controller = new ParticiperController($pdo);

// Récupération des joueurs actifs et des matchs à venir
$joueursActifs = $controller->getJoueursActifs();
$matchsAvenir = $controller->getMatchsAvenir();
?>

<!-- Affichage des joueurs actifs -->
<h2>Joueurs actifs</h2>
<ul>
    <?php foreach ($joueursActifs as $joueur): ?>
        <li><?= htmlspecialchars($joueur['prenom']) . ' ' . htmlspecialchars($joueur['nom']) ?></li>
    <?php endforeach; ?>
</ul>

<!-- Affichage des matchs à venir -->
<h2>Matchs à venir</h2>
<ul>
    <?php foreach ($matchsAvenir as $match): ?>
        <li><?= htmlspecialchars($match['equipe_adverse']) ?> - <?= htmlspecialchars($match['date_heure']) ?></li>
    <?php endforeach; ?>
</ul>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

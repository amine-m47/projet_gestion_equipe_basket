<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/StatsController.php';

// Initialisation du contrôleur
$controller = new StatsController($pdo);

// Récupération des statistiques via le contrôleur
$statsEquipe = null;
$statsJoueurs = [];

$controller->index();
?>

<h1>Statistiques de l'équipe</h1>

<h2>Statistiques globales</h2>
<table>
    <tr>
        <th>Total de matchs</th>
        <td><?= isset($statsEquipe['total']) ? $statsEquipe['total'] : 0 ?></td>
    </tr>
    <tr>
        <th>Matchs gagnés</th>
        <td>
            <?= isset($statsEquipe['gagnes']) ? $statsEquipe['gagnes'] : 0 ?> 
            (<?= isset($statsEquipe['total']) && $statsEquipe['total'] > 0 
                ? round(($statsEquipe['gagnes'] / $statsEquipe['total']) * 100, 2) 
                : 0 ?>%)
        </td>
    </tr>
    <tr>
        <th>Matchs nuls</th>
        <td>
            <?= isset($statsEquipe['nuls']) ? $statsEquipe['nuls'] : 0 ?> 
            (<?= isset($statsEquipe['total']) && $statsEquipe['total'] > 0 
                ? round(($statsEquipe['nuls'] / $statsEquipe['total']) * 100, 2) 
                : 0 ?>%)
        </td>
    </tr>
    <tr>
        <th>Matchs perdus</th>
        <td>
            <?= isset($statsEquipe['perdus']) ? $statsEquipe['perdus'] : 0 ?> 
            (<?= isset($statsEquipe['total']) && $statsEquipe['total'] > 0 
                ? round(($statsEquipe['perdus'] / $statsEquipe['total']) * 100, 2) 
                : 0 ?>%)
        </td>
    </tr>
</table>

<h2>Statistiques des joueurs</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Statut</th>
            <th>Poste préféré</th>
            <th>Titularisations</th>
            <th>Remplacements</th>
            <th>Évaluations</th>
            <th>% Victoires</th>
            <th>Sélections consécutives</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($statsJoueurs)): ?>
            <?php foreach ($statsJoueurs as $joueur): ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['nom']) ?></td>
                    <td><?= htmlspecialchars($joueur['statut']) ?></td>
                    <td><?= htmlspecialchars($joueur['poste_prefere'] ?? 'Non spécifié') ?></td>
                    <td><?= $joueur['titularisations'] ?? 0 ?></td>
                    <td><?= $joueur['remplacements'] ?? 0 ?></td>
                    <td><?= $joueur['evaluations_count'] ?? 0 ?></td>
                    <td><?= isset($joueur['pourcentage_victoires']) 
                            ? round($joueur['pourcentage_victoires'], 2) . '%' 
                            : '0%' ?></td>
                    <td><?= $joueur['selections_consecutives'] ?? 0 ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucune donnée disponible</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>



<?php require_once __DIR__ . '/../layout/footer.php'; ?>

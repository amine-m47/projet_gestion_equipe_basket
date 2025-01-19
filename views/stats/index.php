<?php
// Inclusion de l'en-tête
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/statistiques_lib.php';
require_once __DIR__ . '/../../controllers/StatsController.php';
?>

<h1>Statistiques de l'équipe</h1>

<h2>Statistiques générales</h2>
<p>Total de matchs : <?= $statsEquipe['total'] ?? 0 ?></p>
<p>Matchs gagnés : <?= $statsEquipe['gagnes'] ?? 0 ?> (<?= round(($statsEquipe['gagnes'] / $statsEquipe['total']) * 100, 2) ?? 0 ?>%)</p>
<p>Matchs nuls : <?= $statsEquipe['nuls'] ?? 0 ?> (<?= round(($statsEquipe['nuls'] / $statsEquipe['total']) * 100, 2) ?? 0 ?>%)</p>
<p>Matchs perdus : <?= $statsEquipe['perdus'] ?? 0 ?> (<?= round(($statsEquipe['perdus'] / $statsEquipe['total']) * 100, 2) ?? 0 ?>%)</p>

<h2>Statistiques des joueurs</h2>

<table>
    <thead>
        <tr>
            <th>Joueur</th>
            <th>Statut</th>
            <th>Poste préféré</th>
            <th>Titularisations</th>
            <th>Remplacements</th>
            <th>Moyenne des évaluations</th>
            <th>% Victoires</th>
            <th>Sélections consécutives</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($statsJoueurs as $joueur): ?>
        <tr>
            <td><?= htmlspecialchars($joueur['nom']) ?></td>
            <td><?= htmlspecialchars($joueur['statut']) ?></td>
            <td><?= htmlspecialchars($joueur['poste']) ?></td>
            <td><?= $joueur['titularisations'] ?? 0 ?></td>
            <td><?= $joueur['remplacements'] ?? 0 ?></td>
            <td><?= round($joueur['moyenne_evaluations'], 2) ?? 0 ?></td>
            <td><?= $joueur['pourcentage_victoires'] ?? 0 ?>%</td>
            <td><?= $joueur['selections_consecutives'] ?? 0 ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>

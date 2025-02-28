<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/StatsController.php';

// Initialisation du contrôleur
$controller = new StatsController($pdo);

// Récupération des statistiques via le contrôleur
$statsEquipe = getEquipeStats($pdo);
$statsJoueurs = getJoueurStats($pdo);
$controller->index();
        //var_dump($statsEquipe);
        //var_dump($statsJoueurs);  

?>

<style>
main {
    padding: 2rem;
    background: #f9f9f9;
    margin: 2rem auto;
    max-width: 1000px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px
}
</style>



<h1>Statistiques de l'équipe</h1>

<h2>Statistiques globales</h2>
<table>
    <tr>
        <th>Total de matchs</th>
        <td><?=  $statsEquipe['total'] ?></td>
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
            <th>Nombre de Titularisations</th>
            <th>Nombre de Remplacements</th>
            <th>Moyenne des évaluations</th>
            <th>Pourcentage de Victoires</th>
            <th>Sélections consécutives</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($statsJoueurs)): ?>
            <?php foreach ($statsJoueurs as $joueur): ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['nom_complet']) ?></td>
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

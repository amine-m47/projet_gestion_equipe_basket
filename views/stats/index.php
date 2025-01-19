<?php
// Inclusion de l'en-tête
require_once __DIR__ . '/../layout/header.php';
?>

<h1>Statistiques de l'équipe</h1>

<section>
    <h2>Statistiques générales</h2>
    <p>Total de matchs : <?= $statsEquipe['total'] ?? 'Non défini' ?></p>
    <p>Matchs gagnés : <?= $statsEquipe['gagnes'] ?? 'Non défini' ?> 
    (<?= isset($statsEquipe['gagnes'], $statsEquipe['total']) && $statsEquipe['total'] > 0 ? 
        round(($statsEquipe['gagnes'] / $statsEquipe['total']) * 100, 2) . '%' : 'N/A' ?>)
    </p>
    <p>Matchs nuls : <?= $statsEquipe['nuls'] ?? 'Non défini' ?> 
    (<?= isset($statsEquipe['nuls'], $statsEquipe['total']) && $statsEquipe['total'] > 0 ? 
        round(($statsEquipe['nuls'] / $statsEquipe['total']) * 100, 2) . '%' : 'N/A' ?>)
    </p>
    <p>Matchs perdus : <?= $statsEquipe['perdus'] ?? 'Non défini' ?> 
    (<?= isset($statsEquipe['perdus'], $statsEquipe['total']) && $statsEquipe['total'] > 0 ? 
        round(($statsEquipe['perdus'] / $statsEquipe['total']) * 100, 2) . '%' : 'N/A' ?>)
    </p>
</section>

<section>
    <h2>Statistiques des joueurs</h2>
    <?php if (!empty($statsJoueurs)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Statut</th>
                    <th>Poste préféré</th>
                    <th>Titularisations</th>
                    <th>Remplacements</th>
                    <th>Moyenne</th>
                    <th>% Victoires</th>
                    <th>Sélections consécutives</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statsJoueurs as $joueur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['nom']) ?></td>
                        <td><?= htmlspecialchars($joueur['statut']) ?></td>
                        <td><?= htmlspecialchars($joueur['poste']) ?></td>
                        <td><?= $joueur['titularisations'] ?></td>
                        <td><?= $joueur['remplacements'] ?></td>
                        <td><?= $joueur['moyenne'] ?></td>
                        <td><?= $joueur['victoires'] ?>%</td>
                        <td><?= $joueur['selections'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucune donnée disponible pour les joueurs.</p>
    <?php endif; ?>
</section>



<?php require_once __DIR__ . '/../layout/footer.php'; ?>

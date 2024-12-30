<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>Liste des matchs</h1>
<a href="create.php">Ajouter un match</a>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Date et Heure</th>
        <th>Lieu</th>
        <th>Domicile/Extérieur</th>
        <th>Résultat</th>
        <th>Équipe Adverse</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($matchs as $match): ?>
        <tr>
            <td><?= htmlspecialchars($match['id_match']) ?></td>
            <td><?= htmlspecialchars($match['date_heure']) ?></td>
            <td><?= htmlspecialchars($match['lieu_rencontre']) ?></td>
            <td><?= htmlspecialchars($match['domicile_ou_exterieur']) ?></td>
            <td><?= htmlspecialchars($match['resultat']) ?></td>
            <td><?= $match['equipe_adverse'] ? 'Oui' : 'Non' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

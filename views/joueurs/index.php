<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des joueurs</title>
</head>
<body>
<h1>Liste des joueurs</h1>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Numéro de licence</th>
        <th>Date de naissance</th>
        <th>Taille</th>
        <th>Poids</th>
        <th>Statut</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($joueurs as $joueur): ?>
        <tr>
            <td><?= htmlspecialchars($joueur['id_joueur']) ?></td>
            <td><?= htmlspecialchars($joueur['nom']) ?></td>
            <td><?= htmlspecialchars($joueur['prenom']) ?></td>
            <td><?= htmlspecialchars($joueur['numero_licence_joueur']) ?></td>
            <td><?= htmlspecialchars($joueur['date_naissance']) ?></td>
            <td><?= htmlspecialchars($joueur['taille']) ?></td>
            <td><?= htmlspecialchars($joueur['poids']) ?></td>
            <td><?= htmlspecialchars($joueur['statut']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>

<?php
require_once __DIR__ . '/../layout/header.php';
// Inclure le fichier contenant la connexion à la base de données
require_once __DIR__ . '/../../config/database.php'; // Chemin correct selon ton projet

// Inclure le fichier contenant la fonction getAllJoueurs
require_once __DIR__ . '/../../lib/joueur_lib.php'; // Assure-toi que le chemin est correct

// Appeler la fonction pour récupérer les joueurs
$joueurs = getAllJoueurs($pdo);
?>
<h1>Liste des joueurs</h1>
<a href="create.php">Ajouter un joueur</a>
<?php if (empty($joueurs)): ?>
    <p>Aucun joueur trouvé.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Numéro de Licence</th>
            <th>Date de Naissance</th>
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
                <td><?= date('d/m/Y', strtotime($joueur['date_naissance'])) ?></td>
                <td><?= htmlspecialchars($joueur['taille']) ?></td>
                <td><?= htmlspecialchars($joueur['poids']) ?></td>
                <td><?= htmlspecialchars($joueur['statut']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

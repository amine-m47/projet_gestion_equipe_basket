<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/joueur_lib.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Récupération des joueurs via la fonction getAllJoueurs
$controller = new JoueurController($pdo);
$joueurs = $controller->index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_joueur = $_POST['id_joueur'];
        $controller->delete();
}
?>

<h1>Liste des joueurs</h1>
<a href="create.php">Ajouter un joueur</a>

<?php if (empty($joueurs)): ?>
    <p>Aucun joueur trouvé.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Numéro de Licence</th>
            <th>Date de Naissance</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($joueurs as $joueur): ?>
            <tr>
                <td><?= htmlspecialchars($joueur['nom']) ?></td>
                <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                <td><?= htmlspecialchars($joueur['numero_licence_joueur']) ?></td>
                <td><?= date('d/m/Y', strtotime($joueur['date_naissance'])) ?></td>
                <td><?= htmlspecialchars($joueur['taille']) ?></td>
                <td><?= htmlspecialchars($joueur['poids']) ?></td>
                <td><?= htmlspecialchars($joueur['statut']) ?></td>
                <td>
                    <!-- Lien pour modifier -->
                    <a href="edit.php?id=<?= $joueur['id_joueur'] ?>">Modifier</a>
                    <!-- Formulaire pour supprimer -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <input type="hidden" name="method" value="delete">
                        <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce joueur ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

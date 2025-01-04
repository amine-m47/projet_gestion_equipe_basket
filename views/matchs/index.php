<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/match_lib.php';
require_once __DIR__ . '/../../controllers/MatchController.php';

// Initialisation du contrôleur
$controller = new MatchController($pdo);

// Récupération des matchs via la fonction du contrôleur
$matchs = $controller->index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_match = $_POST['id_match'];
    $controller->delete();
}
?>

<h1>Liste des matchs</h1>
<a href="create.php">Ajouter un match</a>

<table>
    <thead>
    <tr>
        <th>Date et Heure</th>
        <th>Lieu</th>
        <th>Résultat</th>
        <th>Équipe Adverse</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($matchs)): ?>
        <?php foreach ($matchs as $match): ?>
            <tr>
                <td><?= htmlspecialchars($match['date_heure']) ?></td>
                <td><?= $match['domicile'] ? 'Domicile' : htmlspecialchars($match['lieu_rencontre']) ?></td>
                <td><?= $match['resultat'] ? 'Victoire' : 'Défaite' ?></td>
                <td><?= htmlspecialchars($match['equipe_adverse']) ?></td>
                <td>
                    <!-- Vérifie si la date du match est dans le futur -->
                    <?php
                    // Crée un objet DateTime pour la date du match et la date actuelle
                    $dateMatch = new DateTime($match['date_heure']);
                    $dateNow = new DateTime();

                    // Si la date du match est dans le futur, affiche le bouton "Modifier"
                    if ($dateMatch > $dateNow):
                        ?>
                        <a href="edit.php?id=<?= $match['id_match'] ?>">Modifier</a>
                    <?php endif; ?>

                    <!-- Formulaire pour suppression -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_match" value="<?= $match['id_match'] ?>">
                        <input type="hidden" name="method" value="delete">
                        <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce match ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Aucun match trouvé.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

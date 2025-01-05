<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/MatchController.php';

// Initialisation du contrôleur
$controller = new MatchController($pdo);

// Récupération des matchs via la fonction du contrôleur
$matchs = $controller->index();

// Vérifie si le formulaire soumis est celui pour supprimer un match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'delete') {
    if (isset($_POST['id_match'])) {
        $controller->delete(); // Appeler la méthode du contrôleur pour supprimer
        header("Location: index.php"); // Rediriger après suppression
        exit();
    }
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
                <td>
                    <?php
                    // Vérifie si le match a déjà été joué
                    $dateMatch = new DateTime($match['date_heure']);
                    $dateNow = new DateTime();

                    if ($dateMatch > $dateNow):
                        // Match à venir
                        echo "Match non joué";
                    else:
                            if (is_null($match['resultat'])) {
                                echo 'Résultat non défini';
                            } elseif ($match['resultat'] == 1) {
                                echo 'Victoire';
                            } elseif ($match['resultat'] == 0) {
                                echo 'Défaite';
                            } elseif ($match['resultat'] == 2) {
                                echo 'Match nul';
                            }

                    endif;
                    ?>
                </td>
                <td><?= htmlspecialchars($match['equipe_adverse']) ?></td>
                <td>
                    <?php
                    // Actions conditionnelles
                    if ($dateMatch > $dateNow):
                        // Match à venir
                        ?>
                        <a href="edit.php?id=<?= $match['id_match'] ?>">Modifier</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_match" value="<?= $match['id_match'] ?>">
                            <input type="hidden" name="method" value="delete">
                            <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce match ?')">Supprimer</button>
                        </form>
                    <?php
                    else:
                        // Match déjà joué
                        if (is_null($match['resultat'])):
                            // Ajouter un bouton pour entrer le résultat si non défini
                            ?>
                            <a href="add_result.php?id=<?= $match['id_match'] ?>">Entrer le résultat</a>
                        <?php endif; ?>
                    <?php endif; ?>
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

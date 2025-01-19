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
<style>.match-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-around;
    }

    .match-card {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .match-card.upcoming {
        border-left: 4px solid #007bff; /* bleu pour les matchs à venir */
    }

    .match-card.past {
        border-left: 4px solid #28a745; /* vert pour les matchs passés */
    }

    .match-card.no-result {
        border-left: 4px solid #6c757d; /* gris pour les matchs sans résultat */
    }

    .match-card h3 {
        margin: 0;
        font-size: 1.5em;
        color: #333;
    }

    .match-card p {
        margin: 8px 0;
        color: #555;
    }

    .result {
        font-weight: bold;
    }

    .result.victory {
        color: #28a745; /* Vert pour victoire */
    }

    .result.defeat {
        color: #dc3545; /* Rouge pour défaite */
    }

    .result.draw {
        color: #ffc107; /* Jaune pour match nul */
    }

    .match-actions {
        margin-top: 10px;
    }

    .match-actions a, .match-actions button {
        margin-right: 10px;
    }

    .btn {
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 5px;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-warning {
        background-color: #ffc107;
    }

    .btn-danger {
        background-color: #dc3545;
    }
</style>
<h1>Liste des matchs</h1>
<a href="create.php" class="btn btn-primary">Ajouter un match</a>

<div class="match-list">
    <?php if (!empty($matchs)): ?>
        <?php foreach ($matchs as $match): ?>
            <?php
            $dateMatch = new DateTime($match['date_heure']);
            $dateNow = new DateTime();
            $isPastMatch = $dateMatch < $dateNow;
            $isResultNotSet = is_null($match['resultat']);
            ?>

            <div class="match-card <?php echo $isPastMatch ? 'past' : ($isResultNotSet ? 'no-result' : 'upcoming'); ?>">
                <h3>Match contre <?= htmlspecialchars($match['equipe_adverse']) ?></h3>
                <p><strong>Date et Heure :</strong> <?= htmlspecialchars($match['date_heure']) ?></p>
                <p><strong>Lieu :</strong> <?= $match['domicile'] ? 'Domicile' : htmlspecialchars($match['lieu_rencontre']) ?></p>

                <p>
                    <?php
                    if ($isPastMatch):
                        if ($isResultNotSet) {
                            echo '<a href="add_result.php?id=' . $match['id_match'] . '" class="btn btn-warning">Entrer le résultat</a>';
                        } elseif ($match['resultat'] == 1) {
                            echo '<span class="result victory">Victoire</span>';
                        } elseif ($match['resultat'] == 0) {
                            echo '<span class="result defeat">Défaite</span>';
                        } elseif ($match['resultat'] == 2) {
                            echo '<span class="result draw">Match nul</span>';
                        }
                        else{
                        echo 'Match à venir';}
                    endif;
                    ?>
                </p>

                <div class="match-actions">
                    <?php if (!$isPastMatch): ?>
                        <a href="edit.php?id=<?= $match['id_match'] ?>" class="btn btn-warning">Modifier</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_match" value="<?= $match['id_match'] ?>">
                            <input type="hidden" name="method" value="delete">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce match ?')">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun match trouvé.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

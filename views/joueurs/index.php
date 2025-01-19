<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lib/joueur_lib.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Récupération des joueurs via la fonction getAllJoueurs
$controller = new JoueurController($pdo);
$joueurs = $controller->index();

// Vérifie si le formulaire soumis est celui pour supprimer un joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'delete') {
    if (isset($_POST['id_joueur'])) {
        $controller->delete(); // Appeler la méthode du contrôleur pour supprimer
        header("Location: index.php"); // Rediriger après suppression
        exit();
    }
}
?>

<style>
    /* Liste des joueurs sous forme de cartes */
    .match-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 30px;
    }

    .player-card {
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #ccc;
    }

    .player-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .player-card h3 {
        margin: 0;
        font-size: 1.5em;
        color: #333;
    }

    .player-card p {
        margin: 8px 0;
        color: #555;
    }

    .player-actions {
        margin-top: 10px;
    }

    .player-actions .btn {
        margin-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .mb-4 {
        margin-bottom: 30px;
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

    .btn-warning {
        background-color: #ffc107;
    }

    .btn-info {
        background-color: #ffc107; /* Jaune pour Note */
    }

    .btn-danger {
        background-color: #dc3545;
    }
</style>

<h1 class="text-center">Liste des joueurs</h1>
<div class="text-center mb-4">
    <a href="create.php" class="btn btn-primary">+ Ajouter un joueur</a>
</div>

<div class="match-list">
    <?php if (empty($joueurs)): ?>
        <p class="text-center">Aucun joueur trouvé.</p>
    <?php else: ?>
        <?php foreach ($joueurs as $joueur): ?>
            <div class="player-card">
                <h3><?= htmlspecialchars($joueur['nom']) ?> <?= htmlspecialchars($joueur['prenom']) ?></h3>
                <p><strong>Numéro de Licence :</strong> <?= htmlspecialchars($joueur['numero_licence_joueur']) ?></p>
                <p><strong>Date de Naissance :</strong> <?= date('d/m/Y', strtotime($joueur['date_naissance'])) ?></p>
                <p><strong>Taille :</strong> <?= htmlspecialchars($joueur['taille']) ?> m</p>
                <p><strong>Poids :</strong> <?= htmlspecialchars($joueur['poids']) ?> kg</p>
                <p><strong>Statut :</strong> <?= htmlspecialchars($joueur['statut']) ?></p>
                <div class="player-actions">
                    <a href="note.php?id=<?= $joueur['id_joueur'] ?>" class="btn btn-info">Note</a>
                    <a href="edit.php?id=<?= $joueur['id_joueur'] ?>" class="btn btn-primary">Modifier</a>
                    <?php
                    $participe = $controller->hasParticipation($joueur['id_joueur']);
                    if ($participe): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <input type="hidden" name="method" value="delete">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce joueur ?')">Supprimer</button>
                    </form>
                    <?php endif;?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

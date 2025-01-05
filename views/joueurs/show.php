<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Initialisation du contrôleur
$controller = new JoueurController($pdo);

// Vérifier si une note a été soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['texte'])) {
    $id_joueur = $_GET['id']; // Assurez-vous que l'id du joueur est passé via l'URL
    $texte = $_POST['texte'];
    $controller->ajouterNote($id_joueur, $texte); // Ajouter la note
    exit;
}

// Récupérer les informations du joueur et ses notes
$joueurData = $controller->showCommentaires($_GET['id']);
?>

<h1>Détails du Joueur: <?= htmlspecialchars($joueurData['joueur']['nom']) ?> <?= htmlspecialchars($joueurData['joueur']['prenom']) ?></h1>

<h2>Notes Personnelles</h2>
<?php if (!empty($joueurData['notes'])): ?>
    <ul>
        <?php foreach ($joueurData['notes'] as $note): ?>
            <li><strong><?= htmlspecialchars($note['date_heure']) ?></strong>: <?= nl2br(htmlspecialchars($note['texte'])) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune note pour ce joueur.</p>
<?php endif; ?>

<h3>Ajouter une note</h3>
<form method="POST">
    <textarea name="texte" rows="4" cols="50" required></textarea><br>
    <button type="submit">Ajouter la note</button>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

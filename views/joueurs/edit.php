<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

$controller = new JoueurController($pdo);

// Récupère l'ID du joueur à modifier
$id_joueur = $_GET['id'] ?? null;
if ($id_joueur) {
    // Récupère les données du joueur pour l'édition
    $joueur = $controller->show($id_joueur);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appeler la méthode pour mettre à jour les données du joueur
    $controller->update();
    exit; // Stopper l'exécution après redirection
}
?>

<h1>Modifier un joueur</h1>

<?php if ($joueur): ?>
    <form method="POST">
        <input type="hidden" name="id_joueur" value="<?= $id_joueur ?>">

        <label for="numero_licence_joueur">Numéro de Licence :</label>
        <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" value="<?= htmlspecialchars($joueur['numero_licence_joueur']) ?>" required><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" required><br>

        <label for="date_naissance">Date de Naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= $joueur['date_naissance'] ?>" required><br>

        <label for="taille">Taille :</label>
        <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($joueur['taille']) ?>" step="0.01" required><br>

        <label for="poids">Poids :</label>
        <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($joueur['poids']) ?>" step="0.01" required><br>

        <label for="statut">Statut :</label>
        <select id="statut" name="statut">
            <option value="Actif" <?= $joueur['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
            <option value="Inactif" <?= $joueur['statut'] === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
        </select><br>

        <button type="submit">Mettre à jour</button>
    </form>
<?php else: ?>
    <p>Le joueur n'existe pas.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

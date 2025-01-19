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

<style>
    /* Style global */
    .titre{
        font-size: 2rem;
        text-align: center;
        margin-bottom: 40px; /* Augmenter l'espace sous le titre */
        color: #007bff;
    }

    /* Structure du formulaire */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        margin-bottom: 20px;
    }

    .form-row div {
        flex: 0 0 40%; /* Les champs prennent 40% de la largeur */
        max-width: 350px; /* Réduire la largeur maximale des champs */
    }

    .form-row label {
        font-size: 1rem;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .form-row input,
    .form-row select {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        margin-bottom: 20px; /* Augmenter l'espace sous chaque champ */
    }

    /* Champ de texte de plus de largeur */
    .form-row input[type="text"] {
        font-size: 1.1rem;
    }

    /* Bouton Mettre à jour */
    .btn-update {
        background-color: #007bff;
        color: white;
        padding: 10px 20px; /* Réduction de la taille du bouton */
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
        width: auto;
        margin-top: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-update:hover {
        background-color: #0056b3;
    }

    /* Amélioration du message d'erreur */
    .error-message {
        color: red;
        text-align: center;
        margin-top: 20px;
    }
</style>

<h1 class="titre">Modifier un joueur</h1>

<?php if ($joueur): ?>
    <form method="POST">
        <input type="hidden" name="id_joueur" value="<?= $id_joueur ?>">

        <div class="form-row">
            <div>
                <label for="numero_licence_joueur">Numéro de Licence :</label>
                <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" value="<?= htmlspecialchars($joueur['numero_licence_joueur']) ?>" required>
            </div>

            <div>
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" required>
            </div>

            <div>
                <label for="date_naissance">Date de Naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?= $joueur['date_naissance'] ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="taille">Taille (en cm) :</label>
                <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($joueur['taille']) ?>" step="0.01" required>
            </div>

            <div>
                <label for="poids">Poids (en kg) :</label>
                <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($joueur['poids']) ?>" step="0.01" required>
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="statut">Statut :</label>
                <select id="statut" name="statut">
                    <option value="Actif" <?= $joueur['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="Blessé" <?= $joueur['statut'] === 'Blessé' ? 'selected' : '' ?>>Blessé</option>
                    <option value="Suspendu" <?= $joueur['statut'] === 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
                    <option value="Absent" <?= $joueur['statut'] === 'Absent' ? 'selected' : '' ?>>Absent</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn-update">
            Mettre à jour
        </button>
    </form>
<?php else: ?>
    <p class="error-message">Le joueur n'existe pas.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

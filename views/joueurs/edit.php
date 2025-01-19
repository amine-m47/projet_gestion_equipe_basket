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

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des champs
    $numero_licence_joueur = trim($_POST['numero_licence_joueur'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';
    $taille = $_POST['taille'] ?? '';
    $poids = $_POST['poids'] ?? '';
    $statut = $_POST['statut'] ?? '';


    // Validation du numéro de licence (non vide et longueur minimale)
    if (empty($numero_licence_joueur) || strlen($numero_licence_joueur) < 5) {
        $errors[] = "Le numéro de licence doit contenir au moins 5 caractères.";
    }

    // Validation du nom
    if (empty($nom) || strlen($nom) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    }

    // Validation du prénom
    if (empty($prenom) || strlen($prenom) < 2) {
        $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    }

    // Validation de la date de naissance (doit être une date valide et antérieure à aujourd'hui)
    if (empty($date_naissance) || strtotime($date_naissance) >= time()) {
        $errors[] = "La date de naissance doit être une date valide et antérieure à aujourd'hui.";
    }

    // Validation de la taille (positive et réaliste)
    if (empty($taille) || $taille <= 0 || $taille > 3) {
        $errors[] = "La taille doit être un nombre positif et réaliste (en mètres).";
    }

    // Validation du poids (positif et réaliste)
    if (empty($poids) || $poids <= 0 || $poids > 200) {
        $errors[] = "Le poids doit être un nombre positif et réaliste (en kilogrammes).";
    }

    // Validation du statut
    $statuts_valides = ['Actif', 'Blessé', 'Suspendu', 'Absent'];
    if (!in_array($statut, $statuts_valides)) {
        $errors[] = "Le statut sélectionné est invalide.";
    }

    if (empty($errors)) {
        // Appeler la méthode pour mettre à jour les données du joueur
        $controller->update();
        exit; // Stopper l'exécution après redirection
    }
}
?>

<h1>Modifier un joueur</h1>

<?php if ($joueur): ?>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li style="color: red;"><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id_joueur" value="<?= $id_joueur ?>">
        <div>
        <label for="numero_licence_joueur">Numéro de Licence :</label>
        <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" value="<?= htmlspecialchars($joueur['numero_licence_joueur']) ?>" required>
        </div>
        <div>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" required>
        </div>

        <div>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" required>
        </div>

        <div>
        <label for="date_naissance">Date de Naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= $joueur['date_naissance'] ?>" required>
        </div>

        <div>
        <label for="taille">Taille (en mètres) :</label>
        <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($joueur['taille']) ?>" step="0.01" required>
        </div>

        <div>
        <label for="poids">Poids (en kg) :</label>
        <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($joueur['poids']) ?>" step="0.01" required>
        </div>

        <div>
        <label for="statut">Statut :</label>
        <select id="statut" name="statut">
            <option value="Actif" <?= $joueur['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
            <option value="Blessé" <?= $joueur['statut'] === 'Blessé' ? 'selected' : '' ?>>Blessé</option>
            <option value="Suspendu" <?= $joueur['statut'] === 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
            <option value="Absent" <?= $joueur['statut'] === 'Absent' ? 'selected' : '' ?>>Absent</option>
        </select>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
<?php else: ?>
    <p style="color: red;">Le joueur n'existe pas.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

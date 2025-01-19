<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

$controller = new JoueurController($pdo);

// Initialiser un tableau pour les erreurs
$errors = [];

// Si le formulaire est soumis, valider les données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_licence_joueur = $_POST['numero_licence_joueur'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $statut = $_POST['statut'];

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

    // Si aucune erreur, appeler la méthode store()
    if (empty($errors)) {
        $controller->store();
        exit; // Stopper l'exécution après redirection
    }
}
?>

<div class="container">
    <h1>Ajouter un joueur</h1>

    <!-- Affichage des erreurs -->
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="numero_licence_joueur">Numéro de Licence :</label>
            <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" value="<?php echo htmlspecialchars($_POST['numero_licence_joueur'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="date_naissance">Date de Naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($_POST['date_naissance'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="taille">Taille (en mètres) :</label>
            <input type="number" id="taille" name="taille" step="0.01" value="<?php echo htmlspecialchars($_POST['taille'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="poids">Poids (en kg):</label>
            <input type="number" id="poids" name="poids" step="0.01" value="<?php echo htmlspecialchars($_POST['poids'] ?? ''); ?>" required>
        </div>

        <div>
            <label for="statut">Statut :</label>
            <select id="statut" name="statut">
                <option value="Actif">Actif</option>
                <option value="Blessé">Blessé</option>
                <option value="Suspendu">Suspendu</option>
                <option value="Absent">Absent</option>
            </select>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

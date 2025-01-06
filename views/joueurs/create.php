<?php require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

$controller = new JoueurController($pdo);

// Si le formulaire est soumis, appeler la méthode store()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
    exit; // Stopper l'exécution après redirection
}
?>

<h1>Ajouter un joueur</h1>

<form method="POST">
    <label for="numero_licence_joueur">Numéro de Licence :</label>
    <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" required><br>

    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="date_naissance">Date de Naissance :</label>
    <input type="date" id="date_naissance" name="date_naissance" required><br>

    <label for="taille">Taille :</label>
    <input type="number" id="taille" name="taille" step="0.01" required><br>

    <label for="poids">Poids :</label>
    <input type="number" id="poids" name="poids" step="0.01" required><br>

    <label for="statut">Statut :</label>
    <select id="statut" name="statut">
        <option value="Actif">Actif</option>
        <option value="Blessé">Blessé</option>
        <option value="Suspendu">Suspendu</option>
        <option value="Absent">Absent</option>
    </select><br>

    <button type="submit">Ajouter</button>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

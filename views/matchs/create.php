<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/MatchController.php';

// Initialisation du contrôleur
$controller = new MatchController($pdo);

// Si le formulaire est soumis, appeler la méthode store()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
    exit; // Stopper l'exécution après redirection
}
?>

<h1>Ajouter un match</h1>

<form method="POST">
    <label for="date_heure">Date et Heure :</label>
    <input type="datetime-local" id="date_heure" name="date_heure" required><br>

    <label for="lieu_rencontre">Lieu de Rencontre :</label>
    <input type="text" id="lieu_rencontre" name="lieu_rencontre"><br>

    <label for="domicile">Domicile :</label>
    <input type="checkbox" id="domicile" name="domicile"><br>

    <label for="resultat">Résultat (victoire ou défaite) :</label>
    <input type="radio" id="victoire" name="resultat" value="1" required> Victoire
    <input type="radio" id="defaite" name="resultat" value="0" required> Défaite<br>

    <label for="equipe_adverse">Équipe Adverse :</label>
    <input type="text" id="equipe_adverse" name="equipe_adverse" required><br>

    <button type="submit">Ajouter</button>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

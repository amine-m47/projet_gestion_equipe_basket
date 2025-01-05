<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/MatchController.php';
require_once __DIR__ . '/../../lib/match_lib.php';

// Initialisation du contrôleur
$controller = new MatchController($pdo);

// Vérifier si l'ID du match est fourni dans l'URL
if (isset($_GET['id'])) {
    $id_match = $_GET['id'];

    // Récupérer les informations du match à modifier
    $match = $controller->show($id_match);

    // Si le formulaire est soumis, appeler la méthode update()
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->update();
        exit; // Stopper l'exécution après redirection
    }
} else {
    // Si l'ID n'est pas présent, rediriger ou afficher une erreur
    echo "Match introuvable.";
    exit;
}
?>

<h1>Modifier le match</h1>

<form method="POST">
    <input type="hidden" name="id_match" value="<?= $id_match ?>">

    <label for="date_heure">Date et Heure :</label>
    <input type="datetime-local" id="date_heure" name="date_heure" value="<?= $match['date_heure'] ?>" required><br>

    <label for="lieu_rencontre">Lieu de Rencontre :</label>
    <input type="text" id="lieu_rencontre" name="lieu_rencontre" value="<?= $match['lieu_rencontre'] ?>"><br>

    <label for="domicile">Domicile :</label>
    <input type="checkbox" id="domicile" name="domicile" <?= $match['domicile'] ? 'checked' : '' ?>><br>

    <label for="equipe_adverse">Équipe Adverse :</label>
    <input type="text" id="equipe_adverse" name="equipe_adverse" value="<?= $match['equipe_adverse'] ?>" required><br>

    <button type="submit">Mettre à jour</button>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

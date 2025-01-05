<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/MatchController.php';

$controller = new MatchController($pdo);

if (isset($_GET['id'])) {
    $id_match = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $resultat = $_POST['resultat'];

        // Appel au controller pour mettre à jour le résultat
        $success = $controller->updateResult($id_match, $resultat);

        if ($success) {
            // Redirection après mise à jour réussie
            header("Location: index.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du résultat.";
        }
    }
} else {
    echo "Aucun match sélectionné.";
    exit();
}
?>

<h1>Entrer le résultat du match</h1>

<form method="POST">
    <label for="resultat">Résultat :</label>
    <input type="radio" id="victoire" name="resultat" value="1" required> Victoire
    <input type="radio" id="defaite" name="resultat" value="0" required> Défaite
    <input type="radio" id="nul" name="resultat" value="2" required> Match Nul
    <br>
    <button type="submit">Enregistrer</button>
</form>

<?php require_once __DIR__ . '/../layout/header.php';?>
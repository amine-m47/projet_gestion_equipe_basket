<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ParticiperController.php';

// Initialisation du contrôleur
$controller = new ParticiperController($pdo);

// ID du match (à récupérer dynamiquement)
$id_match = $_GET['id'];

// Récupération des joueurs et de la feuille de match
$data = $controller->show($id_match);
$joueursDisponibles = $data['joueursDisponibles'];
$feuilleMatch = $data['feuilleMatch'];

// Traitement des formulaires (ajout/modification/suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ajouter') {
            $controller->addJoueur($id_match, $_POST['id_joueur'], $_POST['titulaire'], $_POST['poste']);
        } elseif ($_POST['action'] === 'modifier') {
            $controller->modifyJoueur($id_match, $_POST['id_joueur'], $_POST['titulaire'], $_POST['poste']);
        } elseif ($_POST['action'] === 'supprimer') {
            $controller->removeJoueur($id_match, $_POST['id_joueur']);
        }
    }
    header("Location: feuille_match.php?id=$id_match");
    exit();
}
?>

<h1>Feuille de Match - Match #<?= htmlspecialchars($id_match) ?></h1>

<form method="post">
    <h2>Ajouter un joueur</h2>
    <label for="id_joueur">Sélectionner un joueur</label>
    <select name="id_joueur" required>
        <?php foreach ($joueursDisponibles as $joueur) : ?>
            <option value="<?= $joueur['id_joueur'] ?>"><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></option>
        <?php endforeach; ?>
    </select>
    <label for="titulaire">Titulaire :</label>
    <input type="checkbox" name="titulaire" value="1">
    <label for="poste">Poste :</label>
    <input type="text" name="poste" required>
    <button type="submit" name="action" value="ajouter">Ajouter</button>
</form>

<h2>Feuille de match actuelle</h2>
<table>
    <thead>
    <tr>
        <th>Nom</th>
        <th>Titulaire</th>
        <th>Poste</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($feuilleMatch as $participation) :
        var_dump($participation); ?> // Cette ligne te permettra de voir les données.

        <tr>
            <td><?= htmlspecialchars($participation['prenom'] . ' ' . $participation['nom']) ?></td>
            <td><?= $participation['titulaire'] ? 'Oui' : 'Non' ?></td>
            <td><?= htmlspecialchars($participation['poste']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id_joueur" value="<?= $participation['id_joueur'] ?>">
                    <button type="submit" name="action" value="modifier">Modifier</button>
                    <button type="submit" name="action" value="supprimer">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

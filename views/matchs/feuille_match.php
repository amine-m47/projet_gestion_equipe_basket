<?php
require_once __DIR__ . '/../../controllers/ParticiperController.php';
require_once __DIR__ . '/../../controllers/MatchController.php';
require_once __DIR__ . '/../layout/header.php';

// Initialisation du contrôleur
$controller = new ParticiperController($pdo);
$matchcontroller = new MatchController($pdo);

if (!isset($_GET['id_match'])) {
    echo "Aucun match sélectionné.";
    exit();
}

$id_match = $_GET['id_match'];
if (!$matchcontroller->show($id_match)) {
    echo "Le match avec l'ID spécifié n'existe pas.";
    header("Location: index.php");
}

// Ajouter un joueur à un poste (validation individuelle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poste'], $_POST['id_joueur']) && isset($_POST['method']) && $_POST['method'] === 'ajouterTitulaire' ) {
    $poste = $_POST['poste'];
    $id_joueur = $_POST['id_joueur'];

    // Ajouter le joueur et recharger la page
    $controller->addJoueur($id_match, $id_joueur, $poste, $titulaire = true);
    header("Location: feuille_match.php?id_match=" . $id_match);
    exit();
}

// Ajouter un joueur remplaçant (validation individuelle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poste'], $_POST['id_joueur']) && isset($_POST['method']) && $_POST['method'] === 'ajouterRemplacant') {
    $poste = $_POST['poste'];
    $id_joueur = $_POST['id_joueur'];

    // Ajouter le joueur remplaçant et recharger la page
    $controller->addJoueur($id_match, $id_joueur, $poste, $titulaire = false);
    header("Location: feuille_match.php?id_match=" . $id_match);
    exit();
}

// Supprimer un joueur d’un poste
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'supprimer') {
    $id_joueur = $_POST['id_joueur'];
    // Supprimer la participation et recharger la page
    $controller->removeJoueur($id_match, $id_joueur);
    header("Location: feuille_match.php?id_match=" . $id_match);
    exit();
}

// Supprimer tous les joueurs du match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'supprimer_tous') {
    // Supprimer tous les joueurs du match et recharger la page
    $controller->removeAllJoueurs($id_match);
    header("Location: feuille_match.php?id_match=" . $id_match);
    exit();
}

// Récupérer les données nécessaires pour la page
$data = $controller->show($id_match);
$joueursDisponibles = $data['joueursDisponibles'];
$feuilleMatch = $data['feuilleMatch'];

// Regrouper les joueurs sélectionnés par poste
$joueursParPoste = [];
foreach ($feuilleMatch as $participation) {
    $joueursParPoste[$participation['poste']] = $participation;
}

// Vérifier si un poste titulaire est manquant
$postesTitulairesManquants = [];
foreach ($postes as $numPoste => $nomPoste) {
    if (!isset($joueursParPoste[$numPoste])) {
        $postesTitulairesManquants[] = $nomPoste;
    }
}

?>

<h1>Feuille de match</h1>
<p>Match ID : <?= htmlspecialchars($id_match) ?></p>

<h2>Postes et sélection des joueurs (validation individuelle)</h2>

<?php
// Si un poste titulaire manque, afficher un message d'avertissement
if (!empty($postesTitulairesManquants)): ?>
    <div style="color: red; font-weight: bold;">
        <p>Attention : Les postes suivants n'ont pas de joueur titulaire sélectionné :</p>
        <ul>
            <?php foreach ($postesTitulairesManquants as $posteManquant): ?>
                <li><?= htmlspecialchars($posteManquant) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Bouton pour retirer tous les joueurs -->
<form method="POST">
    <input type="hidden" name="method" value="supprimer_tous">
    <button type="submit">Retirer tous les joueurs</button>
</form>

<?php
// Liste des postes titulaires
$postes = [
    1 => "Meneur (Point Guard)",
    2 => "Arrière (Shooting Guard)",
    3 => "Ailier (Small Forward)",
    4 => "Ailier fort (Power Forward)",
    5 => "Pivot (Center)"
];

foreach ($postes as $numPoste => $nomPoste): ?>
    <form method="POST">
        <table>
            <thead>
            <tr>
                <th>Poste</th>
                <th>Joueur sélectionné</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= htmlspecialchars($nomPoste) ?></td>
                <td>
                    <?php if (isset($joueursParPoste[$numPoste])): ?>
                        <?= htmlspecialchars($joueursParPoste[$numPoste]['prenom'] . ' ' . $joueursParPoste[$numPoste]['nom']) ?>
                    <?php else: ?>
                        <select name="id_joueur" required>
                            <option value="">-- Sélectionnez un joueur --</option>
                            <?php foreach ($joueursDisponibles as $joueur): ?>
                                <option value="<?= $joueur['id_joueur'] ?>">
                                    <?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!isset($joueursParPoste[$numPoste])): ?>
                        <button type="submit" name="poste" value="<?= $numPoste ?>">Valider Titulaire</button>
                        <input type="hidden" name="method" value="ajouterTitulaire">
                    <?php else: ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_joueur" value="<?= $joueursParPoste[$numPoste]['id_joueur'] ?>">
                            <input type="hidden" name="method" value="supprimer">
                            <button type="submit" name="bouton">Retirer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php endforeach; ?>

<h2>Postes et sélection des joueurs remplaçants (optionnel)</h2>

<?php
// Liste des postes remplaçants
foreach ($postes as $numPoste => $nomPoste): ?>
    <form method="POST">
        <table>
            <thead>
            <tr>
                <th>Poste</th>
                <th>Joueur sélectionné</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= htmlspecialchars($nomPoste) ?></td>
                <td>
                    <?php if (isset($joueursParPoste[$numPoste]) && $joueursParPoste[$numPoste]['titulaire'] === 0): ?>
                        <?= htmlspecialchars($joueursParPoste[$numPoste]['prenom'] . ' ' . $joueursParPoste[$numPoste]['nom']) ?>
                    <?php else: ?>
                        <select name="id_joueur">
                            <option value="">-- Sélectionnez un joueur --</option>
                            <?php foreach ($joueursDisponibles as $joueur): ?>
                                <option value="<?= $joueur['id_joueur'] ?>">
                                    <?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!isset($joueursParPoste[$numPoste]) || $joueursParPoste[$numPoste]['titulaire'] === 1): ?>
                        <input type="hidden" name="method" value="ajouterRemplacant">
                        <button type="submit" name="poste" value="<?= $numPoste ?>">Valider Remplaçant</button>
                    <?php else: ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_joueur" value="<?= $joueursParPoste[$numPoste]['id_joueur'] ?>">
                            <input type="hidden" name="method" value="supprimer">
                            <button type="submit" name="bouton">Retirer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

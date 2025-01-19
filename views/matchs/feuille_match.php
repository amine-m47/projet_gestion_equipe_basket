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

$match = $matchcontroller->show($id_match);

// Vérifier si la donnée de la date existe
if (isset($match['date_heure'])) {
    $matchEstPasse = strtotime($match['date_heure']) < time();
} else {
    echo "La date du match n'est pas disponible.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'ajouterNote') {
    $id_joueur = $_POST['id_joueur'];
    $note = $_POST['note'];

    // Appel de la méthode pour ajouter la note
    $controller->addNote($id_joueur, $id_match, $note);

    // Redirection pour recharger la page avec la note ajoutée
    header("Location: feuille_match.php?id_match=" . $id_match);
    exit();
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

// Liste des postes (titulaires et remplaçants)
$postes = [
    1 => "Meneur",
    2 => "Arrière",
    3 => "Ailier",
    4 => "Ailier fort",
    5 => "Pivot"
];
$postesRemplacants = [
    6 => "Meneur",
    7 => "Arrière",
    8 => "Ailier",
    9 => "Ailier fort",
    10 => "Pivot"
];

// Vérifier si un poste titulaire est manquant
$postesTitulairesManquants = [];
foreach ($postes as $numPoste => $nomPoste) {
    if (!isset($joueursParPoste[$numPoste])) {
        $postesTitulairesManquants[] = $nomPoste;
    }
}

?>
<style>
    /* Style général */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fb;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #007bff;
        font-size: 36px;
        margin-top: 5px;
    }

    h2 {
        color: #333;
        font-size: 24px;
        text-align: center;
        margin-top: 5px;
    }

    .match-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 5px;
    }

    .player-card {
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
        margin-bottom: 30px;
    }

    .player-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .player-card h3 {
        margin: 0;
        font-size: 1.5em;
        color: #333;
    }

    .player-card p {
        margin: 10px 0;
        color: #555;
    }

    .player-actions {
        margin-top: 15px;
    }

    .player-actions .btn {
        margin-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .btn {
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-warning {
        background-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-info {
        background-color: #28a745;
    }

    .btn-info:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .add-player-btn a {
        padding: 12px 30px;
        background-color: #28a745;
        color: white;
        font-size: 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .add-player-btn a:hover {
        background-color: #218838;
    }

    .form-container {
        margin-top: 5px;
        text-align: center;
    }

    .form-container button {
        padding: 12px 25px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .form-container button:hover {
        background-color: #0056b3;
    }
</style>

<h1>Feuille de Match</h1>

<h2>Postes et sélection des Joueurs</h2>

<?php if (!empty($postesTitulairesManquants)): ?>
    <div class="warning">
        <p>Attention : Les postes suivants n'ont pas de joueur titulaire sélectionné :</p>
        <ul>
            <?php foreach ($postesTitulairesManquants as $posteManquant): ?>
                <li><?= htmlspecialchars($posteManquant) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!$matchEstPasse): ?>
    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="method" value="supprimer_tous">
            <button type="submit">Retirer tous les joueurs</button>
        </form>
    </div>
<?php endif; ?>

<div class="match-list">
    <?php foreach ($postes as $numPoste => $nomPoste): ?>
        <div class="player-card">
            <h3><?= htmlspecialchars($nomPoste) ?></h3>
            <p>
                <?php if (isset($joueursParPoste[$numPoste])): ?>
                    <?= htmlspecialchars($joueursParPoste[$numPoste]['prenom'] . ' ' . $joueursParPoste[$numPoste]['nom']) ?>
                <?php else: ?>
                    <?php if (!$matchEstPasse): ?>
                        <select name="id_joueur" required>
                            <option value="">-- Sélectionnez un joueur --</option>
                            <?php foreach ($joueursDisponibles as $joueur): ?>
                                <option value="<?= $joueur['id_joueur'] ?>">
                                    <?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif;?>
                <?php endif; ?>
            </p>
            <div class="form-container">
                <?php if (!$matchEstPasse): ?>
                    <?php if (!isset($joueursParPoste[$numPoste])): ?>
                        <form method="POST">
                            <input type="hidden" name="method" value="ajouterTitulaire">
                            <input type="hidden" name="poste" value="<?= $numPoste ?>">
                            <button type="submit" class="btn btn-warning">Valider Titulaire</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_joueur" value="<?= $joueursParPoste[$numPoste]['id_joueur'] ?>">
                            <input type="hidden" name="method" value="supprimer">
                            <button type="submit" class="btn btn-danger">Retirer</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (isset($joueursParPoste[$numPoste])): ?>
                    <?php if ($matchEstPasse): ?>
                        <?php
                        $noteExistante = isset($joueursParPoste[$numPoste]['note_joueur']) ? $joueursParPoste[$numPoste]['note_joueur'] : null;
                        if ($noteExistante): ?>
                            <p>Note : <?= htmlspecialchars($noteExistante) ?> / 5</p>
                        <?php else: ?>
                            <form method="POST">
                                <label for="note">Note :</label>
                                <input type="number" name="note" min="1" max="5" required>
                                <input type="hidden" name="id_joueur" value="<?= $joueursParPoste[$numPoste]['id_joueur'] ?>">
                                <input type="hidden" name="method" value="ajouterNote">
                                <button type="submit" class="btn btn-warning">Ajouter la note</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<h2>Postes et sélection des joueurs remplaçants (optionnel)</h2>

<?php
// Liste des postes remplaçants
foreach ($postesRemplacants as $numPoste => $nomPoste): ?>
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
                        <?php if (!$matchEstPasse): ?>
                            <select name="id_joueur">
                                <option value="">-- Sélectionnez un joueur --</option>
                                <?php foreach ($joueursDisponibles as $joueur): ?>
                                    <option value="<?= $joueur['id_joueur'] ?>">
                                        <?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif;?>
                    <?php endif;?>
                </td>
                <td>
                <?php if (!$matchEstPasse): ?>

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
                <?php endif;?>

                    <?php if (isset($joueursParPoste[$numPoste])): ?>
                        <!-- Si un joueur est présent dans ce poste, vérifier si la note existe -->
                        <?php if ($matchEstPasse): ?>
                            <?php
                            // Vérifier si le joueur a déjà une note
                            $noteExistante = isset($joueursParPoste[$numPoste]['note_joueur']) ? $joueursParPoste[$numPoste]['note_joueur'] : null;
                            if ($noteExistante): ?>
                                <p>Note : <?= htmlspecialchars($noteExistante) ?> / 5</p>
                            <?php else: ?>
                                <!-- Si la note n'existe pas, afficher un champ pour ajouter la note -->
                                <form method="POST">
                                    <label for="note">Note :</label>
                                    <input type="number" name="note" min="1" max="5" required>
                                    <input type="hidden" name="id_joueur" value="<?= $joueursParPoste[$numPoste]['id_joueur'] ?>">
                                    <input type="hidden" name="method" value="ajouterNote">
                                    <button type="submit">Ajouter la note</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

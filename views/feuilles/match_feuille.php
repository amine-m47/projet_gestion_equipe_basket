<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/MatchController.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';
require_once __DIR__ . '/../../controllers/ParticiperController.php';

// Initialisation des contrôleurs
$matchController = new MatchController($pdo);
$joueurController = new JoueurController($pdo);
$participerController = new ParticiperController($pdo);

// Récupération du match
if (isset($_GET['id'])) {
    $id_match = $_GET['id'];
    $match = $matchController->show($id_match);

    if (!$match) {
        echo "Match non trouvé.";
        exit;
    }

    // Vérification si le match est déjà joué, on bloque la modification si c'est le cas
    if ($match['resultat'] !== null) {
        echo "Impossible de modifier la feuille de match, le match a déjà eu lieu.";
        exit;
    }
} else {
    echo "ID du match non fourni.";
    exit;
}

// Récupérer les joueurs actifs
$joueurs = $joueurController->getJoueursActifs(); // Fonction qui récupère les joueurs actifs

// Gestion du formulaire pour ajouter ou modifier des joueurs à la feuille de match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulaireIds = isset($_POST['titulaire']) ? $_POST['titulaire'] : [];
    $remplacantIds = isset($_POST['remplacant']) ? $_POST['remplacant'] : [];
    $postesTitulaire = isset($_POST['poste_titulaire']) ? $_POST['poste_titulaire'] : [];
    $postesRemplacant = isset($_POST['poste_remplacant']) ? $_POST['poste_remplacant'] : [];

    // Vérifier si le nombre de joueurs correspond à celui requis pour ce sport
    $nombreJoueurs = count($titulaireIds) + count($remplacantIds);
    $joueursRequis = 10; // Exemple, à ajuster selon les règles du basket
    if ($nombreJoueurs !== $joueursRequis) {
        echo "Le nombre de joueurs sélectionnés ne correspond pas au nombre requis.";
        exit;
    }

    // Ajouter les joueurs titulaires
    foreach ($titulaireIds as $index => $id_joueur) {
        $poste = isset($postesTitulaire[$index]) ? $postesTitulaire[$index] : '';
        $participerController->ajouterParticipation($id_joueur, $id_match, 'Titulaire', $poste);
    }

    // Ajouter les joueurs remplaçants
    foreach ($remplacantIds as $index => $id_joueur) {
        $poste = isset($postesRemplacant[$index]) ? $postesRemplacant[$index] : '';
        $participerController->ajouterParticipation($id_joueur, $id_match, 'Remplaçant', $poste);
    }

    echo "Feuille de match mise à jour.";
}

?>

<h1>Feuille de match pour <?= htmlspecialchars($match['equipe_adverse']) ?> - <?= htmlspecialchars($match['lieu_rencontre']) ?> </h1>
<p><strong>Date :</strong> <?= htmlspecialchars($match['date_heure']) ?></p>

<!-- Formulaire de sélection des joueurs -->
<form method="POST">
    <h3>Titulaires</h3>
    <div>
        <?php foreach ($joueurs as $joueur): ?>
            <?php if ($joueur['statut'] === 'actif'): ?>
                <label>
                    <input type="checkbox" name="titulaire[]" value="<?= $joueur['id_joueur'] ?>" <?php // Marquer comme coché si déjà sélectionné ?>>
                    <?= htmlspecialchars($joueur['prenom']) ?> <?= htmlspecialchars($joueur['nom']) ?>
                </label>
                <!-- Choix de poste pour les basketteurs -->
                <select name="poste_titulaire[]">
                    <option value="">Choisir un poste</option>
                    <option value="Meneur">Meneur</option>
                    <option value="Arrière">Arrière</option>
                    <option value="Ailier">Ailier</option>
                    <option value="Ailier fort">Ailier fort</option>
                    <option value="Pivot">Pivot</option>
                </select><br>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h3>Remplaçants</h3>
    <div>
        <?php foreach ($joueurs as $joueur): ?>
            <?php if ($joueur['statut'] === 'actif'): ?>
                <label>
                    <input type="checkbox" name="remplacant[]" value="<?= $joueur['id_joueur'] ?>" <?php // Marquer comme coché si déjà sélectionné ?>>
                    <?= htmlspecialchars($joueur['prenom']) ?> <?= htmlspecialchars($joueur['nom']) ?>
                </label>
                <!-- Choix de poste pour les basketteurs -->
                <select name="poste_remplacant[]">
                    <option value="">Choisir un poste</option>
                    <option value="Meneur">Meneur</option>
                    <option value="Arrière">Arrière</option>
                    <option value="Ailier">Ailier</option>
                    <option value="Ailier fort">Ailier fort</option>
                    <option value="Pivot">Pivot</option>
                </select><br>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <button type="submit">Mettre à jour la feuille de match</button>
</form>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

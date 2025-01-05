<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';
require_once __DIR__ . '/../../controllers/NoteController.php'; // Inclure le contrôleur pour les notes

// Initialisation des contrôleurs
$joueurController = new JoueurController($pdo);
$noteController = new NoteController($pdo);

// Récupérer l'id du joueur à partir de l'URL
$id_joueur = $_GET['id'] ?? null;

if (!$id_joueur) {
    header("Location: index.php");
    exit;
}

// Récupérer les informations du joueur
$joueurData = $joueurController->show($id_joueur);

// Si le formulaire de modification du joueur est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) {
    // Modifier les informations du joueur
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero_licence_joueur = $_POST['numero_licence_joueur'];
    $date_naissance = $_POST['date_naissance'];
    $taille = $_POST['taille'];
    $poids = $_POST['poids'];
    $statut = $_POST['statut'];
    $joueurController->update();
    header("Location: choix.php?id=$id_joueur");
    exit;
}

// Si le formulaire de suppression du joueur est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_joueur'])) {
    $joueurController->delete();
    header("Location: index.php"); // Rediriger après suppression
    exit;
}

// Si le formulaire d'ajout de note est soumis
if (isset($_POST['texte'])) {
    $texte = $_POST['texte'];
    $noteController->ajouterNote($id_joueur, $texte); // Ajouter la note
    header("Location: choix.php?id=$id_joueur");
    exit;
}

// Si le formulaire de suppression de note est soumis
if (isset($_POST['supprimer_note'])) {
    $id_note = $_POST['id_note_supprimee'];
    $noteController->supprimerNote($id_note); // Supprimer la note
    header("Location: choix.php?id=$id_joueur");
    exit;
}

// Récupérer les notes du joueur
$notes = $noteController->getNotes($id_joueur);
?>

<form action="index.php" method="get">
    <button type="submit">←  Retour à la liste des joueurs</button>
</form>

<h1>Modifier un joueur - <?= htmlspecialchars($joueurData['nom']) ?> <?= htmlspecialchars($joueurData['prenom']) ?></h1>

<?php if ($joueurData): ?>
    <!-- Formulaire pour modifier les détails du joueur -->
    <form method="POST">
        <input type="hidden" name="id_joueur" value="<?= $id_joueur ?>">

        <label for="numero_licence_joueur">Numéro de Licence :</label>
        <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" value="<?= htmlspecialchars($joueurData['numero_licence_joueur']) ?>" required><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueurData['nom']) ?>" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueurData['prenom']) ?>" required><br>

        <label for="date_naissance">Date de Naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= $joueurData['date_naissance'] ?>" required><br>

        <label for="taille">Taille :</label>
        <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($joueurData['taille']) ?>" step="0.01" required><br>

        <label for="poids">Poids :</label>
        <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($joueurData['poids']) ?>" step="0.01" required><br>

        <label for="statut">Statut :</label>
        <select id="statut" name="statut">
            <option value="Actif" <?= $joueurData['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
            <option value="Inactif" <?= $joueurData['statut'] === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
        </select><br>

        <button type="submit">Mettre à jour</button>
    </form>
<?php else: ?>
    <p>Le joueur n'existe pas.</p>
<?php endif; ?>

<!-- Formulaire pour supprimer le joueur -->
<form method="POST">
    <input type="hidden" name="id_joueur" value="<?= $id_joueur ?>">
    <button type="submit" name="supprimer_joueur" onclick="return confirm('Voulez-vous vraiment supprimer ce joueur ?')">Supprimer</button>
</form>

<h2>Notes </h2>
<?php if (!empty($notes)): ?>
    <ul>
        <?php foreach ($notes as $note): ?>
            <li>
                <strong><?= htmlspecialchars($note['date_heure']) ?></strong>:
                <?= nl2br(htmlspecialchars($note['texte'])) ?>

                <!-- Formulaire pour supprimer la note -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_note_supprimee" value="<?= $note['id_note'] ?>">
                    <button type="submit" name="supprimer_note" onclick="return confirm('Voulez-vous vraiment supprimer cette note ?')">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune note pour ce joueur.</p>
<?php endif; ?>

<!-- Formulaire pour ajouter une note -->
<h3>Ajouter une note</h3>
<form method="POST">
    <textarea name="texte" rows="4" cols="50" required></textarea><br>
    <button type="submit">Ajouter la note</button>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

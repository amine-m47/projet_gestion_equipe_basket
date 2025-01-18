<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';
require_once __DIR__ . '/../../controllers/NoteController.php';

// Initialisation des contrôleurs
$joueurController = new JoueurController($pdo);
$noteController = new NoteController($pdo);

// Récupérer l'id du joueur à partir de l'URL
$id_joueur = $_GET['id'] ?? null;

if (!$id_joueur){
    header("Location: index.php");
    exit;
}

// Si le formulaire d'ajout de note est soumis
if (isset($_POST['texte'])) {
    $texte = $_POST['texte'];
    // Limiter la longueur de la note à 150 caractères
    $texte = substr($texte, 0, 150);
    $noteController->ajouterNote($id_joueur, $texte); // Ajouter la note
    header("Location: note.php?id=$id_joueur");
    exit;
}

// Si le formulaire de suppression de note est soumis
if (isset($_POST['supprimer_note'])) {
    $id_note = $_POST['id_note_supprimee'];
    $noteController->supprimerNote($id_note); // Supprimer la note
    header("Location: note.php?id=$id_joueur");
    exit;
}

// Récupérer les informations du joueur
$joueurData = $joueurController->show($id_joueur);

// Récupérer les notes du joueur
$notes = $noteController->getNotes($id_joueur);
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 2rem;
        text-align: center;
        color: #ffcc00; /* Jaune sportif */
        margin-bottom: 30px;
    }

    h3 {
        color: #ffcc00; /* Jaune sportif */
        font-size: 1.6rem;
        margin-top: 20px;
    }

    button {
        background-color: #ffcc00; /* Jaune sportif */
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #e6b800;
    }

    .note-card {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .note-card p {
        font-size: 1rem;
        color: #333;
    }

    .note-card button {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: transparent; /* Pas de fond pour la croix */
        font-size: 18px;
        color: #e74c3c; /* Rouge pour la suppression */
        border: none;
        cursor: pointer;
    }

    .note-card button:hover {
        color: #c0392b;
    }

    .textarea-note {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        resize: none;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <!-- Bouton retour à la liste -->
    <div style="display: flex; justify-content: flex-start; margin-bottom: 20px;">
        <form action="index.php" method="get">
            <button type="submit">
                ← Retour à la liste des joueurs
            </button>
        </form>
    </div>

    <!-- Titre avec nom et prénom du joueur -->
    <h1>
        Notes de <?= htmlspecialchars($joueurData['nom']) ?> <?= htmlspecialchars($joueurData['prenom']) ?>
    </h1>

    <!-- Affichage des notes -->
    <div style="margin-top: 30px;">
        <?php if (!empty($notes)): ?>
            <?php foreach ($notes as $note): ?>
                <div class="note-card">
                    <!-- Affichage de la date -->
                    <p>
                        <strong><?= date('d/m/Y H:i', strtotime($note['date_heure'])) ?> :</strong>
                        <span>
                            <?= nl2br(htmlspecialchars(substr($note['texte'], 0, 150))) ?>
                            <?php if (strlen($note['texte']) > 150): ?>
                                ...
                            <?php endif; ?>
                        </span>
                    </p>

                    <!-- Bouton de suppression -->
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="id_note_supprimee" value="<?= $note['id_note'] ?>">
                        <input type="hidden" name="supprimer_note" value="1">
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                            ✖
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #555;">Aucune note pour ce joueur.</p>
        <?php endif; ?>
    </div>

    <!-- Formulaire pour ajouter une note -->
    <h3>Ajouter une note</h3>
    <form method="POST">
        <textarea name="texte" rows="4" class="textarea-note" maxlength="150" placeholder="Écrire une note..." required></textarea><br>
        <button type="submit">
            Ajouter la note
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

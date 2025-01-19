<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

$controller = new JoueurController($pdo);

// Si le formulaire est soumis, appeler la méthode store()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
    exit; // Stopper l'exécution après redirection
}
?>

<style>
    main {
        background: none !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
</style>

<div class="container" style="max-width: 600px; margin: 50px auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
    <h1 style="font-size: 1.8rem; color: #006400; text-align: center; margin-bottom: 20px;">Ajouter un joueur</h1>

    <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; flex-direction: column;">
            <label for="numero_licence_joueur" style="font-size: 1rem; color: #333;">Numéro de Licence :</label>
            <input type="text" id="numero_licence_joueur" name="numero_licence_joueur" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="nom" style="font-size: 1rem; color: #333;">Nom :</label>
            <input type="text" id="nom" name="nom" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="prenom" style="font-size: 1rem; color: #333;">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="date_naissance" style="font-size: 1rem; color: #333;">Date de Naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="taille" style="font-size: 1rem; color: #333;">Taille :</label>
            <input type="number" id="taille" name="taille" step="0.01" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="poids" style="font-size: 1rem; color: #333;">Poids :</label>
            <input type="number" id="poids" name="poids" step="0.01" required style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="statut" style="font-size: 1rem; color: #333;">Statut :</label>
            <select id="statut" name="statut" style="padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px;">
                <option value="Actif">Actif</option>
                <option value="Blessé">Blessé</option>
                <option value="Suspendu">Suspendu</option>
                <option value="Absent">Absent</option>
            </select>
        </div>

        <button type="submit" style="background-color: #006400; color: white; padding: 12px 20px; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s;">
            Ajouter
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

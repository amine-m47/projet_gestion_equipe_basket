<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>Ajouter un joueur</h1>
<form method="POST" action="/controllers/index.php?method=store">
    <input type="text" name="numero_licence_joueur" placeholder="Numéro de Licence" required>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="date" name="date_naissance" required>
    <input type="number" name="taille" placeholder="Taille (cm)" step="0.01" required>
    <input type="number" name="poids" placeholder="Poids (kg)" step="0.01" required>
    <select name="statut">
        <option value="Actif">Actif</option>
        <option value="Inactif">Inactif</option>
    </select>
    <button type="submit">Ajouter</button>
</form>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
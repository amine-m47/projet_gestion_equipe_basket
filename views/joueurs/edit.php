<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>Modifier un joueur</h1>
<form method="POST" action="../../controllers/JoueurController.php?action=update">
    <input type="hidden" name="id_joueur" value="<?= htmlspecialchars($joueur['id_joueur']) ?>">
    <input type="text" name="numero_licence_joueur" value="<?= htmlspecialchars($joueur['numero_licence_joueur']) ?>" required>
    <input type="text" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" required>
    <input type="text" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" required>
    <input type="date" name="date_naissance" value="<?= htmlspecialchars($joueur['date_naissance']) ?>" required>
    <input type="number" name="taille" step="0.01" value="<?= htmlspecialchars($joueur['taille']) ?>" required>
    <input type="number" name="poids" step="0.01" value="<?= htmlspecialchars($joueur['poids']) ?>" required>
    <select name="statut">
        <option value="Actif" <?= $joueur['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
        <option value="Inactif" <?= $joueur['statut'] === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
    </select>
    <button type="submit">Modifier</button>
</form>

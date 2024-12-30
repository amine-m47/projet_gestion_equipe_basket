<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>Ajouter un match</h1>
<form method="POST" action="../../controllers/MatchController.php">
    <input type="datetime-local" name="date_heure" required>
    <input type="text" name="lieu_rencontre" placeholder="Lieu" required>
    <select name="domicile_ou_exterieur">
        <option value="Domicile">Domicile</option>
        <option value="Exterieur">Extérieur</option>
    </select>
    <input type="text" name="resultat" placeholder="Résultat" required>
    <select name="equipe_adverse">
        <option value="1">Oui</option>
        <option value="0">Non</option>
    </select>
    <button type="submit">Ajouter</button>
</form>

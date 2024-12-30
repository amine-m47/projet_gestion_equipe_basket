<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>Modifier un match</h1>
<form method="POST" action="../../controllers/MatchController.php?action=update">
    <input type="hidden" name="id_match" value="<?= htmlspecialchars($match['id_match']) ?>">
    <input type="datetime-local" name="date_heure" value="<?= htmlspecialchars($match['date_heure']) ?>" required>
    <input type="text" name="lieu_rencontre" value="<?= htmlspecialchars($match['lieu_rencontre']) ?>" required>
    <select name="domicile_ou_exterieur">
        <option value="Domicile" <?= $match['domicile_ou_exterieur'] === 'Domicile' ? 'selected' : '' ?>>Domicile</option>
        <option value="Exterieur" <?= $match['domicile_ou_exterieur'] === 'Exterieur' ? 'selected' : '' ?>>Extérieur</option>
    </select>
    <input type="text" name="resultat" value="<?= htmlspecialchars($match['resultat']) ?>" required>
    <select name="equipe_adverse">
        <option value="1" <?= $match['equipe_adverse'] ? 'selected' : '' ?>>Oui</option>
        <option value="0" <?= !$match['equipe_adverse'] ? 'selected' : '' ?>>Non</option>
    </select>
    <button type="submit">Modifier</button>
</form>

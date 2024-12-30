<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Redirection vers la page des joueurs
header('Location: ../views/joueurs/index.php');
exit;

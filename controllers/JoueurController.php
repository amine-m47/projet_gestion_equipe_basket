<?php
require_once __DIR__ . '/../models/Joueur.php';

class JoueurController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $joueurModel = new Joueur($this->pdo);
        $joueurs = $joueurModel->getAllJoueurs();
        require __DIR__ . '/../views/joueurs/index.php';
    }
}

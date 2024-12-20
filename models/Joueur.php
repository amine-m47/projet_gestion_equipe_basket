<?php
class Joueur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllJoueurs() {
        $stmt = $this->pdo->query('SELECT * FROM Joueur');
        return $stmt->fetchAll();
    }
}

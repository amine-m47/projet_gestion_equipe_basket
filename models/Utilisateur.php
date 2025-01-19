<?php
class Utilisateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM Utilisateur WHERE mail = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}

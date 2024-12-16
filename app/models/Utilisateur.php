<?php

class Utilisateur{
	private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=mvc_example', 'root', '');
    }

    public function authenticate($username, $password) {
        $query = $this->db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $query->bindParam(':username', $username);
        $query->bindParam(':password', md5($password)); // Exemple de hachage de mot de passe
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

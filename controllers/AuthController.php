<?php
class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare('SELECT * FROM Utilisateur WHERE mail = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

            // Redirection après connexion réussie
            header('Location: ../views/joueurs/index.php');
            exit;
        } else {
            // Connexion échouée
            $_SESSION['error'] = 'Identifiants incorrects.';
            header('Location: ../public/login.php');
            exit;
        }
    }
}

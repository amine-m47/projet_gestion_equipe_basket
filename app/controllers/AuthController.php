<?php

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new Utilisateur();
    }

    public function login($username, $password) {
        $user = $this->userModel->authenticate($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /dashboard');
        } else {
            header('Location: /login?error=invalid_credentials');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
    }
}

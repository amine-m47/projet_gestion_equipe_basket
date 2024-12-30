<?php
require_once __DIR__ . '/../lib/match_lib.php';

class MatchController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $matchs = getAllMatchs($this->pdo);
        require __DIR__ . '/../views/matchs/index.php';
    }

    public function create($data) {
        createMatch($this->pdo, $data);
        header('Location: index.php');
    }

    public function edit($id, $data) {
        updateMatch($this->pdo, $data);
        header('Location: index.php');
    }
}

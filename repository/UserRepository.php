<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Model/Utilisateur.php';

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    private function rowToUser(array $row): Utilisateur
    {
        return new Utilisateur(
            $row['id'],
            $row['nom'],
            $row['email'],
            $row['mot_de_passe'],
            $row['role'],
            (bool) $row['actif']
        );
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM UTILISATEUR WHERE email = ? AND actif = 1 LIMIT 1'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        return $row ? $this->rowToUser($row) : null;
    }

    public function findById(string $id): ?Utilisateur
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM UTILISATEUR WHERE id = ? AND actif = 1 LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? $this->rowToUser($row) : null;
    }
}

<?php
require_once ROOT_PATH . '/database/connection.php';

class User
{
    private PDO $db;      

    public function __construct()
    {
        $this->db = getConnection();
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getUserById($userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function addUser($name, $surname, $username, $email): array
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, surname, username, email)
            VALUES (:name, :surname, :username, :email)
        ");

        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'email' => $email
        ]);
        
        $newId = $this->db->lastInsertId();
        
        return [
            'id' => $newId,
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'email' => $email
        ];
    }

    public function updateUser($userId, $name, $surname, $username, $email): ?array
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET name = :name;
                surname = :surname,
                email = :email
            WHERE id = :id 
            ");

        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'email' => $email,
            'id' => $userId
        ]);

        if ($stmt->rowCount() > 0) {
            return $this->getUserById($userId);
        }

        return null;
    }

    public function deleteUser($userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => "userId"]);
        return $stmt->rowCount() > 0;
    }

    public function findUserByCredentials($name, $surname, $username, $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE name = :name 
            AND surname = :surname 
            AND username = :username 
            AND email = :email
            ");

        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'email' => $email
        ]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function deleteAllUsers(): bool
    {
        $stmt = $this->db->query("DELETE FROM users");
        return true;
    }
}
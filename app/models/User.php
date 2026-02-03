<?php
require_once __DIR__ . '/UserStorage.php';

class User
{
    private $storage;    
    private $data;       

    public function __construct()
    {
        $this->storage = new UserStorage();
        $this->data = $this->storage->getData();
    }

    public function getAllUsers(): array
    {
        return $this->data['users'] ?? [];
    }

    public function getUserById($userId): ?array
    {
        foreach ($this->data['users'] as $user) {
            if ($user['id'] == $userId) {
                return $user;
            }
        }
        return null;
    }

    public function addUser($name, $surname, $username, $email): array
    {
        $newUser = [
            'id' => time(),                          
            'name' => $name,                         
            'surname' => $surname,
            'username' => $username,
            'email' => $email,                       
            'created_at' => date('Y-m-d H:i:s')      
        ];

        $this->data['users'][] = $newUser;
        $this->storage->setData($this->data);

        return $newUser;
    }

    public function updateUser($userId, $name, $surname, $username, $email): ?array
    {
        foreach ($this->data['users'] as $index => $user) {
            if ($user['id'] == $userId) {
                $this->data['users'][$index]['name'] = $name;
                $this->data['users'][$index]['surname'] = $surname;
                $this->data['users'][$index]['username'] = $username;
                $this->data['users'][$index]['email'] = $email;
                $this->data['users'][$index]['updated_at'] = date('Y-m-d H:i:s');
                
                $this->storage->setData($this->data);
                return $this->data['users'][$index];
            }
        }
        return null;
    }

    public function deleteUser($userId): bool
    {
        foreach ($this->data['users'] as $index => $user) {
            if ($user['id'] == $userId) {
                array_splice($this->data['users'], $index, 1); // Elimina y re indexa
                
                $this->storage->setData($this->data);

                return true;
            }
        }
        return false;
    }

    public function findUserByCredentials($name, $surname, $username, $email): ?array
    {
        foreach ($this->data['users'] as $user) {
            if (
                $user['name'] == $name &&
                $user['surname'] == $surname &&
                $user['username'] == $username &&
                $user['email'] == $email
            ) {
                return $user;
            }
        }
        return null;
    }

    public function deleteAllUsers(): bool
    {
        $this->data['users'] = [];
        $this->storage->setData($this->data);
        return true;
    }
}
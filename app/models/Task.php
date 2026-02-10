<?php

require_once ROOT_PATH . '/database/connection.php';

class Task {

    private PDO $db;

    public function __construct() {

        $this->db = getConnection();
    }

    public function getAllTasks($userId): array {

        $stmt = $this->db->prepare("
        SELECT 
            id,
            user_id,
            title,
            description,
            status,
            creation_date AS created_at,
            end_date AS due_date
        FROM tasks
        WHERE user_id = :user_id
        ORDER BY creation_date DESC");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function addTask($userId, $title, ?string $description, ?string $createdAt, ?string $dueDate): array {

        $newTask = [
            'id' => time(),
            'userId' => $userId,
            'title' => $title,
            'description' => $description,
            'created_at' => $createdAt,
            'due_date' => $dueDate,
            'status' => 'pending'
        ];

        $this->data['tasks'][] = $newTask;
        $this->storage->setData($this->data);
        
        return $newTask;
    }

    public function updateTask($userId, $taskId, $newStatus): array | null {

        foreach($this->data['tasks'] as $index => $task) {
            if($task['userId'] == $userId && $task['id'] == $taskId) {
                $this->data['tasks'][$index]['status'] = $newStatus;
                $this->storage->setData($this->data);
                return $task;
            }
        }
        return null;
    }

    public function deleteTask($userId, $taskId): bool {

        foreach ($this->data['tasks'] as $index => $task) {
            if ($task['userId'] == $userId && $task['id'] == $taskId) {
                array_splice($this->data['tasks'], $index, 1);
                $this->storage->setData($this->data);
                return true;
            }
        }
        return false;
    }

    public function deleteAllTasksByUserId($userId): bool {
    // Filtrar las tareas: mantener solo las que NO pertenecen al usuario
        $this->data['tasks'] = array_filter($this->data['tasks'], function($task) use ($userId) {
        return $task['userId'] != $userId;
        });
    
    // Re-indexar el array para mantener índices consecutivos
        $this->data['tasks'] = array_values($this->data['tasks']);
    
    // Guardar los cambios
        $this->storage->setData($this->data);
    
        return true;
    }

    public function getTaskById($userId, $taskId) {

        foreach ($this->data['tasks'] as $task) {
            if ($task['userId'] == $userId && $task['id'] == $taskId) {
                return $task;
        }
    }
        return null;
    }

    public function updateTaskContent($userId, $taskId, $title, $description, $dueDate) {
    
        foreach ($this->data['tasks'] as $index => $task) {
            if ($task['userId'] == $userId && $task['id'] == $taskId) {
                $this->data['tasks'][$index]['title'] = $title;
                $this->data['tasks'][$index]['description'] = $description;
                $this->data['tasks'][$index]['due_date'] = $dueDate;
                $this->storage->setData($this->data);
                return true;
        }
    }
    
    return false;
}

}
?>
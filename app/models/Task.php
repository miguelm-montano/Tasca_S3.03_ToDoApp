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

        $stmt = $this->db->prepare("
            INSERT INTO tasks (user_id, title, description, creation_date, end_date, status)
            VALUES (:user_id, :title, :description, :creation_date, :end_date, 'pending')
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'creation_date' => $createdAt,
            'end_date' => $dueDate
        ]);
        
        // Obtener el ID auto-generado
        $newId = $this->db->lastInsertId();
        
        // Devolver la tarea creada
        return [
            'id' => $newId,
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'created_at' => $createdAt,
            'due_date' => $dueDate,
            'status' => 'pending'
        ];
    }

    public function updateTask($userId, $taskId, $newStatus): array | null {

        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET status = :status 
            WHERE user_id = :user_id AND id = :task_id
        ");
        
        $stmt->execute([
            'status' => $newStatus,
            'user_id' => $userId,
            'task_id' => $taskId
        ]);
        
        // Si se actualizó alguna fila, devolver la tarea
        if ($stmt->rowCount() > 0) {
            return $this->getTaskById($userId, $taskId);
        }
        
        return null;
    }

    public function deleteTask($userId, $taskId): bool {

        $stmt = $this->db->prepare("
            DELETE FROM tasks 
            WHERE user_id = :user_id AND id = :task_id
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'task_id' => $taskId
        ]);
        
        return $stmt->rowCount() > 0;
    }

    public function deleteAllTasksByUserId($userId): bool {

        $stmt = $this->db->prepare("
            DELETE FROM tasks 
            WHERE user_id = :user_id
        ");
        
        $stmt->execute(['user_id' => $userId]);
        
        return true;
    }

    public function getTaskById($userId, $taskId) {

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
            WHERE user_id = :user_id AND id = :task_id
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'task_id' => $taskId
        ]);
        
        return $stmt->fetch();
    }

    public function updateTaskContent($userId, $taskId, $title, $description, $dueDate) {
    
        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET title = :title, 
                description = :description, 
                end_date = :due_date
            WHERE user_id = :user_id AND id = :task_id
        ");
        
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'user_id' => $userId,
            'task_id' => $taskId
        ]);
        
        return $stmt->rowCount() > 0;
}

}
?>
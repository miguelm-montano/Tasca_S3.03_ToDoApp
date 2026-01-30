<?php

require_once __DIR__ . '/TaskStorage.php';

class Task {

    private $storage;
    private $data;

    public function __construct() {

        $this->storage = new TaskStorage();
        $this->data = $this->storage->getData();
    }

    public function getAllTasks($userId): array {

        $tasks = [];
        foreach($this->data['tasks'] as $task) {
            if($task['userId'] == $userId) {
                $tasks[] = $task;
            }
        }

        return $tasks;
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


}
?>
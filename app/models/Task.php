<?php

require_once __DIR__ . '/JsonStorage.php';

class Task {

    private $storage;
    private $data;

    public function __construct() {

        $this->storage = new JsonStorage();
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

    public function addTask($userId, $title, ?string $description, string $createdAt, ?string $dueDate): array {

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

    public function getTaskStats($userId) {

        $tasks = $this->getAllTasks($userId);
    
        $stats = [
            'total' => count($tasks),
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0
        ];
    
        foreach ($tasks as $task) {
            switch ($task['status']) {
                case 'pending':
                    $stats['pending']++;
                    break;
                case 'in_progress':
                    $stats['in_progress']++;
                    break;
                case 'completed':
                    $stats['completed']++;
                    break;
            }
        }
    
        return $stats;
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

        $this->data['tasks'] = array_filter($this->data['tasks'], function($task) use ($userId) {
        return $task['userId'] != $userId;
        });
    
        $this->data['tasks'] = array_values($this->data['tasks']);
    
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

    public function updateTaskContent($userId, $taskId, $title, $description, $dueDate): bool {
    
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
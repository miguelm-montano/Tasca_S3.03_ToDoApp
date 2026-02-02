<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController extends ApplicationController {

    public function indexAction() {

        $userId = 1; //Temporal para probar

        $taskModel = new Task();
        $this->view->tasks = $taskModel->getAllTasks($userId);

    }

    public function newAction() {
        //Para renderizar la vista
    }

    public function addTaskAction() {

        $userId = 1; //Prueba luego sera la sesión
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $createdAt = $_POST['created_at'] ?? null;
        $dueDate = $_POST['due_date'] ?? null;

        if(!empty($title)) {
            $taskModel = new Task();
            $taskModel->addTask($userId, $title, $description ?: null, $createdAt, $dueDate);
        }

        header('Location: /task');
        exit;
    }

    public function deleteTaskAction() {

        $userId = 1; //Temporal
        $taskId = $_POST['task_id'] ?? null;

        if($taskId) {
            $taskModel = new Task();
            $taskModel->deleteTask($userId, $taskId);
        }

        header('Location: /task');
        exit;
    }
    
    public function updateTaskAction() {

        $userId = 1; //Temporal
        $taskId = $_POST['task_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($taskId && $status) {
        $taskModel = new Task();
        $taskModel->updateTask($userId, $taskId, $status);
    }

        header('Location: /task');
        exit;
    }

    public function editTaskAction() {

        $userId = 1; // temporal, luego sesión
        $taskId = $_GET['id'] ?? null;

        if (!$taskId) {
            header('Location: /task');
            exit;
        }

        $taskModel = new Task();
        $task = $taskModel->getTaskById($userId, $taskId);

        if (!$task) {
            header('Location: /task');
            exit;
        }

        $this->view->task = $task;
    }

    public function updateTaskContentAction() {

        $userId = 1;
        $taskId = $_POST['task_id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dueDate = $_POST['due_date'] ?? null;

    if ($taskId && $title) {

            $taskModel = new Task();
            $taskModel->updateTaskContent(
            $userId,
            $taskId,
            $title,
            $description ?: null,
            $dueDate);
        }

        header('Location: /task');
        exit;
}


}
?>
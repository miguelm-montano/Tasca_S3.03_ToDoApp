<?php

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

    }
    
    public function updateTask() {

    }

    
}
?>
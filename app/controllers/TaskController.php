<?php

class TaskController extends ApplicationController {

    public function indexAction() {

        $userId = 1; //Temporal para probar

        $taskModel = new Task();
        $tasks = $taskModel->getAllTasks($userId);

        $this->view->tasks = $tasks;
    }

    public function addTaskAction() {

        $userId = 1; //Prueba luego sera la sesión
        $title = $_POST['title'] ?? '';

        if(!empty($title)) {
            $taskModel = new Task();
            $taskModel->addTask($userId, $title);
        }

        header('Location: /task');
        exit;
    }

    public function updateTask() {

    }

    public function deleteTask() {
        
    }
}
?>
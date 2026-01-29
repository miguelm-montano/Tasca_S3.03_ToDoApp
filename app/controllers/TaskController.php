<?php

class TaskController extends ApplicationController {

    public function indexAction() {
        
        $userId = 1; //Temporal

        $taskModel = new Task();
        $tasks = $taskModel->getAllTasks($userId);

        $this->view->tasks = $tasks;
    }

    public function addTask() {

    }

    public function updateTask() {

    }

    public function deleteTask() {
        
    }
}
?>
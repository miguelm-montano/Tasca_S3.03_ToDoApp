<?php

class TaskController extends ApplicationController {

    public function indexAction() {

        $userId = 1; //Temporal para probar

        $taskModel = new Task();
        $this->view->tasks = $taskModel->getAllTasks($userId);

    }

    public function addTaskAction() {

    }

    public function deleteTaskAction() {

  
    }
    
    public function updateTask() {

    }

    
}
?>
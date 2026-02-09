<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/helpers/SessionHelper.php';

class TaskController extends ApplicationController {

    private $sessionHelper;

    public function __construct() {

        $this->sessionHelper = new SessionHelper();
    }

    public function indexAction() {

        $this->sessionHelper->requireLogin();

        $userId = $this->sessionHelper->getCurrentUserId();

        $taskModel = new Task();
        $this->view->tasks = $taskModel->getAllTasks($userId);

        $this->view->currentUser=$this->sessionHelper->getCurrentUser();
    }

    public function newAction() {
        $this->sessionHelper->requireLogin();
        //Para render de la vista

        $userId = $this->sessionHelper->getCurrentUserId();
    
    // Verificar si estamos editando
    $taskId = $_GET['id'] ?? null;
    
    if ($taskId) {
        // Modo edición
        $taskModel = new Task();
        $task = $taskModel->getTaskById($userId, $taskId);
        
        if ($task) {
            $this->view->task = $task;
        } else {
            header('Location:' . WEB_ROOT . '/task');
            exit;
            }
        }
    }

    public function addTaskAction() {

        $this->sessionHelper->requireLogin();
        $userId = $this->sessionHelper->getCurrentUserId();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $createdAt = $_POST['created_at'] ?? null;
        $dueDate = $_POST['due_date'] ?? null;

        if(!empty($title)) {
            $taskModel = new Task();
            $taskModel->addTask($userId, $title, $description ?: null, $createdAt, $dueDate);
        }

        header('Location:' . WEB_ROOT . '/task');
        exit;
    }

    public function deleteTaskAction() {

        $this->sessionHelper->requireLogin();
        $userId = $this->sessionHelper->getCurrentUserId();
        
        $taskId = $_POST['task_id'] ?? null;

        if($taskId) {
            $taskModel = new Task();
            $taskModel->deleteTask($userId, $taskId);
        }

        header('Location:' . WEB_ROOT . '/task');
        exit;
    }
    
    public function updateTaskAction() {

        $this->sessionHelper->requireLogin();
        $userId = $this->sessionHelper->getCurrentUserId();

        $taskId = $_POST['task_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($taskId && $status) {
        $taskModel = new Task();
        $taskModel->updateTask($userId, $taskId, $status);
    }

        header('Location:' . WEB_ROOT . '/task');
        exit;
    }

    public function editTaskAction() {

        $this->sessionHelper->requireLogin();
        $userId = $this->sessionHelper->getCurrentUserId();

        $taskId = $_GET['id'] ?? null;

        if (!$taskId) {
            header('Location:' . WEB_ROOT . '/task');
            exit;
        }

        $taskModel = new Task();
        $task = $taskModel->getTaskById($userId, $taskId);

        if (!$task) {
            header('Location:' . WEB_ROOT . '/task');
            exit;
        }

        $this->view->task = $task;
    }

    public function updateTaskContentAction() {

        $this->sessionHelper->requireLogin();
        $userId = $this->sessionHelper->getCurrentUserId();

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

        header('Location:' . WEB_ROOT . '/task');
        exit;
}


}
?>
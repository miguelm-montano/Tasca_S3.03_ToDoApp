<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/helpers/SessionHelper.php';

class UserController extends ApplicationController 
{

    private $sessionHelper;

    public function __construct() 
    {
        $this->sessionHelper = new SessionHelper();
    }

    public function indexAction() 
    {
        $this->sessionHelper->startSession();

        $userModel = new User();
        $this->view->users = $userModel->getAllUsers();

        if ($this->sessionHelper->isLoggedIn()) {
            $this->view->currentUser = $this->sessionHelper->getCurrentUser();
            $this->view->canEdit = true;
        } else {
            $this->view->currentUser = null;
            $this->view->canEdit = true;
        }
    }

     public function loginAsAction()
    {
    $this->sessionHelper->startSession();
    
    $userId = $_GET['id'] ?? 0;
    
    if ($userId > 0) {
        $userModel = new User();
        $user = $userModel->getUserById($userId);
        
        if ($user) {
            $this->sessionHelper->setUser($user);
            header('Location: ' . WEB_ROOT . '/task');
            exit;
        }
    }
    
    // Si no encuentra el usuario, vuelve a la lista de usuarios
    header('Location: ' . WEB_ROOT . '/users');
    exit;
    }

    public function deleteAction()
    {
        $this->sessionHelper->startSession();
        $userId = $_GET['id'] ?? 0;
    
        if (!$userId) {
        header('Location: ' . WEB_ROOT . '/users');
        exit;
        }
    
        $currentUser = $_SESSION['user'] ?? null;
        $isSelfDelete = $currentUser && ($userId == $currentUser['id']);
    
    // 1. Eliminar todas las tareas del usuario
        require_once __DIR__ . '/../models/Task.php';
        $taskModel = new Task();
        $taskModel->deleteAllTasksByUserId($userId);

    // 2. Eliminar el usuario
        $userModel = new User();
        $userModel->deleteUser($userId);
    
    // 3. Si el usuario eliminado es el actual, cerrar sesión
        if ($isSelfDelete) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user']);
        header('Location: ' . WEB_ROOT . '/auth/login');
        } else {
        header('Location: ' . WEB_ROOT . '/users');
        }
        exit;
    }

    public function editProfileAction()
{
    $this->sessionHelper->requireLogin();
    $currentUser = $this->sessionHelper->getCurrentUser();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $currentUser['id'];
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        
        $userModel = new User();
        $updatedUser = $userModel->updateUser($id, $name, $surname, $username, $email);
        
        // Actualizar la sesión con los nuevos datos
        $this->sessionHelper->setUser($updatedUser);
        
        header('Location: ' . WEB_ROOT . '/task');
        exit;
    }
    
    $this->view->user = $currentUser;
}

}

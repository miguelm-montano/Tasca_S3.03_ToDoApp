<?php
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/helpers/SessionHelper.php';

class UserController extends ApplicationController 
{
    private $sessionHelper;
    private $userModel;
    private $taskModel;

    public function __construct() 
    {
        $this->sessionHelper = new SessionHelper();
        $this->userModel = new User();
        $this->taskModel = new Task();
        $this->sessionHelper->startSession();
    }

    public function indexAction() 
    {
        $this->view->users = $this->userModel->getAllUsers();

        if ($this->sessionHelper->isLoggedIn()) {
            $this->view->currentUser = $this->sessionHelper->getCurrentUser();
        } else {
            $this->view->currentUser = null;
        }
        
        $this->view->canEdit = true;
    }

    public function loginAsAction()
    {
        $userId = $this->getIdFromRequest();
    
        if ($userId > 0) {
            $user = $this->userModel->getUserById($userId);
        
            if ($user) {
                $this->sessionHelper->setUser($user);
                $this->redirectTo('/task');
            }
        }

        $this->redirectTo('/users');
    }

    public function deleteAction()
    {
        $userId = $this->getIdFromRequest();
    
        if (!$userId) {
            $this->redirectTo('/users');
        }
    
        $currentUser = $this->sessionHelper->getCurrentUser();
        $isSelfDelete = $currentUser && ($userId == $currentUser['id']);
    
        $this->taskModel->deleteAllTasksByUserId($userId);
        $this->userModel->deleteUser($userId);
    
        if ($isSelfDelete) {
            unset($_SESSION['logged_in']);
            unset($_SESSION['user']);
            $this->redirectTo('/auth/login');
        } else {
            $this->redirectTo('/users');
        }
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
        
            $updatedUser = $this->userModel->updateUser($id, $name, $surname, $username, $email);
            $this->sessionHelper->setUser($updatedUser);
        
            $this->redirectTo('/task');
        }

        $this->view->user = $currentUser;
    }

    // ===============
    // PRIVATE METHODS
    // ===============
    
    private function getIdFromRequest()
    {
        return $_GET['id'] ?? 0;
    }

    private function redirectTo($path)
    {
        header('Location: ' . WEB_ROOT . $path);
        exit;
    }
}
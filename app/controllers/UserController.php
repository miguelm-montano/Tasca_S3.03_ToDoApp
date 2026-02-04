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

    public function indexAction() {

        $this->sessionHelper->startSession();

        $userModel = new User();
        $this->view->users = $userModel->getAllUsers();

        if ($this->sessionHelper->isLoggedIn()) {
            $this->view->currentUser = $this->sessionHelper->getCurrentUser();
            $this->view->canEdit = true;
        } else {
            $this->view->currentUser = null;
            $this->view->canEdit = false;
        }
    }

    public function addAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $userModel = new User();

            $userModel->addUser($name, $surname, $username, $email);

            header('Location: ' . WEB_ROOT . '/dashboard');
            exit;
        }
    }

    public function deleteAction()
    {
        $this->sessionHelper->startSession();

        $userId = $_GET['id'] ?? 0;
        $currentUser = $_SESSION['user'] ?? null;
        $isSelfDelete = $currentUser && ($userId == $currentUser['id']);

        $userModel = new User();
        $userModel->deleteUser($userId);

        if ($isSelfDelete) {
            $this->sessionHelper->destroySession();
            header('Location: ' . WEB_ROOT . '/');
        } else {
            header('Location: ' . WEB_ROOT . '/dashboard');
        }
        exit;
    }

    public function editAction()
    {
        $userId = $_GET['id'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $userModel = new User();

            $userModel->updateUser($id, $name, $surname, $username, $email);

            header('Location: ' . WEB_ROOT . '/dashboard');
            exit;
        }
        $userModel = new User();
        $this->view->user = $userModel->getUserById($userId);
    }

    public function deleteAllAction()
    {
        $this->sessionHelper->startSession();

        $userModel = new User();
        $userModel->deleteAllUsers();

        $this->sessionHelper->destroySession();

        header('Location: ' . WEB_ROOT . '/');
        exit;
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
            }
            exit;
        }

        header('Location: ' . WEB_ROOT . '/users');
        exit;
    }
}

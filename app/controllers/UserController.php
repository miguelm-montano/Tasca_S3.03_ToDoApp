<?php

class UserController extends ApplicationController
{

    public function indexAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userModel = new User();
        $this->view->users = $userModel->getAllUsers();

        if (isset($_SESSION['logged_in'])) {
            $this->view->currentUser = $_SESSION['user'];
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_GET['id'] ?? 0;
        $currentUser = $_SESSION['user'] ?? null;
        $isSelfDelete = $currentUser && ($userId == $currentUser['id']);

        $userModel = new User();
        $userModel->deleteUser($userId);

        if ($isSelfDelete) {
            session_destroy();
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userModel = new User();
        $userModel->deleteAllUsers();

        session_destroy();

        header('Location: ' . WEB_ROOT . '/');
        exit;
    }
}

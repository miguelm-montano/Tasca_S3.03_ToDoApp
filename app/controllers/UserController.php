<?php

class UserController extends ApplicationController
{

    public function indexAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . WEB_ROOT . '/');
            exit;
        }

        $userModel = new User();
        $this->view->users = $userModel->getAllUsers();
        $this->view->currentUser = $_SESSION['user'];
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
 
        $userId = $_GET['id'] ?? 0;

        $userModel = new User();
        $userModel->deleteUser($userId);


        header('Location: ' . WEB_ROOT . '/dashboard');
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
}

<?php

class AuthController extends ApplicationController
{

    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';

            $userModel = new User();
            $user = $userModel->findUserByCredentials($name, $surname, $username, $email);

            if ($user) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $user;
            } else {
                $newUser = $userModel->addUser($name, $surname, $username, $email);
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $newUser;
            }

            header('Location: ' . WEB_ROOT . '/dashboard');
            exit;
        }
    }

    public function logoutAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        header('Location: ' . WEB_ROOT . '/');
        exit;
    }
}

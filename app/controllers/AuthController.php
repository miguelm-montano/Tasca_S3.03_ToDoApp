<?php

require_once __DIR__ . '/helpers/SessionHelper.php';

class AuthController extends ApplicationController {

    private $sessionHelper;

    public function __construct() {
    
        $this->sessionHelper = new SessionHelper();
    }

    public function loginAction() {

        /*if ($this->sessionHelper->isLoggedIn()) {
        header('Location: ' . WEB_ROOT . '/task');
        exit;
        }*/

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name && $surname && $username && $email) {

            $userModel = new User();

            $user = $userModel->findUserByCredentials(
                $name,
                $surname,
                $username,
                $email
            );

            if (!$user) {
                $user = $userModel->addUser(
                    $name,
                    $surname,
                    $username,
                    $email
                );
            }

            $this->sessionHelper->setUser($user);

            header('Location: ' . WEB_ROOT . '/task');
            exit;
        }
    }
    /*// formulario
    $this->view->render('auth/login.phtml');*/
}
    

    public function registerAction() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $surname = trim($_POST['surname'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');

        // Validar que todos los campos estén completos
        if (empty($name) || empty($surname) || empty($username) || empty($email)) {
            header('Location: ' . WEB_ROOT . '/?error=empty_fields');
            exit;
        }

        $userModel = new User();
        // Intentar encontrar el usuario
        $user = $userModel->findUserByCredentials($name, $surname, $username, $email);
        if ($user) {
        // Usuario ya existe - iniciar sesión con ese usuario
            $this->sessionHelper->setUser($user);
        } else {
        // Usuario NO existe - crear nuevo y iniciar sesión
            $newUser = $userModel->addUser($name, $surname, $username, $email);
            $this->sessionHelper->setUser($newUser);
        }
        // Redirigir a SUS tareas
        header('Location: ' . WEB_ROOT . '/task');
        exit;
    }
        // Si no es POST, redirigir al inicio
        header('Location: ' . WEB_ROOT . '/');
        exit;
    }

    public function logoutAction() {

        $this->sessionHelper->destroySession();
        header('Location: ' . WEB_ROOT . '/');
        exit;   
    }
}

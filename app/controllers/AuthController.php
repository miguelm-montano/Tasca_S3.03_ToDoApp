<?php
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends ApplicationController 
{
    private $sessionHelper;

    public function __construct() 
    {
        $this->sessionHelper = new SessionHelper();
    }

    public function loginAction() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->authenticate($_POST);
        }
    }
    
    private function authenticate($data) 
    {
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($surname) || empty($username) || empty($email)) {
            header('Location: ' . WEB_ROOT . '/?error=empty_fields');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findUserByCredentials($name, $surname, $username, $email);
    
        if (!$user) {
            $user = $userModel->addUser($name, $surname, $username, $email);
        }

        $this->sessionHelper->setUser($user);
        header('Location: ' . WEB_ROOT . '/task');
        exit;
    }

    public function logoutAction() 
    {
        $this->sessionHelper->destroySession();
        header('Location: ' . WEB_ROOT . '/');
        exit;   
    }
}
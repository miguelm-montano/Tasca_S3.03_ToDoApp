<?php

class SessionHelper {

    public function startSession() {

        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLoggedIn() {

        $this->startSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function getCurrentUserId() {

        $this->startSession();

        if($this->isLoggedIn() && isset($_SESSION['user']['id'])) {
            return (int) $_SESSION['user']['id'];
        }

        return null;
    }

    public function getCurrentUser() {

        $this->startSession();

        if($this->isLoggedIn() && isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    public function setUser($user) {

        $this->startSession();
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user;
    }

    public function destroySession() {
        
        $this->startSession();
        session_destroy();
    }

    public function requireLogin($redirectUrl = '/') {

        if($this->isLoggedIn()) {
            header('Location:' . WEB_ROOT . $redirectUrl);
            exit;
        }
    }
}
?>
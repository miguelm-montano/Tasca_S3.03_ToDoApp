<?php

class SessionHelper {

    public function startSession() {

        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLoggedIn() {

        $this->startSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user']);
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
        
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
    }

    public function requireLogin($redirectUrl = '/') {

        if(!$this->isLoggedIn()) {
            header('Location:' . WEB_ROOT . $redirectUrl);
            exit;
        }
    }
}
?>
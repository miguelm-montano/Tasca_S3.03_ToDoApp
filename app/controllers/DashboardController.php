<?php

class DashboardController extends ApplicationController
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

        $this->view->currentUser = $_SESSION['user'];
    }
}

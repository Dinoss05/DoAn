<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require_once "views/layouts/header.php";
        require_once "views/{$view}.php";
        require_once "views/layouts/footer.php";
    }

    protected function redirect($url) {
        header("Location: index.php?route=" . $url);
        exit;
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('login');
        }
    }

    protected function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== 'admin') {
            die("Truy cập bị từ chối! Chỉ dành cho Quản trị viên.");
        }
    }
}
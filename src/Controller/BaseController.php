<?php

namespace App\Controller;

abstract class BaseController {
    protected function render($view, $data = []) {
        extract($data);
        
        ob_start();
        $viewPath = "views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            return false;
        }
        include $viewPath;
        $content = ob_get_clean();
        
        ob_start();
        $layoutPath = "views/layout.php";
        
        if (!file_exists($layoutPath)) {
            return $content;
        }
        include $layoutPath;
        return ob_get_clean();
    }

    protected function json($data) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($data);
    }

    protected function redirect($path) {
        if (!headers_sent()) {
            header("Location: {$path}");
            exit;
        }
        echo '<script>window.location.href="' . htmlspecialchars($path) . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($path) . '"></noscript>';
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getPost($key = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    protected function getQuery($key = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    protected function validateCSRF() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            http_response_code(403);
            die('Invalid CSRF token');
        }
    }

    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
} 
<?php

namespace App;

class Router {
    private static $instance = null;
    private $routes = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function addRoute($method, $path, $controller, $action, $requiresAuth = false) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'requiresAuth' => $requiresAuth
        ];
    }

    public function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $pattern = $this->convertRouteToRegex($route['path']);
            
            if (preg_match($pattern, $uri, $matches) && $route['method'] === $method) {
                if ($route['requiresAuth'] && (!isset($_SESSION['user']) || !$_SESSION['user'])) {
                    header('Location: /login');
                    exit;
                }

                array_shift($matches);

                $controller = new $route['controller']();
                return call_user_func_array([$controller, $route['action']], $matches);
            }
        }

        http_response_code(404);
        return $this->render404();
    }

    private function convertRouteToRegex($route) {
        return "#^" . preg_replace('/:[a-zA-Z]+/', '([^/]+)', $route) . "$#";
    }

    private function render404() {
        ob_start();
        include __DIR__.'/../views/404.php';
        return ob_get_clean();
    }
} 

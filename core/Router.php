<?php
class Router {
    private $routes = [];
    
    public function add($route, $action) {
        $this->routes[$route] = $action;
    }
    
    public function dispatch() {
        $uri = $this->getUri();
        
        foreach ($this->routes as $route => $action) {
            $pattern = $this->convertRouteToRegex($route);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callAction($action, $matches);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        include 'views/errors/404.php';
    }
    
    private function getUri() {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        // Remove base path if exists
        $basePath = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
            $uri = trim($uri, '/');
        }
        
        return $uri;
    }
    
    private function convertRouteToRegex($route) {
        $route = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        return '#^' . $route . '$#';
    }
    
    private function callAction($action, $params = []) {
        list($controller, $method) = explode('@', $action);

        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                try {
                    call_user_func_array([$controllerInstance, $method], $params);
                } catch (Exception $e) {
                    if (strpos($e->getMessage(), 'Token CSRF invalide') !== false) {
                        // CSRF token invalid, redirect to login with error
                        $_SESSION['error'] = 'Session expirée. Veuillez vous reconnecter.';
                        header("Location: " . (defined('BASE_URL') ? BASE_URL : '') . "login");
                        exit;
                    } else {
                        // Other exceptions, log and show 500 error
                        error_log("Exception in {$controller}->{$method}: " . $e->getMessage());
                        http_response_code(500);
                        include 'views/errors/500.php';
                        exit;
                    }
                }
            } else {
                throw new Exception("Method {$method} not found in {$controller}");
            }
        } else {
            throw new Exception("Controller {$controller} not found");
        }
    }
}
?>
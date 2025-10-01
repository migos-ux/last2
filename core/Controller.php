<?php
class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function view($view, $data = []) {
        extract($data);

        if (strpos($view, 'auth/') === 0) {
            // Auth views are standalone, no layout
            include "views/{$view}.php";
        } else {
            ob_start();
            include "views/{$view}.php";
            $content = ob_get_clean();

            include 'views/layouts/main.php';
        }
    }
    
    protected function adminView($view, $data = []) {
        extract($data);
        
        ob_start();
        include "views/admin/{$view}.php";
        $content = ob_get_clean();
        
        include 'views/layouts/admin.php';
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    protected function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
    
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('login');
        }
    }
    
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    protected function validateCSRF() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Token CSRF invalide');
        }
    }
    
    protected function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
?>
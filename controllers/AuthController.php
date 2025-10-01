<?php
class AuthController extends Controller {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $email = $this->sanitize($_POST['email']);
            $password = $_POST['password'];
            
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            
            if ($user && $userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];
                
                $userModel->updateLastLogin($user['id']);
                
                // Regenerate CSRF token after successful login
                unset($_SESSION['csrf_token']);
                $this->generateCSRF();
                
                if ($user['role'] === 'admin') {
                    $this->redirect('admin');
                } else {
                    $this->redirect('');
                }
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }
        
        $this->view('auth/login', [
            'error' => $error ?? null,
            'csrf_token' => $this->generateCSRF(),
            'title' => 'Connexion',
            'BASE_URL' => BASE_URL
        ]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = $this->sanitize($_POST);
            $errors = $this->validateRegistration($data);
            
            if (empty($errors)) {
                $userModel = new User();
                
                // Vérifier si l'email existe déjà
                if ($userModel->findByEmail($data['email'])) {
                    $errors[] = 'Cet email est déjà utilisé';
                } else {
                    $userId = $userModel->createUser([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => $data['password'],
                        'role' => 'customer'
                    ]);
                    
                    $_SESSION['user'] = [
                        'id' => $userId,
                        'email' => $data['email'],
                        'name' => $data['name'],
                        'role' => 'customer'
                    ];
                    
                    // Regenerate CSRF token after successful registration
                    unset($_SESSION['csrf_token']);
                    $this->generateCSRF();
                    
                    $this->redirect('');
                }
            }
        }
        
        $this->view('auth/register', [
            'errors' => $errors ?? [],
            'csrf_token' => $this->generateCSRF(),
            'title' => 'Inscription',
            'BASE_URL' => BASE_URL
        ]);
    }
    
    public function logout() {
        session_destroy();
        // Regenerate CSRF token after logout
        session_start();
        unset($_SESSION['csrf_token']);
        $this->generateCSRF();
        $this->redirect('');
    }
    
    private function validateRegistration($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Le nom est requis';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email valide requis';
        }
        
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }
        
        return $errors;
    }
}
?>
<?php
class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index() {
        $transactionModel = new Transaction();
        $productModel = new Product();
        
        // Statistiques générales
        $analytics = $transactionModel->getAnalytics();
        $recentTransactions = $transactionModel->getRecentTransactions(10);
        $topProducts = $productModel->getTopSelling(5);
        
        $this->adminView('dashboard', [
            'analytics' => $analytics,
            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
            'title' => 'Tableau de bord'
        ]);
    }
    
    public function products() {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $products = $productModel->findAll();
        $categories = $categoryModel->getActive();
        
        $this->adminView('products/index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Gestion des produits'
        ]);
    }
    
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            $data = $this->sanitize($_POST);
            $errors = $this->validateProduct($data);

            // Gestion de l'upload d'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->uploadImage($_FILES['image']);
                if ($uploadResult['success']) {
                    $data['image'] = $uploadResult['filename'];
                } else {
                    $errors[] = $uploadResult['error'];
                }
            }

            if (empty($errors)) {
                $productModel = new Product();
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['status'] = $data['status'] ?? 'active';
                $data['country'] = $data['country'] ?? 'FR';
                $data['featured'] = isset($data['featured']) ? 1 : 0;

                $productModel->create($data);
                $_SESSION['flash_message'] = 'Produit créé avec succès!';
                $this->redirect('admin/products');
            }
        }
        
        $categoryModel = new Category();
        $categories = $categoryModel->getActive();
        
        $this->adminView('products/create', [
            'categories' => $categories,
            'errors' => $errors ?? [],
            'csrf_token' => $this->generateCSRF(),
            'title' => 'Ajouter un produit'
        ]);
    }
    
    public function editProduct($id) {
        $productModel = new Product();
        $product = $productModel->findById($id);
        
        if (!$product) {
            $this->redirect('admin/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = $this->sanitize($_POST);
            $errors = $this->validateProduct($data);
            
            // Gestion de l'upload d'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->uploadImage($_FILES['image']);
                if ($uploadResult['success']) {
                    // Supprimer l'ancienne image
                    if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])) {
                        unlink(UPLOAD_PATH . $product['image']);
                    }
                    $data['image'] = $uploadResult['filename'];
                } else {
                    $errors[] = $uploadResult['error'];
                }
            }
            
            if (empty($errors)) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['country'] = $data['country'] ?? 'FR';
                $data['featured'] = isset($data['featured']) ? 1 : 0;
                $productModel->update($id, $data);
                $_SESSION['flash_message'] = 'Produit modifié avec succès!';
                $this->redirect('admin/products');
            }
        }
        
        $categoryModel = new Category();
        $categories = $categoryModel->getActive();
        
        $this->adminView('products/edit', [
            'product' => $product,
            'categories' => $categories,
            'errors' => $errors ?? [],
            'csrf_token' => $this->generateCSRF(),
            'title' => 'Modifier le produit'
        ]);
    }
    
    public function deleteProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $productModel = new Product();
            $product = $productModel->findById($id);
            
            if ($product) {
                // Supprimer l'image
                if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])) {
                    unlink(UPLOAD_PATH . $product['image']);
                }
                
                $productModel->delete($id);
                $_SESSION['flash_message'] = 'Produit supprimé avec succès!';
            }
        }
        
        $this->redirect('admin/products');
    }
    
    public function transactions() {
        $transactionModel = new Transaction();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $transactions = $transactionModel->getRecentTransactions($limit);
        $totalTransactions = $transactionModel->count();
        $totalPages = ceil($totalTransactions / $limit);
        
        $this->adminView('transactions/index', [
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Gestion des transactions'
        ]);
    }
    
    public function analytics() {
        $transactionModel = new Transaction();
        
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        
        $analytics = $transactionModel->getAnalytics($startDate, $endDate);
        $channelStats = $transactionModel->getChannelStats($startDate, $endDate);
        $dailyStats = $transactionModel->getDailyStats(30);
        
        $this->adminView('analytics/index', [
            'analytics' => $analytics,
            'channelStats' => $channelStats,
            'dailyStats' => $dailyStats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Analytics et KPIs'
        ]);
    }
    
    public function kpis() {
        $transactionModel = new Transaction();
        $productModel = new Product();
        
        // KPIs avancés
        $monthlyAnalytics = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m-01', strtotime("-{$i} months"));
            $endDate = date('Y-m-t', strtotime($date));
            $monthlyAnalytics[] = array_merge(
                ['month' => date('M Y', strtotime($date))],
                $transactionModel->getAnalytics($date, $endDate)
            );
        }
        
        $topProducts = $productModel->getTopSelling(10);
        $channelPerformance = $transactionModel->getChannelStats();
        
        $this->adminView('kpis/index', [
            'monthlyAnalytics' => $monthlyAnalytics,
            'topProducts' => $topProducts,
            'channelPerformance' => $channelPerformance,
            'title' => 'KPIs Avancés'
        ]);
    }
    
    private function validateProduct($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Le nom du produit est requis';
        }
        
        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors[] = 'Le prix doit être un nombre positif';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = 'La catégorie est requise';
        }
        
        return $errors;
    }
    
    private function uploadImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = MAX_FILE_SIZE;
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Type de fichier non autorisé'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Fichier trop volumineux'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadPath = UPLOAD_PATH . $filename;
        
        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Erreur lors de l\'upload'];
        }
    }
}
?>